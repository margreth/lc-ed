<?php

namespace App\Controller;

use App\Entity\Voucher;
use App\Form\VoucherType;
use App\Handler\VoucherHandler;
use App\Model\ProductManager;
use App\Repository\VoucherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoucherController extends AbstractController
{
    private $repo;

    public function __construct(VoucherRepository $repo)
    {
        $this->repo = $repo;
    }

    #[Route('/generate', name: 'app_voucher_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $params = array_merge($request->request->all(), json_decode($request->getContent(), true));
        $discount = (int)$params['discount'] ?? 0;

        if ($discount) {
            $voucher = new Voucher();
            $voucher->setDicount($discount);

            $code = 'ABC' . time() . rand();
            $voucher->setCode($code);
            $this->repo->save($voucher, true);
            return $this->json([
                'code' => $voucher->getCode()
            ]);

        } else {
            return $this->json([
                'invalid discount' => $discount
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/apply', name: 'app_voucher_apply', methods: ['POST'])]
    public function apply(Request $request, VoucherHandler $handler, ProductManager $productManager): JsonResponse
    {
        $params = array_merge($request->request->all(), json_decode($request->getContent(), true) ?? []);


        if ($code = ($params['code'] ?? 0)) {

            $voucher = $this->repo->finOneByCode($code);
            $itemsRequest = $params['items'];

            $items = $productManager->createProductList($itemsRequest);

            if ($voucher) {
                $handler->setVoucher($voucher)->applyVoucher($items);
                $data = $items->serializeList();
                return $this->json([
                    'items' => $data,
                    'code' => $code
                ]);
            } else {
                return $this->json([
                    sprintf('invalid voucher code "%s"', $code)
                ]);
            }

        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/VoucherController.php',
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return [];
        // TODO: Implement getSubscribedServices() method.
    }


}
