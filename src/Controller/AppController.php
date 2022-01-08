<?php

namespace App\Controller;

use App\Form\UploadExcelType;
use App\Normalizer\QuestionNormalizer;
use App\Service\QuestionExcelReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="index",
     *     methods={"GET", "POST"}
     * )
     */
    public function index(
        Request $request,
        QuestionExcelReader $questionExcelReader,
        QuestionNormalizer $questionNormalizer
    ): Response {
        $form = $this->createForm(UploadExcelType::class);
        $form->handleRequest($request);

        $uploadedExcelFile = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedExcelFile = $form->get('file')->getData();
        } else if (
            !empty($request->files)
                &&
            1 === count($request->files)
        ) {
            $uploadedExcelFile = $request->files->get(0);
        }

        if (null !== $uploadedExcelFile) {
            $question = $questionExcelReader
                ->read($uploadedExcelFile)
            ;

            return new JsonResponse(
                $questionNormalizer->normalize($question)
            );
        }

        return $this->render('app/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
