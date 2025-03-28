<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class RecipeController extends AbstractController
{
    #[Route('/recettes/{id}/ajuster-ingredients', name: 'ajuster_ingredients', methods: ['POST'])]
    public function adjustIngredients(int $id, RecipeRepository $recipeRepository, Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer la recette en fonction de l'ID
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return $this->json(['erreur' => 'Recette introuvable.'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Récupérer le nombre de nouvelles portions depuis la requête (POST)
        $newServings = $request->request->get('newServings');

        // Validation du nombre de portions
        if (!$newServings || $newServings <= 0) {
            return $this->json(['erreur' => 'Le nombre de portions doit être un entier positif.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Ajuste les ingrédients en fonction du nombre de portions
        try {
            $recipe->adjustIngredientsForServings($newServings);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['erreur' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Sauvegarder les modifications dans la base de données
        $em->flush();

        // Réponse de succès
        return $this->json(['message' => 'Ingrédients ajustés avec succès.'], JsonResponse::HTTP_OK);
    }
}
