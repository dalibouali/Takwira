<?php

namespace App\Controller;

use App\data\SearchData;
use App\Entity\Terrain;
use App\Form\ReservationFormType;
use App\Form\SearchForm;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
    /**
     * @Route("/res/{id}", name="reservation_terrain")
     */


    public function reservationTerrain($id,ReservationRepository $reservation,Request $request , EntityManagerInterface $entityManager): Response
    {    $reserv_client= new Reservation();

         $form=$this->createForm(ReservationFormType::class,$reserv_client);
        $form->handleRequest($request);
        $ter=$entityManager->getRepository(Terrain::class)->findOneBy(array('id'=>$id));
        if ($form->isSubmitted() && $form->isValid()) {

           $reserv_client->setDate($form->get('date')->getData());
           $reserv_client->setMaker($this->getUser());
           $reserv_client->setTerrain($ter);
           $reserv_client->setEtat(0);
           $reserv_client->setId( $entityManager->getRepository(Reservation::class)->findOneBy([], ['id' => 'desc'])->getId()+1);

            $entityManager->persist($reserv_client);
            $entityManager->flush();




        }
        $complexe=$ter->getComplex();

        $matches=$reservation->findby(array('terrain' => $id));
        $res=array();

        foreach ($matches as $matche){
            if($matche->getEtat()==1){
                $col="#6CB62F";
                $ms="Reservation effectué par ";
            }else{
                $col="#C92a57";
                $ms="Demande en cour par ";

            }

            $res[]=array(
                'id'=>$matche->getMaker()->getId().'_'.$matche->getTerrain()->getId().'_'.$matche->getDate()->format('d-m-Y-h-i'),
                'start'=>$matche->getDate()->format('Y-m-d H:i'),
                'end'=>$matche->getDate()->modify("+1 hour")->format('Y-m-d H:i'),
                'backgroundColor'=>$col,
                'title'=>$ms.$matche->getMaker()->getPseudo()
            );

        }

        $data = json_encode($res);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController','data'=>$data,'form'=>$form->createView(),'complexe'=>$complexe,'terrain_a'=>$ter
        ]);
    }
}
