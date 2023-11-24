<?php

namespace App\Controller;

use App\Entity\Recruitment;
use App\Form\RecruitmentType;
use App\Repository\RecruitmentRepository;
use App\Service\FileUploader;
use App\Traits\GenerateLevel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/recruitment')]
class RecruitmentController extends AbstractController
{
    use GenerateLevel;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private FileUploader $fileUploader;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        FileUploader $fileUploader
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->fileUploader = $fileUploader;
    }

    #[Route('/', name: 'app_recruitment_index', methods: ['GET'])]
    public function index(RecruitmentRepository $recruitmentRepository): Response
    {
        return $this->render('recruitment/index.html.twig', [
            'recruitments' => $recruitmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recruitment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $recruitment = new Recruitment();
        $form = $this->createForm(RecruitmentType::class, $recruitment);
        $form->handleRequest($request);

        $errors = $this->validator->validate($recruitment);

        if ($form->isSubmitted() && $form->isValid() && count($errors) === 0 ) {
            $expectedSalary = $request->request->all()['recruitment']['expectedSalary'];
            $recruitment->setLevel($this->generateLevel($expectedSalary));
            $recruitment->setDisplayed(false);

            $cvFile = $form->get('brochure')->getData();
            if ($cvFile) {
                $cvFileName = $this->fileUploader->upload($cvFile);
                $recruitment->setCv($cvFileName);
            }

            $this->entityManager->persist($recruitment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Application submitted correctly. Thanks !');

            return $this->redirectToRoute('app_home_page', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recruitment/new.html.twig', [
            'recruitment' => $recruitment,
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_recruitment_show', methods: ['GET'])]
    public function show(Recruitment $recruitment): Response
    {
        $recruitment->setDisplayed(true);
        $this->entityManager->persist($recruitment);
        $this->entityManager->flush();

        return $this->render('recruitment/show.html.twig', [
            'recruitment' => $recruitment,
        ]);
    }
}
