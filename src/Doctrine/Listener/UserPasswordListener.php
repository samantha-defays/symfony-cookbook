<?php

namespace App\Doctrine\Listener;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordListener
{
    protected UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function prePersist(User $user)
    {
        // Encoder le mot de passe
        $password = $user->getPassword();
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
    }
}
