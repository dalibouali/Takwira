<?php

namespace App\Controller;
use App\Entity\Complex;
use App\Entity\Terrain;
use App\Form\Complex1Type;
use App\Form\ComplexType;
use App\Form\RegistrationFormType;
use App\Form\TerrainType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ComplexRepository;
use App\Repository\ReservationRepository;
use App\Repository\TerrainRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/proprietaire")
 */
class ProprietaireController extends AbstractController
{
    /**
     * @Route("/", name="proprietaireapp")
     */
    public function index(TerrainRepository $terrainRepository,ComplexRepository $complexRepository): Response
    {
        $complex=$complexRepository->findOneBy(['owner'=>$this->getUser()]);



        return $this->render('proprietaire/index.html.twig', [
            'controller_name' => 'ProprietaireController',
            'terrains' => $terrainRepository->findBy(['complex'=>$complex]),
        ]);
    }

    /**
     * @Route("/new", name="new_terrain", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ComplexRepository $complexRepository): Response
    {
        $terrain = new Terrain();
        $complex=$complexRepository->findOneBy(['owner'=>$this->getUser()]);
        $terrain->setComplex($complex);


        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($terrain);
            $entityManager->flush();

            return $this->redirectToRoute('proprietaireapp', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terrain/new.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="show_terrain", methods={"GET"})
     */
    public function show($id ,ReservationRepository $reservationRepository): Response
    {


        $reservation =$reservationRepository->findBy(['terrain'=>$id]);
        return $this->render('terrain/show.html.twig', [

            'reservation'=>$reservation

        ]);
    }

    /**
     * @Route("/setting", name="setting")
     */
    public function editcomplex(Request $request ,ComplexRepository $complexRepository,EntityManagerInterface $entityManager):Response
    {
        $complex=$complexRepository->findOneBy(['owner'=>$this->getUser()]);

        $form =$this->createForm(ComplexType::class, $complex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $complex->setOwner($this->getUser());

            $entityManager->persist($complex);
            $entityManager->flush(); }


        return $this->render('proprietaire/complex.html.twig', [
            'form' => $form->createView(),
        ]);



    }
    /**
     * @Route("/profil", name="proprietaireedit")
     */
    public function edit(Request $request ,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager):Response
    {
        $user=$this->getUser();
        $form =$this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush(); }


        return $this->render('proprietaire/edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);



    }



    /**
     * @Route("/{id}/edit", name="edit_terrain", methods={"GET", "POST"})
     */
    public function editterrain(Request $request, Terrain $terrain, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('proprietaireapp', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terrain/edit.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }


    /**
     * @Route ("/{id}/confirm" ,name="confirmation")
     */

    public function confirm(Request $request,$id,ReservationRepository $reservationRepository,EntityManagerInterface $entityManager){

        $reservation=$reservationRepository->findOneBy(['id'=>$id]);
        $ter=$reservation->getTerrain()->getId();
        $reservation->setEtat(1);
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('show_terrain', ['id'=>$ter], Response::HTTP_SEE_OTHER);

    }



    /**
     * @Route("/{id}/deltereservation",name="deletereservation")
     */
    public function deletereservation(Request $request,$id,ReservationRepository $reservationRepository,EntityManagerInterface $entityManager){

        $reservation=$reservationRepository->findOneBy(['id'=>$id]);
         $ter=$reservation->getTerrain()->getId();
        $entityManager->remove($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('show_terrain', ['id'=>$ter], Response::HTTP_SEE_OTHER);

    }



    /**
     * @Route("/{id}", name="terrain_delete", methods={"POST"})
     */
    public function delete(Request $request, Terrain $terrain, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$terrain->getId(), $request->request->get('_token'))) {

            $entityManager->remove($terrain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proprietaireapp', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }

}
