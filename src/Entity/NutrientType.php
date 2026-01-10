<?php
// src/Entity/NutrientType.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class NutrientType
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['recipe:read', 'nutrient:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read', 'nutrient:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(['recipe:read', 'nutrient:read'])]
    private ?string $unit = null;

    // Getters y Setters...
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getUnit(): ?string { return $this->unit; }
    public function setUnit(string $unit): static { $this->unit = $unit; return $this; }
}