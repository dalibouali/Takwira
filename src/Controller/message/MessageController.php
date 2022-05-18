<?php

namespace App\Controller\message;

use App\Entity\Conversation;
use App\Entity\Messages;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\MessagType;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index(UserRepository $userRepository,AuthenticationUtils $authenticationUtils): Response

    {   $email=$authenticationUtils->getLastUsername();
        $user=$userRepository->findOneBy(array('mail' => $email));

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'user'=>'$user',
        ]);
    }

    /**
     * @Route("/send",name="send")
     */
    public function send(Request $request,UserRepository $userRepository,AuthenticationUtils $authenticationUtils,ConversationRepository $conversationRepository):Response{



        $message= new Messages();
        $form=$this->createForm(MessageType::class,$message);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid())
        {



            $message->setSender($this->getUser());


            $user=$this->getUser();



            $receipient=$message->getRecipient();



            /****** $array_part est un tableau contenats tout les utilisateur que j'ai contacter ************/

            $array_part=[];
            $convs=$user->getConversations();
            foreach ($convs as $conv){
                array_push($array_part,$conv->getParticipants()[0]);
                array_push($array_part,$conv->getParticipants()[1]);

            }

            if(in_array($receipient,$array_part)){
                $conversation=new Conversation();
                foreach($convs as $conv) {
                    if ($conv->getParticipants()[0] == $receipient || $conv->getParticipants()[1] == $receipient) {
                        $conversation = $conv;
                    }
                }
                $conversation->setLastMessage($message);
                $conversation->addParticipant($user);
                $conversation->addParticipant($receipient);
                $message->setConversation($conversation);
                $em = $this->getDoctrine()->getManager();
                $em2 = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em2->persist($conversation);
                $em->flush();
                $em2->flush();

                return $this->redirectToRoute("send");

            }
            else {

                $conversation=new Conversation();
                $message->setConversation($conversation);
                $conversation->setLastMessage($message);
                $conversation->addParticipant($user);
                $conversation->addParticipant($receipient);
                $em = $this->getDoctrine()->getManager();
                $em2 = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em2->persist($conversation);
                $em->flush();
                $em2->flush();

            }



        }

        return $this->render("message/send.html.twig",[
            "form"=>$form->createView()

        ]);


    }

    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('user/index.html.twig');
    }
    /**
     * @Route("/read/{id}", name="read")
     */
    public function read( Request $request,Conversation $conversation,UserRepository $userRepository,AuthenticationUtils $authenticationUtils): Response
    {

        $email = $authenticationUtils->getLastUsername();
        $user = $this->getUser();
        $lastmessage=$conversation->getLastMessage();
        if($lastmessage->getSender()!=$user)
        {$lastmessage->setIsRead(true);}
        $em3= $this->getDoctrine()->getManager();

        $em3->persist($lastmessage);

        $em3->flush();
        $participant=$conversation->getParticipants();

        if($participant[0]->getPseudo()== $user->getPseudo()){
            $recever=$participant[1];

        }else{
            $recever=$participant[0];
        }



        /******** envoi d'un nouveau message***************************************/
        $message= new Messages();
        $form=$this->createForm(MessagType::class,$message);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()) {
            $message->setSender($user);
            $message->setRecipient($recever);
            $message->setConversation($conversation);
            $conversation->setLastMessage($message);
            $em = $this->getDoctrine()->getManager();
            $em2 = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em2->persist($conversation);
            $em->flush();
            return $this->redirectToRoute("read", ["id"=>$conversation->getId()]);
        }

        /* $allconversation=$user->getConversations();
         $nonreadconversation=[];
         foreach($allconversation as $conv){
             if(($conv->getLastMessage()->getIsRead()==false )&&($conv->getLastMessage()->getSender()!=$user)){
                 array_push($nonreadconversation,$conv);
             }
         }
        */


        return $this->render('message/read.html.twig',[
            'conversation'=>$conversation,
            "form"=>$form->createView(),



        ]);
    }
    /*public function read(Messages $message): Response
    {
        $message->setIsRead(true);
        $em=$this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('message/read.html.twig',compact("message"));
    }*/
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Messages $message): Response
    {

        $em=$this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("received");
    }

    /**
     * @Route("/sent/", name="sent")
     */
    public function sent(): Response
    {

        return $this->render("message/sent.html.twig");
    }



}
