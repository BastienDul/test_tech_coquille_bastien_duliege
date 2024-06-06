<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/download/{filename}', name: 'home_download', methods: ['GET'])]
    public function download(string $filename): BinaryFileResponse
    {
        return $this->file(new File($filename));
    }

    #[Route('/api/helloword/{name}', name: 'api_helloword')]
    public function apiHelloword(string $name): Response
    {
        return new JsonResponse('Hello ' . $name . ' !');
    }
}
