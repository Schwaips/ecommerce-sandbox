<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['placeholder' => 'Entrez votre adresse e-mail'],
            ])
            ->add('plainPassword', RepeatedType::class, [
              'type' => PasswordType::class,
              'first_options'  => [
                'label' => 'Entrez votre mot de passe', 
                'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                'hash_property_path' => 'password',
                'constraints' => [
                  new Length([
                    'min' => 4,
                    'max' => 30
                  ])
                ]
              ],
              'second_options' => [
                'label' => 'Confirmer le mot de passe',
                'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
              ],
              'mapped' => false,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
                'constraints' => [
                  new Length([
                    'min' => 2,
                    'max' => 30
                  ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
                'constraints' => [
                  new Length([
                    'min' => 2,
                    'max' => 30
                  ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'btn btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
              new UniqueEntity([
                'entityClass' => User::class,
                'fields' => ['email'],
                'message' => 'Cette adresse e-mail est déjà utilisée.',
              ])
            ],
            'data_class' => User::class,
        ]);
    }
}
