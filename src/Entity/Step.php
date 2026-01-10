namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity]
class Step
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'step_order')] // "order" es reservada en SQL
    #[Groups(['recipe:read'])]
    #[SerializedName("order")]
    private ?int $orderStep = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['recipe:read'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function setOrderStep(int $order): static { $this->orderStep = $order; return $this; }
    public function getOrderStep(): ?int { return $this->orderStep; }
    public function setDescription(string $desc): static { $this->description = $desc; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setRecipe(?Recipe $recipe): static { $this->recipe = $recipe; return $this; }
}