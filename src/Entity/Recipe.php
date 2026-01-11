<?php
// src/Entity/Recipe.php
namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    #[SerializedName("number-diner")]
    private ?int $numberDiner = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe:read'])]
    private ?RecipeType $type = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Ingredient::class, cascade: ['persist', 'remove'])]
    #[Groups(['recipe:read'])]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Step::class, cascade: ['persist', 'remove'])]
    #[Groups(['recipe:read'])]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeNutrient::class, cascade: ['persist', 'remove'])]
    #[Groups(['recipe:read'])]
    private Collection $nutrients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Rating::class, cascade: ['persist', 'remove'])]
    private Collection $ratings;

    #[ORM\Column(type: 'boolean')]
    private bool $deleted = false;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->nutrients = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    #[Groups(['recipe:read'])]
    public function getRating(): array
    {
        $totalVotes = count($this->ratings);
        $sum = 0;
        foreach ($this->ratings as $r) {
            $sum += $r->getRate();
        }
        
        return [
            'number-votes' => $totalVotes,
            'rating-avg' => $totalVotes > 0 ? round($sum / $totalVotes, 1) : 0
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getNumberDiner(): ?int { return $this->numberDiner; }
    public function setNumberDiner(int $numberDiner): static { $this->numberDiner = $numberDiner; return $this; }
    public function getType(): ?RecipeType { return $this->type; }
    public function setType(?RecipeType $type): static { $this->type = $type; return $this; }
    public function isDeleted(): bool { return $this->deleted; }
    public function setDeleted(bool $deleted): static { $this->deleted = $deleted; return $this; }

    // Métodos Add/Get para colecciones
    public function addIngredient(Ingredient $ingredient): static {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setRecipe($this);
        }
        return $this;
    }
    public function getIngredients(): Collection { return $this->ingredients; }

    public function addStep(Step $step): static {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipe($this);
        }
        return $this;
    }
    public function getSteps(): Collection { return $this->steps; }

    public function addNutrient(RecipeNutrient $nutrient): static {
        if (!$this->nutrients->contains($nutrient)) {
            $this->nutrients->add($nutrient);
            $nutrient->setRecipe($this);
        }
        return $this;
    }
    public function getNutrients(): Collection { return $this->nutrients; }
    
    public function addRating(Rating $rating): static {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setRecipe($this);
        }
        return $this;
    }
    public function getRatings(): Collection { return $this->ratings; }
}