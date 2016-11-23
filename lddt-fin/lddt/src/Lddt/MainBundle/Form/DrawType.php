<?php

namespace Lddt\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DrawType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array('label'=>"Nom de votre dessin",
                                        "attr"=>array('class'=>'form-control')));
//            ->add('drawPath','text',array('label'=>'chemin du fichier',
//                                        "attr"=>array('class'=>'form-control')))

        // Si mode édition, la méthode getId() return true, donc on affiche pas le champ upload file,
        // si mode création getId() return false, donc on affiche le champ upload File
           if(is_numeric($builder->getData()->getId()) == false)   {
               $builder->add('drawFile','file',array('label'=>'Uploadez votre dessin',
                   "attr"=>array('class'=>'form-control')));
           }

//            ->add('isOnline')
//            ->add('authorName','text',array('label'=>"pseudo",'attr'=>array('class'=>'form-control')))
//            ->add('authorIcoPath','text',array('label'=>'chemin du fichier avatar',
//                "attr"=>array('class'=>'form-control')))
//            ->add('createdAt')
//            ->add('updatedAt')
            $builder->add('category','entity',
                array('label'=>'catégorie du dessin',
                      'attr'=>array('class'=>'form-control'),
                      'class'=>'Lddt\MainBundle\Entity\Category',
                      'choice_label'=>'name'))
            ->add('colors','entity',
            array('label'=>"choisissez les couleurs du dessin",
                'attr'=>array('class'=>'form-control'),
                'class'=>'Lddt\MainBundle\Entity\Color',
                'choice_label'=>'name',
                'expanded'=>true,
                'multiple'=>true

            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lddt\MainBundle\Entity\Draw'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lddt_mainbundle_draw';
    }
}
