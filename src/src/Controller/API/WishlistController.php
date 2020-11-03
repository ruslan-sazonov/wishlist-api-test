<?php

namespace App\Controller\API;

use App\Entity\Wishlist;
use App\Repository\ProductRepository;
use App\Repository\WishlistRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api", name="api_whishlist_")
 */
class WishlistController extends ApiController
{
    /** @var WishlistRepository $wishlistRepository */
    private $wishlistRepository;
    /** @var ProductRepository $productRepository */
    private $productRepository;
    /** @var Security $security */
    private $security;

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
     * @Route("/wishlist", name="list", methods={"GET"})
     */
    public function list()
    {
        $userWishlists = $this->wishlistRepository->findBy([
            'userId' => $this->security->getUser()->getId()
        ]);

        return $this->response($userWishlists);
    }

    /**
     * @Route("/wishlist", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $wishlist = new Wishlist();
        $wishlist->setUserId($this->security->getUser()->getId());
        $wishlist->setCreatedAt(new \DateTimeImmutable());
        $wishlist->setName($data['name'] ?? '');
        $wishlist->setIsActive($data['isActive'] ?? true);
        $wishlist->setUser($this->security->getUser());

        try {
            $this->wishlistRepository->save($wishlist);
        } catch (UniqueConstraintViolationException $e) {
            throw new UnprocessableEntityHttpException('Wishlist with requested name already exists for this user');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException("Can't process request right now");
        }

        return $this->response(
            ['id' => $wishlist->getId()],
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl(
                    'api_whishlist_view',
                    ['id' => $wishlist->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
    }

    /**
     * @Route("/wishlist/{id}", name="view", methods={"GET"}, requirements={"id"="\d+"})
     * @param int $id
     * @return JsonResponse
     */
    public function view(int $id)
    {
        $wishlist = $this->wishlistRepository->findOneBy([
            'id' => $id,
            'userId' => $this->security->getUser()->getId(),
        ]);

        if (!$wishlist) {
            throw new NotFoundHttpException('Requested resource not found');
        }

        return $this->response($wishlist);
    }

    /**
     * @Route("/wishlist/{id}", name="remove", methods={"DELETE"}, requirements={"id"="\d+"})
     * @param int $id
     */
    public function remove(int $id)
    {
        try {
            $this->wishlistRepository->removeOne($id);
        } catch (ORMException | OptimisticLockException $e) {
            throw new BadRequestHttpException("Can't process request right now");
        }

        $this->response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/wishlist/{id}/products", name="list_products", methods={"GET"}, requirements={"id"="\d+"})
     * @param int $id
     * @return JsonResponse
     */
    public function listProducts(int $id)
    {
        $wishlist = $this->wishlistRepository->findOneBy([
            'id' => $id,
            'userId' => $this->security->getUser()->getId(),
        ]);

        if (!$wishlist) {
            throw new NotFoundHttpException('Requested resource not found');
        }

        return $this->response($wishlist->getProducts());
    }

    /**
     * @Route(
     *     "/wishlist/{wishlistId}/products/{productId}",
     *     name="add_product",
     *     methods={"PUT"},
     *     requirements={"wishlistId"="\d+", "productId"="\d+"}
     * )
     * @param Request $request
     * @param int $wishlistId
     * @param int $productId
     * @return JsonResponse
     */
    public function addProduct(Request $request, int $wishlistId, int $productId)
    {
        $wishlist = $this->wishlistRepository->findOneBy([
            'id' => $wishlistId,
            'userId' => $this->security->getUser()->getId()
        ]);

        if (!$wishlist) {
            throw new NotFoundHttpException('Requested wishlist not found');
        }

        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new NotFoundHttpException('Requested product not found');
        }

        $wishlist->addProduct($product);

        try {
            $this->wishlistRepository->save($wishlist);
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException("Can't process request right now");
        }

        return $this->response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     "/wishlist/{wishlistId}/products/{productId}",
     *     name="remove_product",
     *     methods={"DELETE"},
     *     requirements={"wishlistId"="\d+", "productId"="\d+"}
     * )
     * @param Request $request
     * @param int $wishlistId
     * @param int $productId
     * @return Response
     */
    public function removeProduct(Request $request, int $wishlistId, int $productId)
    {
        $wishlist = $this->wishlistRepository->findOneBy([
            'id' => $wishlistId,
            'userId' => $this->security->getUser()->getId()
        ]);

        if (!$wishlist) {
            throw new NotFoundHttpException('Requested wishlist not found');
        }

        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new NotFoundHttpException('Requested product not found');
        }

        $wishlist->removeProduct($product);

        try {
            $this->wishlistRepository->save($wishlist);
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException("Can't process request right now");
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
