<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * @Route("/recipes", name="recipe_index")
     */
    public function index(RecipeRepository $recipeRepository)
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findBy([], ['id' => 'DESC'])
        ]);
    }

    /**
     * @Route("/recipes/{id}", name="recipe_show")
     */
    public function show(Recipe $recipe)
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    /**
     * @Route("/recipes/create", name="recipe_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\Recipe */
            $recipe = $form->getData();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recipes/edit/{id}", name="recipe_edit")
     */
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recipes/delete/{id}", name="recipe_delete")
     */
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();

        return $this->redirectToRoute('recipe_index');
    }
}
