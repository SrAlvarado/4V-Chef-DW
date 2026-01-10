namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class RecipeNutrient
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe:read'])]
    private ?NutrientType $type = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?float $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'nutrients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function getId(): ?int { return $this->id; }
    public function getType(): ?NutrientType { return $this->type; }
    public function setType(?NutrientType $type): static { $this->type = $type; return $this; }
    public function getQuantity(): ?float { return $this->quantity; }
    public function setQuantity(float $quantity): static { $this->quantity = $quantity; return $this; }
    public function setRecipe(?Recipe $recipe): static { $this->recipe = $recipe; return $this; }
}