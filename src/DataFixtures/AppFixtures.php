<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\NutrientType;
use App\Entity\RecipeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. Tipos de Receta
        $types = [
            ['Postre', 'Para endulzar un buen menú'],
            ['Ensalada', 'Sanas y frescas'],
            ['Carne', 'Proteína pura'],
            ['Potaje', 'Platos de cuchara']
        ];
        foreach ($types as $t) {
            $type = new RecipeType();
            $type->setName($t[0])->setDescription($t[1]);
            $manager->persist($type);
        }

        // 2. Nutrientes
        $nutrients = [
            ['Proteinas', 'gr'],
            ['Grasas', 'gr'],
            ['Carbohidratos', 'gr'],
            ['Calorías', 'kcal']
        ];
        foreach ($nutrients as $n) {
            $nut = new NutrientType();
            $nut->setName($n[0])->setUnit($n[1]);
            $manager->persist($nut);
        }

        $manager->flush();
    }
}