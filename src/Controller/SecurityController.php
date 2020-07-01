<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login_token", name="security_login_token", methods={"POST"})
     */
    public function login_token()
    {
    }

    /**
     * @Route("/login", name="admin_security_login")
     */
    public function login(FormFactoryInterface $factory)
    {
        $form = $factory->createNamed('', LoginType::class);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="admin_security_logout")
     *
     * @return void
     */
    public function logout()
    {
    }
}
