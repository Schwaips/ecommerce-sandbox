<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Form\FormEvents;


class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
        ->add('actualPassword', PasswordType::class, [ 
          'label' => 'Mot de passe actuel',
          'attr' => ['placeholder' => 'Entrez votre mot de passe actuel'],
          'mapped' => false,
        ])
        ->add('plainPassword', RepeatedType::class, [
          'type' => PasswordType::class,
          'first_options'  => [
            'label' => 'Nouveau mot de passe', 
            'attr' => ['placeholder' => 'Entrez votre nouveau mot de passe'],
            'hash_property_path' => 'password',
            'constraints' => [
              new Length([
                'min' => 2,
                'max' => 30
              ])
              ],
          ],
          'second_options' => [
            'label' => 'Confirmer le nouveau mot de passe',
            'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
          ],
        'mapped' => false,
        ])
        ->add('submit', SubmitType::class, [
          'label' => 'Modifier mon mot de passe',
          'attr' => ['class' => 'btn btn-primary'],
        ])
        ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
          $form = $event->getForm();
          $user = $form->getConfig()->getOptions()["data"];
          $passwordHasher = $form->getConfig()->getOptions()["userPasswordHasher"];

          # mot de passe saisie par l'user
          $actualPasswordInForm = $form->get('actualPassword')->getData();
          # comparer le mot de passe avec celui en DB.
          $passwordIsValidAndSameAsActualInDb = $passwordHasher->isPasswordValid($user, $actualPasswordInForm);

          if(!$passwordIsValidAndSameAsActualInDb) {
              $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme."));
          }
        });
  }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'userPasswordHasher' => null,
        ]);
    }
}
