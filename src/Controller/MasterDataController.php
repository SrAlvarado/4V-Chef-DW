// src/Controller/MasterDataController.php
namespace App\Controller;

use App\Repository\NutrientTypeRepository;
use App\Repository\RecipeTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MasterDataController extends AbstractController
{
    #[Route('/recipe-types', methods: ['GET'])]
    public function getRecipeTypes(RecipeTypeRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($repo->findAll(), 'json', ['groups' => 'type:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/nutrient-types', methods: ['GET'])]
    public function getNutrientTypes(NutrientTypeRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($repo->findAll(), 'json', ['groups' => 'nutrient:read']);
        return new JsonResponse($data, 200, [], true);
    }
}