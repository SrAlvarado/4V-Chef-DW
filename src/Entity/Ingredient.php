namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Ingredient
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?float $quantity = null;

    #[ORM\Column(length: 50)]
    #[Groups(['recipe:read'])]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    // Setters y Getters estándar (ignorar getters por brevedad aquí, pero debes incluirlos)
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setQuantity(float $quantity): static { $this->quantity = $quantity; return $this; }
    public function getQuantity(): ?float { return $this->quantity; }
    public function setUnit(string $unit): static { $this->unit = $unit; return $this; }
    public function getUnit(): ?string { return $this->unit; }
    public function setRecipe(?Recipe $recipe): static { $this->recipe = $recipe; return $this; }
}