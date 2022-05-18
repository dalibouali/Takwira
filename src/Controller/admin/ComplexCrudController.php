<?php

namespace App\Controller\admin;

use App\Entity\Complex;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;



class ComplexCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Complex::class;
    }

    public function configureActions(Actions $actions): Actions

    {

        //bouton pour afficher plus de detail de l'utilisateur

        $detailaction=Action::new( 'detail','details','fa fa-user')

            ->linkToCrudAction(Crud::PAGE_DETAIL)
            ->addCssClass('btn btn-info' );


        return $actions

            ->add(Crud::PAGE_INDEX,$detailaction);
    }
}