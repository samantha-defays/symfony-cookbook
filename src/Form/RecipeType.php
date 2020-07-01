<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
                'attr' => [
                    'placeholder' => "Titre de la recette"
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'attr' => [
                    'placeholder' => "Description"
                ]
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => "Liste des ingrédients",
                'attr' => [
                    'placeholder' => "Liste des ingrédients"
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => "Etapes de la recette",
                'attr' => [
                    'placeholder' => "Etapes de la recette"
                ]
            ])
            ->add('preparationTime', IntegerType::class, [
                'label' => "Temps de préparation (en minutes)",
                'attr' => [
                    'placeholder' => "Temps de préparation (en min)"
                ]
            ])
            ->add('cookingTime', IntegerType::class, [
                'label' => "Temps de cuisson (en minutes)",
                'attr' => [
                    'placeholder' => "Temps de cuisson (en min)"
                ]
            ])
            ->add('utensils', TextareaType::class, [
                'label' => "Ustensiles nécessaires",
                'attr' => [
                    'placeholder' => "Ustensiles"
                ]
            ])
            ->add('illustration', UrlType::class, [
                'label' => "URL de la photo",
                'attr' => [
                    'placeholder' => "URL de la photo d'illustration"
                ]
            ])
            ->add('owner', EntityType::class, [
                'label' => "Utilisateur",
                'class' => User::class,
                'choice_label' => function (User $u) {
                    return sprintf('%s %s', $u->getFirstName(), $u->getLastName());
                }
            ])
            ->add('categories', EntityType::class, [
                'label' => "Catégories",
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
