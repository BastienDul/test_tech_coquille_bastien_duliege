<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('product/list.html.twig');
    }

    #[Route('/api/product/listproducts', name: 'api_product_list', defaults: ['page' => '1'], methods: ['GET'])]
    #[Route('/api/product/listproducts/{page}', name: 'api_product_list_paginated', methods: ['GET'])]
    public function apiList(int $page, ProductRepository $products): JsonResponse
    {
        $paginator = $products->getAllPaginated($page);
        return $this->json($paginator);
    }

    #[Route('/api/product/delete/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function delete(int $id, ProductRepository $productRepository): JsonResponse
    {
        // Logique de suppression du produit
        $productRepository->remove($id);

        // Retourner une réponse JSON
        return $this->json([
            'message' => 'Produit supprimé avec succès.'
        ]);
    }

    #[Route('/api/product/update', name: 'update_product', methods: ['PUT'])]
    public function update(Product $entity, ProductRepository $productRepository): JsonResponse
    {
        // Logique de modification du produit
        $productRepository->save($entity);

        // Retourner une réponse JSON
        return $this->json([
            'message' => 'Produit modifié avec succès.'
        ]);
    }

    #[Route('/api/product/add', name: 'add', methods: ['POST'])]
    public function add(Request $request, ProductRepository $productRepository): JsonResponse
    {
        // Récupérer les données du corps de la requête POST
        $DataFromRequest = json_decode($request->getContent(), true);

        // Vérifier si toutes les données nécessaires sont présentes
        if (!isset($DataFromRequest['name']) || !isset($DataFromRequest['description']) || !isset($DataFromRequest['price'])) {
            // Répondre avec un message d'erreur si des données sont manquantes
            return new JsonResponse(['error' => true, 'message' => 'Toutes les données requises ne sont pas fournies.'], Response::HTTP_BAD_REQUEST);
        }

        // Ajouter un nouveau produit en utilisant la méthode add du repository
        $productRepository->add($DataFromRequest['name'], $DataFromRequest['description'], $DataFromRequest['price']);

        // Retourner une réponse JSON
        return $this->json([
            'message' => 'Produit ajouté avec succès.'
        ]);
    }
}
