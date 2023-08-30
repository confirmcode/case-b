<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(UserRepository $user_repository): Response
    {
        $users = $user_repository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
        /**
     * @Route("/user/new", name="app_user_new")
     */
    function add_user(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $user_repository ): Response
    {
        $user = new User();
        $form_user = $this->createForm(UserType::class,$user);
        $form_user->handleRequest($request);
        if ($form_user->isSubmitted() && $form_user->isValid()) {
            $exists = $user_repository->findOneBy(['email' => $user->getEmail()]);
            if ( !$exists ) {
                $user->setCarId(0);
                $hashedPassword = $passwordHasher->hashPassword( $user, $user->getPassword() );
                $user->setPassword($hashedPassword);
                $user_repository->add($user, true);
                $this->addFlash(
                    'notice',
                    'Пользователь создан!'
                );
                return $this->redirectToRoute('app_user');
            } else {
                $message = "Ошибка: Пользоавель с таким email уже существует.";
            }
        }
        return $this->renderForm('user/form.html.twig', [
            'message' => $message,
            'form_user' => $form_user,
        ]);
    }

    /**
     * @Route("/signin", name="app_login")
     */
    function signin(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form_login = $this->createForm(LoginType::class);
        return $this->renderForm('user/signin.html.twig', [
            'form_login' => $form_login,
            'error' => $error,
            'lastUsername'=> $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    function logout(Request $request ): Response
    {
        throw new \Exception('Activate logout in security.yaml');
    }
}
