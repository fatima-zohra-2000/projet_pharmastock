<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre nom complet',
                    ]),
                ],
            ])
            ->add('email')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les champs de mot de passe et sa confirmation doivent correspondre.',
                'options' => ['attr' => ['autocomplete' => 'new-password']],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit au moin faire {{ limit }} charactères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les canditions d\'utilisation.',
                    ]),
                ],
            ]);
    }
        
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
        }
    }
    
    // ->add('plainPassword', PasswordType::class, [
    //     // instead of being set onto the object directly,
    //     // this is read and encoded in the controller
    //     'mapped' => false,
    //     'attr' => ['autocomplete' => 'new-password'],
    //     'constraints' => [
    //         new NotBlank([
    //             'message' => 'Please enter a password',
    //         ]),
    //         new Length([
    //             'min' => 6,
    //             'minMessage' => 'Your password should be at least {{ limit }} characters',
    //             // max length allowed by Symfony for security reasons
    //             'max' => 4096,
    //         ]),
    //     ],
    // ])