<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class HomeController extends ApiController
{
    /**
     * @Route("/", name="list", methods={"GET"})
     * @return JsonResponse
     */
    public function index()
    {
        return $this->response([
            'description' => 'Welcome to the Wishlist Test API!',
        ]);
    }
}
