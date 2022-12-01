<?php

namespace App\Handler;

use App\Entity\Voucher;
use App\Model\Product;
use App\Model\ProductList;
use function PHPUnit\Framework\throwException;

class VoucherHandler implements VoucherHandlerInterface
{
    private $voucher;

    private $discount;

    private $sumWithDiscount;

    public function setVoucher(Voucher $voucher): self
    {
        $this->voucher = $voucher;
        return $this;
    }

    public function applyVoucher(ProductList $list): ProductList
    {
        if ($list && $this->voucher) {
            $this->discount = $this->voucher->getDicount();
            $this->sum = $list->calculateSumm();
            if ($this->sum <= $this->discount) {
                return $list;
            }
            $this->sumWithDiscount = ($this->sum - $this->discount);
            $this->percentBase = $this->sumWithDiscount / $this->sum;

            $list->applyDiscount($this->percentBase);


            $this->checkSumWithDiscount($list);
        }

        return $list;
    }


    private function checkSumWithDiscount(ProductList $list)
    {
        $sumWithDiscount = $list->calculateDiscountSumm();
        $diff = $this->sumWithDiscount - $sumWithDiscount;

        if ($diff > $list->count()) {
            throw new \Exception(sprintf('discount %s diff with %s  > %s', $diff, $sumWithDiscount, $this->sumWithDiscount));
        } elseif ($diff) {
            $list->resolveDiscountDiff($diff);
        }
    }

}