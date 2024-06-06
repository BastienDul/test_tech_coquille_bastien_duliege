<?php
namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'customer_list', defaults: ['page' => '1'], methods: ['GET'])]
    #[Route('/customers/{page}', name: 'customer_list_paginated', methods: ['GET'])]
    public function list(int $page, CustomerRepository $customers): Response
    {
        $customers = $customers->getAllCustomers($page);
        return $this->render('customer/list.html.twig', [
            'paginator' => $customers
        ]);
    }
}
