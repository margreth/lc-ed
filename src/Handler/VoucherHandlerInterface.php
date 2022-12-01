<?php

namespace App\Handler;

use App\Entity\Voucher;
use App\Model\ProductList;

interface VoucherHandlerInterface
{
    public function setVoucher(Voucher $voucher): self;

    public function applyVoucher(ProductList $list): ProductList;
}