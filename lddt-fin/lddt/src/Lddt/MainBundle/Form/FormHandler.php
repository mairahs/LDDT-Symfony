<?php


namespace Lddt\MainBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormHandler > prend en main la validation d'un formulaire
 * @package Lddt\MainBundle\Form
 */
class FormHandler {
    // Le formulaire à persister
    protected $form;
    // L'entête de ma requête HTTP
    protected $request;
    // L'entity Manager de Doctrine
    protected $em;

    // injection de de dépendances
    public function __construct(Form $form,Request $request,EntityManager $em) {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    // Etape 1 > vérif des données du formulaire
    public function process() {
        // si validation du formulaire via la méthode POST
        if($this->request->getMethod()=="POST") {
            // Récupération des données dans l'en-tête HTTP via $_POST ou $_REQUEST
            $this->form->handleRequest($this->request);
            // Vérif de la validé des données du form via les validateurs symfony
            if($this->form->isValid()) {
                // Appel de la méthode saveForm (etape2)
                $this->saveForm($this->form->getData());
                return true;
            } else {
                return false;
            }
        }
    }

    // Etape 2 > si succès, on persiste les données du form via l'entity manager de Doctrine
    public function saveForm($datas_form_obj) {
        $this->em->persist($datas_form_obj);
        $this->em->flush();
    }




}