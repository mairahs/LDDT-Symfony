<?php

namespace Lddt\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('author','text',
//                array('label'=>"Votre pseudo",
//                "attr"=>array('class'=>'form-control')))
            ->add('content','textarea',
                array('label'=>"Votre commentaire",
                "attr"=>array('class'=>'form-control')))

//            ->add('createdAt')
//            ->add('updatedAt')
//            ->add('draw')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lddt\MainBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lddt_mainbundle_comment';
    }
}
