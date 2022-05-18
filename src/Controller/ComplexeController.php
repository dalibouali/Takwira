<?php

namespace App\Controller;

use App\data\SearchData;
use App\Entity\Complex;
use App\Form\SearchForm;
use App\Repository\ComplexRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComplexeController extends AbstractController
{
    /**
     * @Route("/complexe", name="complexe")
     */
    public function index(Request $request,ComplexRepository $repo, PaginatorInterface $paginator): Response
    {



        $data= new SearchData();
        $form=$this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list=$repo->findByville($form->get('ville')->getData());

        }else{

            $list=$repo->findAll();

        }







        $list=$paginator->paginate(
            $list,$request->query->getInt('page',1),4);
        return $this->render('complexe/index.html.twig', [
            'complexes'=>$list,
            'form' => $form->createView(),
            'controller_name' => 'ComplexeController',
        ]);
    }
}
