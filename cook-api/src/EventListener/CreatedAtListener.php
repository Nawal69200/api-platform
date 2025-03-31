<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Comment;
use App\Entity\FoodInfo;
use App\Entity\Recipe;

class CreatedAtListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // Vérifie si l'entité est une instance de Comment, FoodInfo ou Recipe
        if ($entity instanceof Comment || $entity instanceof FoodInfo || $entity instanceof Recipe) {
            $this->setCreatedAt($entity);
        }
    }

    /**
     * Définit la valeur de createdAt avant la persistance de l'entité.
     *
     * @param Comment|FoodInfo|Recipe $entity
     */
    private function setCreatedAt(object $entity): void
    {
        // Vérifie si l'entité a bien une méthode setCreatedAt
        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new \DateTimeImmutable()); 
        }
    }
}
