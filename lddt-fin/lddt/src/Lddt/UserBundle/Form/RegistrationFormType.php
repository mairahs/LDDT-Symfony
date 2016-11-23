<?php

namespace Lddt\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', null,
                array('label' => 'form.username',
                    'attr'=>array('class'=>'form-control'),
                    'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email',
                array('label' => 'form.email',
                    'attr'=>array('class'=>'form-control'),
                    'translation_domain' => 'FOSUserBundle'))

            ->add('plainPassword', 'repeated',
                array(
                'type' => 'password',

                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' =>
                    array('label' => 'form.password',
                          'attr'=>array('class'=>'form-control')),

                'second_options' =>
                    array('label' => 'form.password_confirmation',
                          'attr'=>array('class'=>'form-control')),

                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('avatarFile','file',array('label'=>'Uploadez votre avatar',
            "attr"=>array('class'=>'form-control')));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'lddt_user_registration';
    }
}