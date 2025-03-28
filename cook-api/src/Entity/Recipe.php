<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\RecipeController;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/recipes/{id}/adjust-ingredients', // L'URL de l'opération
            controller: RecipeController::class,  // Le contrôleur qui gère cette logique
            normalizationContext: ['groups' => ['recipe:list']],  // Groupes pour la réponse
            denormalizationContext: ['groups' => ['recipe:write']]  // Groupes pour la demande
        ),
    ]
)]

class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:list', 'recipe:details'])]
    private ?int $id = null;

    #[Groups(['recipe:list', 'recipe:details', 'recipe:write'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['recipe:list', 'recipe:details', 'recipe:write'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Groups(['recipe:list', 'recipe:details', 'recipe:write'])]
    #[ORM\Column]
    private ?int $prepTime = null;

    #[Groups(['recipe:details', 'recipe:write'])] // UNIQUEMENT pour le détail
    #[ORM\Column(nullable: true)]
    private ?int $cookTime = null;

    #[Groups(['recipe:list', 'recipe:details', 'recipe:write'])]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Groups(['recipe:list', 'recipe:details', 'recipe:write'])]
    #[ORM\Column]
    private ?int $servings = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Step>
     */
    #[ORM\OneToMany(targetEntity: Step::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['recipe:details'])]
    private Collection $steps;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'recipe', orphanRemoval: true)]
    #[Groups(['recipe:details'])]
    private Collection $comments;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'recipe', orphanRemoval: true)]
    #[Groups(['recipe:details'])] 
    private Collection $favorites;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe')]
    #[Groups(['recipe:details'])]
    private Collection $recipeIngredients;

   
    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrepTime(): ?int
    {
        return $this->prepTime;
    }

    public function setPrepTime(int $prepTime): static
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    public function getCookTime(): ?int
    {
        return $this->cookTime;
    }

    public function setCookTime(?int $cookTime): static
    {
        $this->cookTime = $cookTime;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getServings(): ?int
    {
        return $this->servings;
    }

    public function setServings(int $servings): static
    {
        $this->servings = $servings;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Step $step): static
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setRecipe($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getRecipe() === $this) {
                $favorite->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

     /**
     * Mise à jour automatique de la date de modification avant chaque mise à jour
     */
    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Méthodes utiles

        /**
     * Ajuste les quantités des ingrédients en fonction du nombre de portions
     *
     * @param int $newServings Le nombre de portions souhaité
     */
    public function adjustIngredientsForServings(int $newServings): void
    {
        if ($this->servings <= 0) {
            throw new \InvalidArgumentException('Le nombre de portions actuel doit être supérieur à zéro pour effectuer l\'ajustement.');
        }

        $ratio = $newServings / $this->servings;

        foreach ($this->recipeIngredients as $recipeIngredient) {
            $quantity = $recipeIngredient->getQuantity();
            $recipeIngredient->setQuantity($quantity * $ratio);
        }

        // Utilisation de setServings() pour valider la nouvelle valeur
        $this->setServings($newServings);
    }
}
