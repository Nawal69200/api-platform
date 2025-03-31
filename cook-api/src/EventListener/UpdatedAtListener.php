<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Comment;
use App\Entity\FoodInfo;
use App\Entity\Recipe;
use Doctrine\Persistence\Event\PreUpdateEventArgs;

class UpdatedAtListener
{
    /**
     * Cette méthode est exécutée avant qu'une nouvelle entité ne soit persistée dans la base de données.
     * Elle vérifie si l'entité a une propriété 'updatedAt' et met à jour cette propriété avec la date actuelle.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // Vérifie si l'entité est une instance de Comment, FoodInfo ou Recipe
        if ($entity instanceof Comment || $entity instanceof FoodInfo || $entity instanceof Recipe) {
            $this->setUpdatedAt($entity);
        }
    }

    /**
     * Cette méthode est exécutée avant qu'une entité existante soit mise à jour dans la base de données.
     * Elle vérifie si l'entité a une propriété 'updatedAt' et met à jour cette propriété avec la date actuelle.
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        // Vérifie si l'entité est une instance de Comment, FoodInfo ou Recipe
        if ($entity instanceof Comment || $entity instanceof FoodInfo || $entity instanceof Recipe) {
            $this->setUpdatedAt($entity);
        }
    }

    /**
     * Définit la valeur de updatedAt avant la persistance ou la mise à jour de l'entité.
     *
     * @param Comment|FoodInfo|Recipe $entity
     */
    private function setUpdatedAt($entity): void
    {
        // Vérifie si l'entité a bien une méthode setUpdatedAt
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable()); // Assigne la date et l'heure actuelle
        }
    }
}
