namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Rating
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\Column(length: 45)]
    private ?string $ipAddress = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function setRate(int $rate): static { $this->rate = $rate; return $this; }
    public function getRate(): ?int { return $this->rate; }
    public function setIpAddress(string $ip): static { $this->ipAddress = $ip; return $this; }
    public function getIpAddress(): ?string { return $this->ipAddress; }
    public function setRecipe(?Recipe $recipe): static { $this->recipe = $recipe; return $this; }
}