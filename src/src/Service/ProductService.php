<?php

namespace App\Service;

use App\Generic\Hydrator\ResultCollection;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;


class ProductService
{
    /** @var ProductRepository $repository */
    protected $repository;
    /** @var Security $security */
    protected $security;

    public function __construct(ProductRepository $productRepository, Security $security)
    {
        $this->repository = $productRepository;
        $this->security = $security;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductList()
    {
        return (new ResultCollection($this->repository->findActive()))
            ->hydrateResultsAs(\App\DTO\Product::class);
    }
}