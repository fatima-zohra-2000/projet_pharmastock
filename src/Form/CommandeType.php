<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Commande;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Valid;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

//            ->add('client', EntityType::class, [
//                'class' => Client::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('p')
//                        ->orderBy('p.nom', 'ASC');
//                },
//                'choice_label' => 'nom',
//                'choice_value' => 'id',
//                'placeholder' => 'Sélectionnez un client',
//                'attr' => ['class' => 'select2'], // Ajoutez cette ligne pour utiliser le plugin Select2 (optionnel)
//            ])
//            ->add('newClient', CollectionType::class, [
//                'entry_type' => ClientType::class,
//                'entry_options' => ['label' => false], // cette ligne pour annuler tout label en haut venant de taille_commande
//                'allow_add' => true,
//                'by_reference' => false,
//                'label' => false,
//            ])

            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un client',
                'required' => false,
                'constraints' => [
                    new Valid(), // la contrainte Valid pour valider l'entité Client sélectionnée
                ],
                'attr' => ['class' => 'select2'], // le plugin Select2 (optionnel)
            ])
            ->add('newClient', ClientType::class, [
                'label' => 'Ajouter un nouveau client',
                'mapped' => false,
                'required'=> false,
            ])

            ->add('tailleCommandes', CollectionType::class, [
                'entry_type' => TailleCommandeType::class,
                'entry_options' => ['label' => false], // cette ligne pour annuler tout label en haut venant de taille_commande
                'allow_add' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('TVA', ChoiceType::class, [
                'choices' => [
                    '0%' => 0,
                    '10%' => 10,
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
