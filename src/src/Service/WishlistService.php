<?php

namespace App\Service;

use App\Command\Wishlist\CreateWishlistCommand;
use App\Command\Wishlist\AddProductCommand;
use App\Command\Wishlist\RemoveProductCommand;
use App\CommandResult\Wishlist\CreateWishlistCommandResult;
use App\Entity\Product;
use App\Entity\Wishlist;
use App\Exception\Wishlist\NameConflictException;
use App\Exception\Wishlist\ProductNotAddedException;
use App\Exception\Wishlist\ProductNotRemovedException;
use App\Exception\Wishlist\WishlistNotCreatedException;
use App\Exception\Wishlist\WishlistNotRemovedException;
use App\Generic\Exception\ResourceNotFoundApiException;
use App\Generic\Hydrator\ResultCollection;
use App\Repository\ProductRepository;
use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Security;


class WishlistService
{
    /** @var WishlistRepository $wishlistRepository */
    protected $wishlistRepository;
    /** @var ProductRepository $productRepository */
    protected $productRepository;
    /** @var Security $security */
    protected $security;

    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository,
        Security $security
    ) {
        $this->wishlistRepository = $wishlistRepository;
        $this->productRepository = $productRepository;
        $this->security = $security;
    }

    /**
     * @return ArrayCollection
     */
    public function getList()
    {
        $rows = $this->wishlistRepository->findManyByUserId($this->security->getUser()->getId());

        return (new ResultCollection($rows))->hydrateResultsAs(\App\DTO\Wishlist::class);
    }

    /**
     * @param int $id
     * @return \App\DTO\Wishlist|null
     *
     * @throws NonUniqueResultException
     */
    public function getSingleWishlist(int $id)
    {
        $resource = $this->wishlistRepository->findOneForUser($id, $this->security->getUser()->getId());

        if (!$resource) {
            throw new ResourceNotFoundApiException(404, "Requested wishlist doesn't exists");
        }

        return (new ResultCollection([$resource]))->hydrateSingleResultAs(\App\DTO\Wishlist::class);
    }

    /**
     * @param CreateWishlistCommand $command
     *
     * @throws NameConflictException
     * @throws WishlistNotCreatedException
     */
    public function createWishlist(CreateWishlistCommand $command)
    {
        if ($this->wishlistRepository->findOneBy(['name' => $command->getName()])) {
            throw new NameConflictException("Wishlist with a given name is already exists");
        }

        $model = new Wishlist();
        $model->setName($command->getName());
        $model->setUserId($this->security->getUser()->getId());
        $model->setIsActive($command->getIsActive());
        $model->setCreatedAt(new \DateTimeImmutable());

        try {
            $id = $this->wishlistRepository->save($model);
            $result = new CreateWishlistCommandResult();
            $result->setId($id);

            $command->setResult($result);
        } catch (ORMException $exception) {
            throw new WishlistNotCreatedException("Can't create a wishlist");
        }
    }

    /**
     * @param int $id
     *
     * @throws WishlistNotRemovedException
     */
    public function removeSingleWishlist(int $id)
    {
        try {
            $this->wishlistRepository->removeOne($id);
        } catch (ORMException $exception) {
            throw new WishlistNotRemovedException("Can't remove target wishlist");
        }
    }

    /**
     * @param int $id
     * @return ArrayCollection
     *
     * @throws NonUniqueResultException
     */
    public function getRelatedProducts(int $id)
    {
        $wishlist = $this->wishlistRepository->findOneForUser($id, $this->security->getUser()->getId());

        if (!$wishlist) {
            throw new ResourceNotFoundApiException(404, "Requested wishlist doesn't exists");
        }

        return (new ResultCollection($wishlist->getProducts()->toArray()))
            ->hydrateResultsAs(\App\DTO\Product::class);
    }

    /**
     * @param AddProductCommand $command
     *
     * @throws NonUniqueResultException
     * @throws ProductNotAddedException
     */
    public function addProduct(AddProductCommand $command)
    {
        $wishlist = $this->wishlistRepository->findOneForUser(
            $command->getWishlistId(),
            $this->security->getUser()->getId()
        );
        $product = $this->productRepository->find($command->getProductId());

        if (!$wishlist || !$product) {
            throw new ResourceNotFoundApiException(404, "Requested wishlist or product doesn't exists");
        }

        try {
            $wishlist->addProduct($product);
            $this->wishlistRepository->save($wishlist);
        } catch (ORMException $exception) {
            throw new ProductNotAddedException("Can't add product to the target wishlist");
        }
    }

    /**
     * @param RemoveProductCommand $command
     *
     * @throws NonUniqueResultException
     * @throws ProductNotRemovedException
     */
    public function removeProduct(RemoveProductCommand $command)
    {
        $wishlist = $this->wishlistRepository->findOneForUser(
            $command->getWishlistId(),
            $this->security->getUser()->getId()
        );

        $product = $this->productRepository->find($command->getProductId());

        if (!$wishlist || !$product) {
            throw new ResourceNotFoundApiException(404, "Requested wishlist or product doesn't exists");
        }

        try {
            $wishlist->removeProduct($product);
            $this->wishlistRepository->save($wishlist);
        } catch (ORMException $exception) {
            throw new ProductNotRemovedException("Can't remove product from the target wishlist");
        }
    }
}