<?php
//
//namespace App\Controller\Admin;
//
//use App\Entity\Fournisseur;
//use App\Entity\Produit;
//use App\Repository\FournisseurRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
//use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
//use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
//use Vich\UploaderBundle\Form\Type\VichImageType;
//use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
//use App\Controller\Admin\FournisseurCrudController;
//
//class ProduitCrudController extends AbstractCrudController
//{
//    private EntityManagerInterface $entityManager;
//
//    public function __construct(EntityManagerInterface $entityManager)
//    {
//        $this->entityManager = $entityManager;
//    }
//    public static function getEntityFqcn(): string
//    {
//        return Produit::class;
//    }
//
//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            IdField::new('id')->hideOnForm(),
//            TextField::new('nom'),
//            IntegerField::new('prix'),
//            IntegerField::new('Stock.quantite')->setLabel('Quantité'),
//            BooleanField::new('ordonnance')
//                ->setFormTypeOption('value', true) // Définit la valeur par défaut du champ à true (coché)
//                ->setFormTypeOption('attr', ['checked' => 'checked']) // Coche la case par défaut
//                ->hideOnIndex(), // Cache le champ dans la liste d'index
//            AssociationField::new('categorie_id'),
//            Field::new('imageFile')->setFormType(VichImageType::class),
////            ChoiceField::new('Stock.fournisseur_id')->setLabel('Fournisseur'),
////            ChoiceField::new('Stock.fournisseur_id', 'Fournisseur')
////                ->setChoices(function () {
////                    $fournisseurs = $this->entityManager->createQueryBuilder()
////                        ->select('f.id', 'f.nom') // Sélectionnez les champs ID et nom
////                        ->from(Fournisseur::class, 'f')
////                        ->getQuery()
////                        ->getResult();
////
////                    $choices = [];
////
////                    foreach ($fournisseurs as $fournisseur) {
////                        $choices[$fournisseur['nom']] = $fournisseur['id'];
////                    }
////
////                    return $choices;
////                })
////                ->allowMultipleChoices(false)
////                ->onlyOnForms(),
//            AssociationField::new('stock.fournisseur_id', 'Fournisseur')
//                ->setFormTypeOptions([
//                    'class' => Fournisseur::class,
//                    'choice_label' => 'nom',
//                    'query_builder' => function (FournisseurRepository $fournisseurRepository) {
//                        return $fournisseurRepository->createQueryBuilder('f')
//                            ->orderBy('f.nom', 'ASC');
//                    },
//                ])
//                ->setCrudController(FournisseurCrudController::class)
//                ->onlyOnForms(),
////            AssociationField::new('Stock.fournisseur_id'),
//            // Si vous souhaitez également afficher l'image dans la liste d'index, décommentez la ligne suivante :
//            // ImageField::new('image')->setBasePath('/uploads/images');
//
//        ];
//    }
//}


namespace App\Controller\Admin;

use App\Entity\Fournisseur;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Form\ProduitType;
use App\Repository\FournisseurRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    private ProduitRepository $produitRepository;

    public function __construct(EntityManagerInterface $entityManager, ProduitRepository $produitRepository)
    {
        $this->entityManager = $entityManager;
        $this->produitRepository = $produitRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            IntegerField::new('prix'),
            IntegerField::new('stock.quantite')->setLabel('Quantité'),
            BooleanField::new('ordonnance')
                ->setFormTypeOption('value', true)
                ->setFormTypeOption('attr', ['checked' => 'checked'])
                ->hideOnIndex(),
            AssociationField::new('categorie_id'),
            AssociationField::new('stock.fournisseur_id', 'Fournisseur')
//                ->setFormTypeOptions([
//                    'class' => Fournisseur::class,
//                    'choice_label' => 'nom',
//                    'query_builder' => function (FournisseurRepository $fournisseurRepository) {
//                        return $fournisseurRepository->createQueryBuilder('f')
//                            ->orderBy('f.nom', 'ASC');
//                    },
//                ])
                ->setCrudController(FournisseurCrudController::class),
//                ->onlyOnForms(),
            Field::new('imageFile')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),

        ];
    }

    public function createEntity(string $entityFqcn): Produit
    {
        $produit = new Produit();
        $produit->setStock(new Stock()); // Création d'une nouvelle instance de Stock

        return $produit;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->produitRepository->save($entityInstance, true);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->produitRepository->save($entityInstance, true);
    }
}
