<?php

namespace App\Repository\Eloquent;

use App\Models\Voucher;

/**
 * Class VoucherRepository
 * @package App\Repositories\Eloquent
 */
class VoucherRepository extends BaseRepository
{
    /**
     * VoucherRepository constructor.
     *
     * @param Voucher $voucher
     */
    public function __construct(Voucher $voucher)
    {
        parent::__construct($voucher);
    }
}

?>