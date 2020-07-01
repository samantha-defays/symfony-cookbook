<?php

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToItem(\Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        if ($resourceClass === Recipe::class) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere($rootAlias . '.owner = :user')
                ->setParameter('user', $this->security->getUser());
        }
        // elseif ($resourceClass === User::class) {
        //     $rootAlias = $queryBuilder->getRootAliases()[0];
        //     $queryBuilder->andWhere($rootAlias . '.email = :email')
        //         ->setParameter('email', $this->security->getUser());
        // }
    }

    public function applyToCollection(\Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        if ($resourceClass === Recipe::class) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere($rootAlias . '.owner = :user')
                ->setParameter('user', $this->security->getUser());
        } elseif ($resourceClass === User::class) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere($rootAlias . '.email = :email')
                ->setParameter('email', $this->security->getUser()->getUsername());
        }
    }
}
