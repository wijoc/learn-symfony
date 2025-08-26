<?php

namespace App\Form;

use App\Entity\Users;
// use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => [
                    'placeholder' => 'Enter username'
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email ',
                'attr' => [
                    'placeholder' => 'Enter e-mail address'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Enter password'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => 'First Name',
                'attr' => [
                    'placeholder' => 'Enter First Name'
                ]
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name',
                'attr' => [
                    'placeholder' => 'Enter Last Name'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary']
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
