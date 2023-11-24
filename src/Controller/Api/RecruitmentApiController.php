<?php

namespace App\Controller\Api;

use App\Entity\Recruitment;
use App\Repository\RecruitmentRepository;
use App\Traits\GenerateLevel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/recruitment')]
class RecruitmentApiController extends AbstractController
{
    use GenerateLevel;

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    #[Route('/new', name: 'api_recruitment_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $recruitment = new Recruitment();

        $recruitment->setName($request->request->get('name'));
        $recruitment->setLastName($request->request->get('last_name'));
        $recruitment->setEmail($request->request->get('email'));
        $recruitment->setPhoneNumber((int)$request->request->get('phone_number'));
        $recruitment->setExpectedSalary((int)$request->request->get('expected_salary'));
        $recruitment->setPosition($request->request->get('position'));
        $recruitment->setLevel($this->generateLevel($request->request->get('expected_salary')));

        $errors = $this->validator->validate($recruitment);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($recruitment);
        $this->entityManager->flush();

        return $this->json([ 'message' => 'Recruitment created', $recruitment], Response::HTTP_CREATED);
    }

    #[Route('/show', name: 'api_recruitment_show_all', methods: ['GET'])]
    public function index(RecruitmentRepository $recruitmentRepository): Response
    {
        return new Response($this->serializer->serialize($recruitmentRepository->findAll(), 'json'));
    }

    #[Route('/show/{id}', name: 'api_recruitment_show', methods: ['GET'])]
    public function show(RecruitmentRepository $recruitmentRepository, int $id): JsonResponse
    {
        $recruitment = $recruitmentRepository->find($id);

        if (!$recruitment) {
            return $this->json([
                'message' => 'Recruitment not found'
            ], RESPONSE::HTTP_NOT_FOUND);
        }

        $jsonObject = $this->serializer->serialize($recruitment, 'json');

        return new JsonResponse($jsonObject, Response::HTTP_OK, [], true);
    }

    #[Route('/showNotDisplayed/{orderBy}/{how}', name: 'api_recruitment_show_not_displayed', methods: ['GET'])]
    public function showNotDisplayedRecruitment(
        RecruitmentRepository $recruitmentRepository,
        string $orderBy,
        string $how
    ): JsonResponse {
        $recruitment = $recruitmentRepository->searchDisplayedRecruitments(false, $orderBy, $how);

        if (!$recruitment) {
            return $this->json([
                'message' => 'Recruitment not found'
            ], RESPONSE::HTTP_NOT_FOUND);
        }

        $jsonObject = $this->serializer->serialize($recruitment, 'json');

        return new JsonResponse($jsonObject, Response::HTTP_OK, [], true);
    }

    #[Route('/showDisplayed/{orderBy}/{how}', name: 'api_recruitment_show_displayed', methods: ['GET'])]
    public function showDisplayedRecruitment(
        RecruitmentRepository $recruitmentRepository,
        string $orderBy,
        string $how
    ): JsonResponse {
        $recruitment = $recruitmentRepository->searchDisplayedRecruitments(true, $orderBy, $how);

        if (!$recruitment) {
            return $this->json([
                'message' => 'Recruitment not found'
            ], RESPONSE::HTTP_NOT_FOUND);
        }

        $jsonObject = $this->serializer->serialize($recruitment, 'json');

        return new JsonResponse($jsonObject, Response::HTTP_OK, [], true);
    }
}
