<?php

namespace App\Controller\Admin;

use App\Entity\Conference;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class ConferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conference::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Conference Comment')
            ->setEntityLabelInPlural('Conference Comments')
            ->setSearchFields(['author', 'text', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC']);

    }
    
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('createdAt')
        ;
    }
    
    
//    public function configureFields(string $pageName): iterable
//    {
//        yield AssociationField::new('conference');
//        yield TextField::new('author');
//        yield EmailField::new('email');
//        yield TextareaField::new('text')
//            ->hideOnIndex()
//        ;
//        yield TextField::new('photoFilename')
//            ->onlyOnIndex()
//        ;
//
//        $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
//            'html5' => true,
//            'years' => range(date('Y'), date('Y')  5),
//                'widget' => 'single_text',
//            ]);
//        if (Crud::PAGE_EDIT === $pageName) {
//            yield $createdAt->setFormTypeOption('disabled', true);
//        } else {
//            yield $createdAt;
//        }
//    }
    
}
