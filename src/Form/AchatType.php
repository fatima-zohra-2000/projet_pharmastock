<?php

namespace App\Form;

use App\Entity\Achat;
use App\Entity\Fournisseur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('num_achat')
//            ->add('TVA')
//            ->add('mantant_TVA')
//            ->add('total')
//            ->add('date')
//            ->add('fournisseur')
            ->add('tailleAchats', CollectionType::class, [
                'entry_type' => TailleAchatType::class,
                'entry_options' => ['label' => false], // cette ligne pour annuler tout label en haut venant de taille_commande
                'allow_add' => true,
                'by_reference' => false,
                'label' => false, // cette ligne pour annuler le label taille_commande
            ])
            ->add('fournisseur', EntityType::class, [
                'class' => Fournisseur::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'placeholder' => 'Sélectionnez un fournisseur',
                'attr' => ['class' => 'select2'], // cette ligne pour utiliser le plugin Select2 (optionnel)
            ])
            ->add('TVA', ChoiceType::class, [
                'choices' => [
                    '0%' => 0,
                    '10%' => 10,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 10, // Définir la valeur par défaut à 10
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}
