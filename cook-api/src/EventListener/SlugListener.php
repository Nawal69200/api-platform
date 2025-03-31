<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Recipe;
use App\Entity\Event;
use App\Entity\FoodInfo;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Listener pour la génération automatique du slug.
 *
 * Ce listener s'applique aux entités Recipe, Event et FoodInfo.
 * Il génère un slug à partir du titre (ou du nom) de l'entité lors de la création (prePersist)
 * ou de la mise à jour (preUpdate) de l'entité.
 *
 * @param Recipe|Event|FoodInfo $entity L'entité concernée
 */
class SlugListener
{
    /**
     * Constructeur injectant le SluggerInterface via la promotion de propriété.
     *
     * @param SluggerInterface $slugger Service de génération de slug
     */
    public function __construct(private readonly SluggerInterface $slugger) {}

    /**
     * Méthode appelée avant la persistance d'une entité.
     *
     * @param LifecycleEventArgs $args Contexte de l'événement
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        /* Vérifie si l'entité est une instance de Recipe, Event ou FoodInfo */
        if ($entity instanceof Recipe || $entity instanceof Event || $entity instanceof FoodInfo) {
            $this->generateSlug($entity);
        }
    }

    /**
     * Méthode appelée avant la mise à jour d'une entité.
     *
     * @param LifecycleEventArgs $args Contexte de l'événement
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        /* Vérifie si l'entité est une instance de Recipe, Event ou FoodInfo */
        if ($entity instanceof Recipe || $entity instanceof Event || $entity instanceof FoodInfo) {
            $this->generateSlug($entity);
        }
    }

    /**
     * Génère un slug à partir du titre ou nom de l'entité.
     *
     * @param Recipe|Event|FoodInfo $entity L'entité concernée
     */
    private function generateSlug(object $entity): void
    {
        // Vérifie d'abord si l'entité a une méthode getTitle() puis getName() comme alternative
        if (method_exists($entity, 'getTitle')) {
            $source = $entity->getTitle();
        } elseif (method_exists($entity, 'getName')) {
            $source = $entity->getName();
        } else {
            // Si aucune des méthodes n'existe, on ne peut pas générer de slug
            return;
        }

        /* Génère le slug à l'aide du service Slugger injecté */
        $slug = $this->slugger->slug($source)->lower();

        /* Vérifie que l'entité dispose d'une méthode setSlug() pour l'assigner */
        if (method_exists($entity, 'setSlug')) {
            $entity->setSlug($slug);
        }
    }
}
