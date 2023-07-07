<?php

namespace App\Form;

use App\Entity\Client;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('tel')
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
