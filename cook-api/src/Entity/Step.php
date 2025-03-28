<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StepRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StepRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['step:read']],
    denormalizationContext: ['groups' => ['step:write']]
)]
#[ORM\Table(name: 'step', uniqueConstraints: [
    // Définition d'une contrainte d'unicité sur les colonnes recipe_id et step_order
    // Cela garantit qu'il n' y aura pas deux étapes avec le même step_order pour une recette donnée
    new ORM\UniqueConstraint(columns: ['recipe_id', 'step_order'])
])]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:details', 'step:read', 'step:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['recipe:details', 'step:read', 'step:write'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['recipe:details', 'step:read', 'step:write'])]
    private ?int $stepOrder = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['step:read'])]
    private ?Recipe $recipe = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getStepOrder(): ?int
    {
        return $this->stepOrder;
    }

    public function setStepOrder(int $stepOrder): static
    {
        $this->stepOrder = $stepOrder;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
