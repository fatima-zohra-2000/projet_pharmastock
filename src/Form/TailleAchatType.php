<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\TailleAchat;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TailleAchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('quantite')
//            ->add('prix')
//            ->add('achat')
//            ->add('produit')
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'placeholder' => 'Sélectionnez un produit',
                'attr' => ['class' => 'select2'], // cette ligne pour utiliser le plugin Select2 (optionnel)
            ])

            ->add('quantite', IntegerType::class, [
                'attr' => ['value' => 1], // Définir la valeur par défaut à 1
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TailleAchat::class,
        ]);
    }
}
