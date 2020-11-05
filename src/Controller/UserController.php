<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * @Route ("/signin", name="signin")
     */
    public function profile(UserRepository $userRepository,
                            Request $request,
                            EntityManagerInterface $entityManager,
                            UserPasswordEncoderInterface $encoder) {

        $user = new User;

        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {


            $encoded = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($encoded);
            $user->setRoles(["ROLE_USER"]);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous vous êtes bien enregistré !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/user.html.twig', [
            'formUser'=>$formUser->createView()
        ]);
    }


}