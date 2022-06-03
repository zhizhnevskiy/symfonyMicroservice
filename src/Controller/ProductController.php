<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{

    public function __construct(
        private ProductRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {

    }

    #[Route('products/{id}/lowest-price', name: 'lowest-price', methods: "POST")]
    public function lowestPrice(
        Request                   $request,
        int                       $id,
        DTOSerializer             $serializer,
        PromotionsFilterInterface $promotionFilter
    ): Response
    {
        if ($request->headers->has('force_fail')) {
            return new JsonResponse([
                'error' => "Promotions Engine failure message"
            ], $request->headers->get('force_fail'));
        }

        // 1. Deserialize json data into a EnquiryDTO
        /* @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        // find product
        $product = $this->repository->find($id); // add error handling for not found product
        $lowestPriceEnquiry->setProduct($product);

        // find all promotion for this product
        $promotions = $this->entityManager->getRepository(Promotion::class)
            ->findValidForProduct(
                $product,
                date_create_immutable($lowestPriceEnquiry->getRequestDate())
            ); // null

        // 2. Pass the Enquiry into a promotion  filter
        // the appropriate promotion will be applied
        // 3. Return the modified Enquiry
        $modifiedEnquiry = $promotionFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, 200, ['Content-Type'=>'application/json']);

//        return new JsonResponse($lowestPriceEnquiry,200);

//        return new JsonResponse([
//            "quantity"          => 5,
//            "request_location"  => "UK",
//            "voucher_code"      => "OU812",
//            "request_date"      => "2022-04-04",
//            "product_id"        => $id,
//            "price"             => 100,
//            "discounted_price"  => 50,
//            "promotion_id"      => 3,
//            "promotion_name"    => 'Black Friday half price sale'
//        ], 200);
    }

    #[Route('products/{id}/promotions', name: 'promotions', methods: "GET")]
    public function promotions()
    {

    }
}