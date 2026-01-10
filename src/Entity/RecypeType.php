// src/Entity/RecipeType.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class RecipeType
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['recipe:read', 'type:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read', 'type:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['type:read'])]
    private ?string $description = null;

    // Getters y Setters...
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
}