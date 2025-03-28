<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Recipe;

class RecipeUpdatedAtListener
{
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // Vérifie que l'entité est bien une recette
        if (!$entity instanceof Recipe) {
            return;
        }

        // Met à jour automatiquement la date de modification
        $entity->setUpdatedAt(new \DateTimeImmutable());
    }
}
