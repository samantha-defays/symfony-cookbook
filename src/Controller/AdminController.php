<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index(RecipeRepository $recipeRepository, UserRepository $userRepository, CategoryRepository $categoryRepository)
    {
        return $this->render('admin/index.html.twig', [
            'recipes' => $recipeRepository->findBy([], ['id' => 'DESC']),
            'categories' => $categoryRepository->findBy([], ['name' => 'DESC']),
            'users' => $userRepository->findBy([], ['id' => 'ASC'])
        ]);
    }
}
