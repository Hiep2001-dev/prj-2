<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Http\Requests\Admin\UpdateVoucherRequest;
use App\Http\Requests\Admin\StoreVoucherRequest;

use App\Services\VoucherService;

class VoucherController extends Controller
{

    private $voucherService;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }
    public function index()
    {
        return view('admin.voucher.index', $this->voucherService->index());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (count($this->voucherService->create()) > 0) {
            return view('admin.voucher.create', $this->voucherService->create());
        }

        return redirect()->route('admin.vouchers_index');
    }

    public function store(StoreVoucherRequest $request)
    {

        return $this->voucherService->store($request);
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.voucher.edit', $this->voucherService->edit($voucher));
    }

    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        return $this->voucherService->update($request, $voucher);
    }

    public function delete(Request $request)
    {
        return $this->voucherService->delete($request);
    }
}
