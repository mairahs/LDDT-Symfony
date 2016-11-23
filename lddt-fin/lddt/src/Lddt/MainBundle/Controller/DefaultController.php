<?php

namespace Lddt\MainBundle\Controller;

use Lddt\MainBundle\Entity\Color;
use Lddt\MainBundle\Form\CommentType;

use Lddt\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Lddt\MainBundle\Entity\Category;
use Lddt\MainBundle\Entity\Draw;
use Lddt\MainBundle\Entity\Comment;
use Lddt\MainBundle\Form\DrawType;
use Lddt\MainBundle\Form\FormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $draws = $em->getRepository("LddtMainBundle:Draw")->findAll();
        $draws = $em->getRepository("LddtMainBundle:Draw")->findAllDraws();

        // On créé un tableau assoc, les clés de ce tableau deviendront les variables dans ma vue
        $datas = array('draws'=>$draws);
        // La méthode render prend 2 paramas : le nom de la vue + les données (un tableau associatif)
        return $this->render('LddtMainBundle:Default:index.html.twig',$datas);
    }

    public function createAction(Request $request) {
        // METHODE 1 > Persister un objet sans passer par un formulaire
//        $draw = new Draw();
//        $draw->setTitle("lost 2");
//        $draw->setDrawPath("lost.jpg");
//        $draw->setIsOnline(true);
//        $draw->setAuthorIcoPath("charlie-ico.jpg");
//        $draw->setAuthorName("Charlie");
//        $draw->setCreatedAt(new \DateTime());
//        $draw->setUpdatedAt(new \DateTime());
//
//        $categorie = new Category();
//        $categorie->setName('humour');
////
//        $draw->setCategory($categorie);
////
////        // Appel du service Doctrine
//        $em = $this->getDoctrine()->getManager();
////        // Démarrage transaction SQL
//        $em->persist($draw);
//        $em->persist($categorie);
////
////        // Exécution de la requête pour sauvegarder dans la DB
//        $em->flush();
//        die();
        
        // Chaque action doit produire une réponse
        // Plusieurs types de réponses possible > redirection, affichage d'une vue, génération de code json (ajax) etc...
//        return $this->redirect($this->generateUrl('lddt_main_homepage'));

        // Methode 2 > Persister une entité via un formulaire

        // Création de l'instance à persister via le formulaire
        // Récupération de l'utilisateur connecté
        $user = $this->get('security.context')->getToken()->getUser();
        $draw = new Draw($user);
        // Créer le formulaire
        $form = $this->createForm(new DrawType(),$draw);
        // Appel de la class dédiée à la validation d'un formulaire
        $em = $this->getDoctrine()->getManager();
        $formHandler = new FormHandler($form,$request,$em);
        if($formHandler->process()) {
            return $this->redirect($this->generateUrl("lddt_main_show",array('id'=>$draw->getId())));
        }
        $datas = array('form'=>$form->createView());
        return $this->render('LddtMainBundle:Default:create.html.twig',$datas);

    }

    // Voir un dessin

    /**
     * @Template
     * Cette annotation permet d'éviter d'appeler la méthode render avec en
     * param le nom de la vue. Il suffit de créer un fichier twig du même nom que l'action et la vue sera générée automatiquement
     *
     */
    public function showAction($id,Request $request) {
        $em = $this->getDoctrine()->getManager();
        $draw = $em->getRepository('LddtMainBundle:Draw')->find($id);
        // Req SQL SELECT * FROM draw WHERE id = $id;

        // Récupérer les commentaires du dessin, c'est possible grâce à la relation bi-directionnelle entre draw et comments.
        $comments = $draw->getComments();

        // Créa instance du formulaire de commentaires
        $form_comments = new CommentType();
        // Récupérer le user connecté
        $user = $this->get('security.context')->getToken()->getUser();
        // Créa instance formulaire
        $comment = new Comment($draw,$user);
        $form = $this->createForm($form_comments,$comment);

        // Comparaison avec l'auteur du dessin
        $author = $draw->getUser();

        if($user == $author) {
            $is_owner = true;
        } else {
            $is_owner = false;
        }
        // Si Validation du formulaire
        $formHandler = new FormHandler($form,$request,$em);
        if($formHandler->process()) {
            //Produire une réponse dédiée à la validation
return $this->redirect($this->generateUrl('lddt_main_show',
    array('id'=>$draw->getId())));
        }
        // Produire une réponse pour affichage du formulaire
        // Créa de la vue du formulaire (HTML)
        $datas = array('draw'=>$draw,
                        'form'=>$form->createView(),
                        'comments'=>$comments,
                        'is_owner'=>$is_owner);
        return $datas;
    }

    public function publishDrawAction(Draw $draw){
        if($draw->getIsOnline() == true) {
            $draw->setIsOnline(false);
        } else {
            $draw->setIsOnline(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($draw);
        $em->flush();

        $draws = $em->getRepository("LddtMainBundle:Draw")->findAllDraws();

        // On créé un tableau assoc, les clés de ce tableau deviendront les variables dans ma vue
        $datas = array('draws'=>$draws);
        return $this->render('LddtMainBundle:Default:index.html.twig',$datas);
    }
    /**
     * @Template
     */
    public function editAction(Draw $draw, Request $request) {
        $form = $this->createForm(new DrawType, $draw);
        $em = $this->getDoctrine()->getManager();
        // Récupérer le user connecté
        $user = $this->get('security.context')->getToken()->getUser();
        // Comparaison avec l'auteur du dessin
        $author = $draw->getUser();

        if($user == $author) {
            $is_owner = true;
        } else {
            $is_owner = false;
        }
        $formHandler = new FormHandler($form,$request,$em);
        if($formHandler->process()) {
            return $this->redirect($this->generateUrl("lddt_main_show",array('id'=>$draw->getId())));
        }
        // Envoie des datas à la vue en mode affichage du formulaire d'édition
        $datas = array('form'=>$form->createView(),'draw'=>$draw,'is_owner'=>$is_owner);
        return $datas;
    }

    public function deleteAction(Draw $draw) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($draw);
        $em->flush();
        // Stocké un message de confirmation dans la session du user
        $this->get('session')->getFlashBag()
    ->add('success','le dessin a'.$draw->getTitle().' a bien été supprimé');
        return $this->redirect($this->generateUrl('lddt_main_homepage'));
    }


    // ParamConverter > // SELECT * FROM category WHERE id = 4 > Résultat sous forme d'objet
    public function listDrawsByCatAction(Category $category) {
      $em = $this->getDoctrine()->getManager();

      $draws = $em->getRepository('LddtMainBundle:Draw')
                   ->findAllDrawsByCat($category);
        // Réponse
        // Variables envoyées à la vue
        $datas = array('draws'=>$draws,'category'=>$category);
        // On utilise le même template que pour l'action index
       return $this->render('LddtMainBundle:Default:index.html.twig', $datas);
    }

    public function listDrawsByAuthorAction(User $author) {
        $em = $this->getDoctrine()->getManager();
        $draws = $em->getRepository('LddtMainBundle:Draw')
                    ->findAllDrawsByAuthor($author);

        $datas = array('draws'=>$draws, 'author'=>$author);
        return $this->render('LddtMainBundle:Default:index.html.twig',$datas);

    }

    public function listDrawsByColorAction(Color $color) {

        $em = $this->getDoctrine()->getManager();
        $draws = $em->getRepository('LddtMainBundle:Draw')
                    ->findAllDrawsByColor(array($color->getName()));

        $datas = array('draws'=>$draws,'color'=>$color);
        return $this->render('LddtMainBundle:Default:index.html.twig',$datas);
    }












    public function testAction() {
        return $this->render('LddtMainBundle:Default:test.html.twig');
    }
}
