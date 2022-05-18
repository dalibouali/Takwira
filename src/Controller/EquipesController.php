<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Entity\User;
use App\Form\EquipesType;
use App\Repository\EquipesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
* @Route("joueur/equipes")
*/
class EquipesController extends AbstractController
{




/**
* @Route("/", name="equipes_index", methods={"GET"})
 */
    public function index(EquipesRepository $equipesRepository,AuthenticationUtils $authenticationUtils,UserRepository $userRepository): Response
{


    $email=$authenticationUtils->getLastUsername();
    $user=$this->getUser();
    $username=$user->getUsername();


    return $this->render('equipes/index.html.twig', [
        'equipes' => $equipesRepository->findAll(),
        'user'=>$user,
        'username'=>$username

    ]);
}
    /**
     * @Route("/new", name="equipes_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepository,AuthenticationUtils $authenticationUtils): Response
    {
        $equipe = new Equipes();
        $form = $this->createForm(EquipesType::class, $equipe);
        $form->handleRequest($request);
        $email=$authenticationUtils->getLastUsername();
        $user=$this->getUser();
        $equipe->setCapitaine($user);




        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('equipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipes/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }
/**
* @Route("/{id}", name="equipes_show", methods={"GET"})
*/
public function show(Equipes $equipe,AuthenticationUtils $authenticationUtils,UserRepository $userRepository): Response
{
    $email=$authenticationUtils->getLastUsername();
    $user=$this->getUser();
    $username=$user->getUsername();
    return $this->render('equipes/show.html.twig', [
        'equipe' => $equipe,
        'username'=>$username
    ]);
}

/**
* @Route("/{id}/edit", name="equipes_edit", methods={"GET","POST"})
 */
    public function edit(Request $request, Equipes $equipe): Response
{
    $form = $this->createForm(EquipesType::class, $equipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('equipes_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('equipes/edit.html.twig', [
        'equipe' => $equipe,
        'form' => $form,
    ]);
}

    /**
     * @Route("/{id}/delete", name="equipes_delete", methods={"GET","POST"})
     */
    public function delete($id): Response
{

    $entityManager = $this->getDoctrine()->getManager();
    $equipe=$entityManager->getRepository("App\Entity\Equipes")->find($id);
    $entityManager->remove($equipe);
    $entityManager->flush();


    return $this->redirectToRoute('equipes_index', [], Response::HTTP_SEE_OTHER);
}
}

