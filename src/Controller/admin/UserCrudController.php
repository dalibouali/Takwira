<?php

namespace App\Controller\admin;

use App\Entity\Proprietaire;
use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\HttpFoundation\Response;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }






    public function configureActions(Actions $actions): Actions

    {


        //bouton pour afficher plus de detail de l'utilisateur
        $detailaction = Action::new('detail', 'details', 'fa fa-user')
            ->linkToCrudAction(Crud::PAGE_DETAIL)
            ->addCssClass('btn btn-info');


        return $actions
            ->add(Crud::PAGE_INDEX, $detailaction);
    }

    // ajouter des filtres a la recherche
    public function configureFilters(Filters $filters): Filters
    {
        return $filters

            ->add('pseudo')
            ->add('roles')

            ;
    }}

// les champs a afficher
/* public function configureFields(string $pageName): iterable
 {
     return [
         IdField::new('id'),
         TextField::new('Username'),
         TextField::new('Email'),
         TextField::new('type'),
     ];
 }

}*/
