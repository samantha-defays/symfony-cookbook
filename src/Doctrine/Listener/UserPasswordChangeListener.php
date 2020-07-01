<?php

namespace App\Doctrine\Listener;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordChangeListener
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function preUpdate(User $user)
    {
        // Encoder le mot de passe lors de la modification
        $password = $user->getPassword();
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
    }
}
