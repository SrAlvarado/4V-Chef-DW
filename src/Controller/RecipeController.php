<?php
// src/Controller/RecipeController.php
namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\Step;
use App\Entity\RecipeNutrient;
use App\Entity\Rating;
use App\Repository\RecipeRepository;
use App\Repository\RecipeTypeRepository;
use App\Repository\NutrientTypeRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/recipes')]
class RecipeController extends AbstractController
{
    // --- LISTAR RECETAS (GET) ---
    #[Route('', methods: ['GET'])]
    public function list(Request $request, RecipeRepository $recipeRepo, SerializerInterface $serializer): JsonResponse
    {
        $typeId = $request->query->get('type');
        
        // Borrado lógico: filtrar deleted = false
        $criteria = ['deleted' => false];
        if ($typeId) {
            $criteria['type'] = $typeId;
        }

        $recipes = $recipeRepo->findBy($criteria);
        
        $json = $serializer->serialize($recipes, 'json', ['groups' => 'recipe:read']);
        return new JsonResponse($json, 200, [], true);
    }

    // --- CREAR RECETA (POST) ---
    #[Route('', methods: ['POST'])]
    public function create(
        Request $request, 
        EntityManagerInterface $em, 
        RecipeTypeRepository $typeRepo, 
        NutrientTypeRepository $nutRepo,
        SerializerInterface $serializer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // VALIDACIONES (Error schema: {code, description})
        
        // 1. Campos obligatorios
        if (empty($data['title']) || empty($data['number-diner'])) {
            return $this->error(21, 'Title and number-diner are mandatory');
        }

        // 2. Tipo de receta existe
        if (empty($data['type-id'])) return $this->error(22, 'Type ID is required');
        $type = $typeRepo->find($data['type-id']);
        if (!$type) return $this->error(22, 'Recipe Type not found');

        // 3. Al menos 1 ingrediente
        if (empty($data['ingredients']) || !is_array($data['ingredients'])) {
            return $this->error(23, 'At least one ingredient is required');
        }

        // 4. Al menos 1 paso
        if (empty($data['steps']) || !is_array($data['steps'])) {
            return $this->error(24, 'At least one step is required');
        }

        // CREACIÓN
        $recipe = new Recipe();
        $recipe->setTitle($data['title']);
        $recipe->setNumberDiner($data['number-diner']);
        $recipe->setType($type);
        $recipe->setDeleted(false);

        foreach ($data['ingredients'] as $ingData) {
            $ing = new Ingredient();
            $ing->setName($ingData['name'])->setQuantity($ingData['quantity'])->setUnit($ingData['unit']);
            $recipe->addIngredient($ing);
            $em->persist($ing);
        }

        foreach ($data['steps'] as $stepData) {
            $step = new Step();
            $step->setOrderStep($stepData['order'])->setDescription($stepData['description']);
            $recipe->addStep($step);
            $em->persist($step);
        }

        if (!empty($data['nutrients'])) {
            foreach ($data['nutrients'] as $nutData) {
                $nutType = $nutRepo->find($nutData['type-id']);
                if (!$nutType) return $this->error(25, 'Nutrient Type not found: ' . $nutData['type-id']);
                
                $rn = new RecipeNutrient();
                $rn->setType($nutType)->setQuantity($nutData['quantity']);
                $recipe->addNutrient($rn);
                $em->persist($rn);
            }
        }

        $em->persist($recipe);
        $em->flush();

        $json = $serializer->serialize($recipe, 'json', ['groups' => 'recipe:read']);
        return new JsonResponse($json, 200, [], true);
    }

    // --- BORRAR RECETA (DELETE) ---
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, RecipeRepository $repo, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $recipe = $repo->find($id);

        if (!$recipe || $recipe->isDeleted()) {
            return $this->error(44, 'Recipe not found');
        }

        // Borrado lógico
        $recipe->setDeleted(true);
        $em->flush();

        $json = $serializer->serialize($recipe, 'json', ['groups' => 'recipe:read']);
        return new JsonResponse($json, 200, [], true);
    }

    // --- VOTAR RECETA (POST) ---
    #[Route('/{id}/rating/{rate}', methods: ['POST'])]
    public function rate(
        int $id, 
        int $rate, 
        Request $request, 
        RecipeRepository $repo, 
        RatingRepository $ratingRepo,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse {
        $recipe = $repo->find($id);

        if (!$recipe || $recipe->isDeleted()) return $this->error(44, 'Recipe not found');

        if ($rate < 0 || $rate > 5) return $this->error(51, 'Rate must be between 0 and 5');

        $ip = $request->getClientIp();
        $existing = $ratingRepo->findOneBy(['recipe' => $recipe, 'ipAddress' => $ip]);
        
        if ($existing) return $this->error(52, 'You have already voted for this recipe');

        $rating = new Rating();
        $rating->setRate($rate)->setIpAddress($ip ?? '127.0.0.1');
        $recipe->addRating($rating);
        
        $em->persist($rating);
        $em->flush();

        $json = $serializer->serialize($recipe, 'json', ['groups' => 'recipe:read']);
        return new JsonResponse($json, 200, [], true);
    }

    private function error(int $code, string $desc): JsonResponse
    {
        return new JsonResponse(['code' => $code, 'description' => $desc], 400);
    }
}