<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends AbstractFixture
{
    public function loadData(ObjectManager $manager)
    {
        // USERS
        $this->createMany(User::class, 5, function (User $user, $u) {
            $user->setEmail("user$u@gmail.com")
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setPhoto("https://i.pravatar.cc/300")
                ->setPassword("password");
        });

        // ADMIN
        $admin = new User();
        $admin->setEmail("admin@gmail.com")
            ->setFirstName("Admin")
            ->setLastName("Dusite")
            ->setPhoto("https://i.pravatar.cc/300")
            ->setPassword("password")
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

        // CATEGORIES
        $this->createMany(Category::class, 10, function (Category $category) {
            $category->setName($this->faker->words(mt_rand(1, 4), true));
        });

        // RECIPES
        $this->createMany(Recipe::class, 100, function (Recipe $recipe) {
            $recipe->setTitle($this->faker->catchPhrase)
                ->setDescription($this->faker->paragraphs(1, true))
                ->setIngredients($this->faker->paragraphs(2, true))
                ->setContent($this->faker->paragraphs(4, true))
                ->setUtensils($this->faker->words(mt_rand(2, 8), true))
                ->setPreparationTime(mt_rand(5, 20))
                ->setCookingTime(mt_rand(10, 40))
                ->setCreatedAt($this->faker->dateTimeBetween('-4months'))
                ->setIllustration("https://loremflickr.com/500/300/food?random=" . mt_rand(1, 30))
                ->addCategory($this->getRandomReference(Category::class))
                ->setOwner($this->getRandomReference(User::class));
        });
    }
}
