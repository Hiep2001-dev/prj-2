<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOutRequest;
use App\Models\Payment;
use App\Models\Voucher;
use App\Services\CheckOutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
    /**
     * @var CheckOutService
     */
    private $checkOutService;
    public $token;
    public $province;
    public $district;
    public $ward;
    public $transport_fee;
    public $available_service;
    public $from_district;
    public $shop_id;

    /**
     * CheckOutController constructor.
     *
     * @param CheckOutService $checkOutService
     */
    public function __construct(CheckOutService $checkOutService)
    {
        $this->checkOutService = $checkOutService;
        $this->token = env('GHN_API_TOKEN');
        $this->province = env('GHN_API_PROVINCE');
        $this->district = env('GHN_API_DISTRICT');
        $this->ward = env('GHN_API_WARD');
        $this->transport_fee = env('GHN_API_TRANSPORT_FEE');
        $this->available_service = env('GHN_API_AVAILABLE_SERVICE');
        $this->from_district = env('GHN_FROM_DISTRICT');
        $this->shop_id = env('GHN_SHOPID');
    }
    /**
     * hiển thị trang thanh toán
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // nếu giỏ hàng trống thì không cho vào trang thanh toán
        if (count(\Cart::getContent()) <= 0) {
            return back();
        }
        // trả về cho phía khách hàng
        if (count($this->checkOutService->index()) == 0) {
            return redirect()->route('user.home')->with('error', 'Có lỗi xảy ra vui lòng kiểm tra lại');
        }
        return view('client.checkout', $this->checkOutService->index());
    }

    // xử lý khi người dùng bấm nút thanh toán đơn hàng
    public function store(CheckOutRequest $request)
    {
        if (Session::has('info_order')) {
            Session::forget('info_order');
        }
        Session::put('info_order', [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'address' => $request->address,
            'district' => $request->district,
            'ward' => $request->ward,
        ]);
        if ($request->code) {
            $voucher = Voucher::where('code', $request->code)->first();

            if ($voucher) {
                $voucher->increment('used');
            } else {
                return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ!');
            }
        }
        // nếu khách hàng chọn thanh toán online momo
        if ($request->payment_method == Payment::METHOD['momo']) {
            return $this->checkOutService->paymentMomo($request);
        }
        // nếu khách hàng chọn thanh toán online vnpay
        if ($request->payment_method == Payment::METHOD['vnpay']) {
            return $this->checkOutService->paymentVNPAY($request);
        }
        return $this->checkOutService->store($request);
    }

    public function callbackMomo(Request $request)
    {
        return $this->checkOutService->callbackMomo($request);
    }

    public function applyVoucher(Request $request)
    {
        if (!$request->code) {
            $total = \Cart::getTotal();
            $shippingFee = $request->shipping_fee ?? 0;
            $totalWithShipping = $total + $shippingFee;

            return response()->json([
                'success' => true,
                'discount' => 0,
                'newTotal' => $totalWithShipping,
            ]);
        }

        $voucher = Voucher::where('code', $request->code)->first();
        if (!$voucher) {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ'], 400);
        }

        if ($voucher->quantity <= 0 || $voucher->used >= $voucher->quantity) {
            return response()->json(['error' => 'Mã giảm giá không còn hiệu lực hoặc đã hết lượt sử dụng'], 400);
        }

        $total = \Cart::getTotal();
        $shippingFee = $request->shipping_fee ?? 0;
        $totalWithShipping = $total + $shippingFee;

        if ($voucher->value > 1) {
            $discount = $voucher->value;
        } else {
            $discount = $totalWithShipping * $voucher->value;
        }

        $newTotal = max(0, $totalWithShipping - $discount);

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'newTotal' => $newTotal,
        ]);
    }

}
