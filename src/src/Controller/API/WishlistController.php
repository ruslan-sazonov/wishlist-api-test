<?php

namespace App\Controller\API;

use App\Command\Wishlist\CreateWishlistCommand;
use App\Command\Wishlist\AddProductCommand;
use App\Command\Wishlist\RemoveProductCommand;
use App\Controller\ApiController;
use App\Exception\Wishlist\NameConflictException;
use App\Exception\Wishlist\ProductNotAddedException;
use App\Exception\Wishlist\ProductNotRemovedException;
use App\Exception\Wishlist\WishlistNotCreatedException;
use App\Exception\Wishlist\WishlistNotRemovedException;
use App\Generic\Exception\BadRequestApiException;
use App\Service\WishlistService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api", name="api_whishlist_")
 */
class WishlistController extends ApiController
{
    /** @var WishlistService $service */
    protected $service;

    public function __construct(WishlistService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/wishlist", name="list", methods={"GET"})
     */
    public function listAction()
    {
        return $this->json($this->service->getList());
    }

    /**
     * @Route("/wishlist", name="add", methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        try {
            $command = (new CreateWishlistCommand())
                ->setName($data['name'] ?? null)
                ->setIsActive($data['isActive'] ?? null);
            $this->service->createWishlist($command);
            $result = $command->getResult();

            return $this->json(
                $command->getResult(),
                201,
                [
                    "Location" => $this->generateUrl(
                        'api_whishlist_view',
                        ['id' => $result['id']],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ]
            );
        } catch (NameConflictException | WishlistNotCreatedException $exception) {
            throw new BadRequestApiException(400, $exception->getMessage());
        }
    }

    /**
     * @Route("/wishlist/{id}", name="view", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function viewAction(int $id)
    {
        return $this->json(
            $this->service->getSingleWishlist($id)
        );
    }

    /**
     * @Route("/wishlist/{id}", name="remove", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function removeAction(int $id)
    {
        try {
            $this->service->removeSingleWishlist($id);
        } catch (WishlistNotRemovedException $exception) {
            throw new BadRequestApiException(400, $exception->getMessage());
        }

        return new Response(null, 204);
    }

    /**
     * @Route("/wishlist/{id}/products", name="list_products", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function listProductsAction(int $id)
    {
        return $this->json($this->service->getRelatedProducts($id));
    }

    /**
     * @Route(
     *     "/wishlist/{wishlistId}/products/{productId}",
     *     name="add_product",
     *     methods={"PUT"},
     *     requirements={"wishlistId"="\d+", "productId"="\d+"}
     * )
     */
    public function addProductAction(Request $request, int $wishlistId, int $productId)
    {
        $command = (new AddProductCommand())
            ->setWishlistId($wishlistId)
            ->setProductId($productId);

        try {
            $this->service->addProduct($command);
        } catch (ProductNotAddedException $exception) {
            throw new BadRequestApiException(400, $exception->getMessage());
        }

        return new Response(null, 204);
    }

    /**
     * @Route(
     *     "/wishlist/{wishlistId}/products/{productId}",
     *     name="remove_product",
     *     methods={"DELETE"},
     *     requirements={"wishlistId"="\d+", "productId"="\d+"}
     * )
     */
    public function removeProductAction(Request $request, int $wishlistId, int $productId)
    {
        $command = (new RemoveProductCommand())
            ->setWishlistId($wishlistId)
            ->setProductId($productId);

        try {
            $this->service->removeProduct($command);
        } catch (ProductNotRemovedException $exception) {
            throw new BadRequestApiException(400, $exception->getMessage());
        }

        return new Response(null, 204);
    }
}
