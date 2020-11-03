<?php

namespace App\Controller\API;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_product_")
 */
class ProductController extends ApiController
{
    /** @var ProductRepository $productRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/product", name="list", methods={"GET"})
     */
    public function list()
    {
        return $this->response($this->productRepository->findAll());
    }
}
