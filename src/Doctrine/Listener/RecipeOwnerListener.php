<?php

namespace App\Doctrine\Listener;

use App\Entity\Recipe;
use Symfony\Component\Security\Core\Security;

class RecipeOwnerListener
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Recipe $recipe)
    {

        // Assigner l'utilisateur à la recette
        if (!$recipe->getOwner()) {
            $recipe->setOwner($this->security->getUser());
        }
    }
}
