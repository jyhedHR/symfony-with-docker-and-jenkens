<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/signUp",name="signUp")
     */
    public function renderLoginPage()
    {
      return $this->render("registro.html.twig");
    }

   

    

    /**
     * @Route("registro/completed",name="registro")
     */
    public function CreateNewUser(Request $request , EntityManagerInterface $doctrine , UserPasswordEncoderInterface $passwordEncoder)
    {
      $user = new User();
      $user->setUsername($request->get("username"));
      $user->setPassword($passwordEncoder->encodePassword($user , $request->get("password")));

      $doctrine->persist($user);
      $doctrine->flush();

      return $this->render("home/index.html.twig");
    }

    /**
     * @Route("/logged" , name="logged")
     */
    public function accessLogged()
    {
        return $this->render("authorizated.html.twig");
    }

    /**
     * @Route("/profile/auth",name="auth")
     */
    public function renderAuth()
    {
        return $this->render("authorizated.html.twig");
    }
}
