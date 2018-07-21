<?php

namespace App\Controller;

use App\Entity\User;
use App\Events;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    public function inscription(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,['validation_groups' => array('User', 'registration')]);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $encoded = $encoder->encodePassword($user, $form["password"]->getData());

            $user->setPassword($encoded);
            
            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            
            $subject = "Confirmation d'inscription";
            $body = "Bienvenue {{ user.username }},<br>
            vous venez de vous inscrire sur le site de WeshMaGueule.toto et je vous en remercie.<br>
            Si vous avez souscrit à la newsletter, vous serez désormais informés personnellement des meilleures offres Tartonpion ainsi que des bonnes affaires du moment.<br>
            Merci de votre confiance et à bientôt sur WeshMaGueule.toto »";
            $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setTo($user->getEmail())
                ->setFrom('vn.emploi@laposte.net')
                ->setBody($body, 'text/html')
            ;
            $this->get('mailer')->send($message);

            //On déclenche l'event
            // $event = new GenericEvent($user);
            // $eventDispatcher->dispatch(Events::USER_REGISTERED, $event);

            return $this->redirectToRoute('connexion');
        }

        return $this->render('user/inscription.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

   
    
}
