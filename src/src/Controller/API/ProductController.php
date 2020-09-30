<?php

namespace App\Controller\API;

use App\Controller\ApiController;
use App\Service\ProductService;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_product_")
 */
class ProductController extends ApiController
{
    /** @var ProductService $service */
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/product", name="list", methods={"GET"})
     */
    public function listAction()
    {
        return $this->json($this->service->getProductList());
    }
}
