<?php

namespace App\Form;

use App\Entity\Stock;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit_id', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'placeholder' => 'Sélectionnez un produit',
                'attr' => ['class' => 'select2'], // Ajoutez cette ligne pour utiliser le plugin Select2 (optionnel)
            ])
            ->add('quantite', IntegerType::class)
            ->add('fournisseur_id', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => Fournisseur::class, //Pour marquer que c'est un objet Fournisseur et récupérer son id
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un fournisseur',
                'attr' => ['class' => 'select2'], // le plugin Select2 (optionnel)
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
