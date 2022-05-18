<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="userapp")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/profil", name="useredit")
     */
    public function edit(Request $request ,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,SluggerInterface $slugger):Response
    {
       $user=$this->getUser();
       $oldimg=$user->getImagefile();

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

            $brochureFile = $form->get('imagefile')->getData();


            if ($brochureFile) {
                $filesystem=new Filesystem() ;
               // $filesystem->remove(get('kernel')->getRootDir().'/uploads/brochures/'.$oldimg);

                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImagefile($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('user/edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);



    }
}
