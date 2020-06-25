<?php

namespace App\DataFixtures;

use BadMethodCallException;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use InvalidArgumentException;
use OutOfBoundsException;

abstract class AbstractFixture extends Fixture
{
    /**
     * L'instance de faker qui sera utilisable dans nos fixtures
     * @var Generator
     */
    protected Generator $faker;

    /**
     * Le manager de Doctrine utilisable dans toutes nos fixtures
     * @var ObjectManager
     */
    protected ObjectManager $manager;

    /**
     * Appelée automatiquement par le gestionnaire de fixtures, et délègue le travail 
     * à la méthode loadData()
     * @param ObjectManager $manager 
     * @throws InvalidArgumentException 
     * @return void 
     */
    final public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        // On stocke le manager dans l'instance
        $this->manager = $manager;

        // Création et stockage de Faker dans l'instance
        $this->faker = Factory::create('fr_FR');

        $this->customizeFaker();

        // Chargement des données
        $this->loadData($manager);
    }

    /**
     * Redéfinir cette fonction dans vos fixtures si vous souhaitez personnaliser Faker
     */
    protected function customizeFaker(): void
    {
        // Customize Faker with extensions
    }

    /**
     * La méthode loadData doit impérativement être définie dans vos fixtures : c'est elle
     * qui va vraiment mettre en place le chargement de données.
     * 
     * Vous pouvez y appeler la méthode createMany() ou tout simplement faire comme vous 
     * faisiez avant dans la méthode load()
     * 
     * @param ObjectManager $manager 
     * @return void 
     */
    abstract protected function loadData(ObjectManager $manager);

    /**
     * Permet d'obtenir une référence à une entité créée dans une autre fixture auparavant
     * en précisant la classe de l'entité que vous recherchez
     * @param string $className Le nom de l'entité que vous recherchez
     * 
     * @throws Exception Si référence n'existe pour la classe demandée
     * @throws OutOfBoundsException 
     * 
     * @return object L'entité qu'on a retrouvé parmis toutes les références
     */
    protected function getRandomReference(string $className)
    {
        // On chope toutes les entités référencées jusqu'ici
        $references = $this->referenceRepository->getReferences();

        // On ne s'intéresse qu'aux noms de référence (App\Entity\Project_10 par exemple)
        $names = array_keys($references);

        // On ne retient que les références qui correspondent à ce qu'on cherche
        // (dont le nom commence par App\Entity\Project_ par exemple)
        $filteredNames = array_filter($names, function ($name) use ($className) {
            return strpos($name, $className . '_') === 0;
        });

        // Si on ne trouve aucune référence correspondante
        if (count($filteredNames) === 0) {
            throw new \Exception(sprintf("Aucune référence n'a été retrouvée pour la classe %s, êtes vous sur de ce que vous voulez ?", $className));
        }

        // On prend une référence au hasard parmis les références retenues
        $randomReferenceName = $this->faker->randomElement($filteredNames);

        // On retourne l'entité référencée
        return $this->getReference($randomReferenceName);
    }

    /**
     * Permet de créer facilement un certain nombre d'entités
     * @param string $className La classe d'entité qu'on veut créer
     * @param int $count Le nombre d'entité qu'on veut créer
     * @param callable $callback La fonction de rappel qui va personnaliser chaque entité créée
     * 
     * @throws BadMethodCallException 
     * 
     * @return void 
     */
    protected function createMany(string $className, int $count, callable $callback)
    {
        // On boucle autant de fois que $count
        for ($i = 0; $i < $count; $i++) {
            // On créé un objet de la class (App\Entity\Project par exemple)
            $object = new $className();

            // On passe l'objet à la fonction de rappel et elle le personnalise
            $callback($object, $i);

            // On persiste
            $this->manager->persist($object);
            // On ajoute la référence
            $this->addReference($className . '_' . $i, $object);
        }
        // On flush !
        $this->manager->flush();
    }
}
