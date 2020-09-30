<?php

namespace App\Controller\API;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class HomeController extends ApiController
{
    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function index(Request $request)
    {
        return $this->json([
            'description' => 'Welcome to the Wishlist Test API!',
        ]);
    }
}