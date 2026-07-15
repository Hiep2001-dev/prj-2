<?php

namespace App\Services;

use App\Helpers\TextSystemConst;
use App\Http\Requests\Admin\UpdateVoucherRequest;
use App\Http\Requests\Admin\StoreVoucherRequest;
use App\Models\Voucher;
use App\Repository\Eloquent\VoucherRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherService
{
    /**
     * @var VoucherRepository
     */
    private $voucherRepository;

    /**
     * VoucherService constructor.
     *
     * @param VoucherRepository $voucherRepository
     */
    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get list Voucher
        $list = $this->voucherRepository->all();
        $tableCrud = [
            'headers' => [
                ['text' => 'Mã Voucher', 'key' => 'code'],
                ['text' => 'Tên Voucher', 'key' => 'name'],
                ['text' => 'Số lượng', 'key' => 'quantity'],
                ['text' => 'Giá trị', 'key' => 'value'],
                ['text' => 'Đã sử dụng', 'key' => 'used'],
            ],
            'actions' => [
                'text'          => "Thao Tác",
                'create'        => true,
                'createExcel'   => false,
                'edit'          => true,
                'deleteAll'     => false,
                'delete'        => true,
                'viewDetail'    => false,
            ],
            'routes' => [
                'create' => 'admin.vouchers_create',
                'delete' => 'admin.vouchers_delete',
                'edit' => 'admin.vouchers_edit',
            ],
            'list' => $list,
        ];

        return [
            'title' => TextLayoutTitle("voucher"),
            'tableCrud' => $tableCrud,
        ];
    }

    /**
     * Show the form for creating a new voucher.
     *
     * @return array
     */
    public function create()
    {
        try {
            // Fields form
            $fields = [
                ['attribute' => 'name', 'label' => 'Tên Voucher', 'type' => 'text'],
                ['attribute' => 'quantity', 'label' => 'Số lượng', 'type' => 'number'],
                ['attribute' => 'value', 'label' => 'Giá trị', 'type' => 'text'],
                ['attribute' => 'code', 'label' => 'Mã Voucher', 'type' => 'text'],
            ];

            // Rules form
            $rules = [
                'name' => ['required' => true, 'minlength' => 1, 'maxlength' => 100],
                'quantity' => ['required' => true, 'min' => 1],
                'value' => [
                    'required' => true,
                    'regex:/^\d+(\.\d{1,2})?%?$/'
                ],
                'code' => ['required' => true, 'unique' => 'vouchers,code'],
                'used' => ['nullable', 'integer', 'min:0'],
            ];

            // Messages error rules
            $messages = [
                'name' => [
                    'required' => __('message.required', ['attribute' => 'Tên Voucher']),
                    'minlength' => __('message.min', ['min' => 1, 'attribute' => 'Tên Voucher']),
                    'maxlength' => __('message.max', ['max' => 100, 'attribute' => 'Tên Voucher']),
                ],
                'quantity' => [
                    'required' => __('message.required', ['attribute' => 'Số lượng']),
                    'min' => __('message.min', ['min' => 1, 'attribute' => 'Số lượng']),
                ],
                'value' => [
                    'required' => __('message.required', ['attribute' => 'Giá trị']),
                    'regex' => __('message.regex.value', ['attribute' => 'Giá trị']),
                ],
                'code' => [
                    'required' => __('message.required', ['attribute' => 'Code Voucher']),
                    'unique' => __('message.unique', ['attribute' => 'Code Voucher']),
                ],
            ];

            return [
                'title' => TextLayoutTitle("create_voucher"),
                'fields' => $fields,
                'rules' => $rules,
                'messages' => $messages,
            ];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Store the voucher in the database.
     *
     * @param StoreVoucherRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreVoucherRequest $request)
    {
        try {
            $data = $request->validated();

            if (strpos($data['value'], '%') !== false) {
                $data['value'] = (float) rtrim($data['value'], '%');
            }

            $data['used'] = $data['used'] ?? 0;
            // dd($data);


            $this->voucherRepository->create($data);
            return redirect()->route('admin.vouchers_index')->with('success', TextSystemConst::CREATE_SUCCESS);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route('admin.vouchers_index')->with('error', TextSystemConst::CREATE_FAILED);
        }
    }


    /**
     * Show the form for editing the voucher.
     *
     * @param Voucher $voucher
     * @return array
     */
    public function edit(Voucher $voucher)
    {
        try {
            $fields = [
                ['attribute' => 'name', 'label' => 'Tên Voucher', 'type' => 'text', 'value' => $voucher->name],
                ['attribute' => 'quantity', 'label' => 'Số lượng', 'type' => 'number', 'value' => $voucher->quantity],
                ['attribute' => 'value', 'label' => 'Giá trị', 'type' => 'text', 'value' => $voucher->value],
                ['attribute' => 'code', 'label' => 'Code Voucher', 'type' => 'text', 'value' => $voucher->code],
            ];

            // Rules form
            $rules = [
                'name' => ['required' => true, 'minlength' => 1, 'maxlength' => 100],
                'quantity' => ['required' => true, 'min' => 1],
                'value' => [
                    'required' => true,
                    'regex:/^\d+(\.\d{1,2})?%?$/'
                ],
                'code' => ['required' => true, 'unique' => 'vouchers,code,' . $voucher->id],
            ];

            // Messages error rules
            $messages = [
                'name' => [
                    'required' => __('message.required', ['attribute' => 'Tên Voucher']),
                    'minlength' => __('message.min', ['min' => 1, 'attribute' => 'Tên Voucher']),
                    'maxlength' => __('message.max', ['max' => 100, 'attribute' => 'Tên Voucher']),
                ],
                'quantity' => [
                    'required' => __('message.required', ['attribute' => 'Số lượng']),
                    'min' => __('message.min', ['min' => 1, 'attribute' => 'Số lượng']),
                ],
                'value' => [
                    'required' => __('message.required', ['attribute' => 'Giá trị']),
                    'regex' => __('message.regex.value', ['attribute' => 'Giá trị']),
                ],
                'code' => [
                    'required' => __('message.required', ['attribute' => 'Code Voucher']),
                    'unique' => __('message.unique', ['attribute' => 'Code Voucher']),
                ],
                'start_date' => [
                    'required' => __('message.required', ['attribute' => 'Ngày bắt đầu']),
                    'date' => __('message.date', ['attribute' => 'Ngày bắt đầu']),
                ],
                'end_date' => [
                    'required' => __('message.required', ['attribute' => 'Ngày kết thúc']),
                    'date' => __('message.date', ['attribute' => 'Ngày kết thúc']),
                ],
            ];

            return [
                'title' => TextLayoutTitle("edit_voucher"),
                'fields' => $fields,
                'rules' => $rules,
                'messages' => $messages,
                'voucher' => $voucher,
            ];
        } catch (Exception $e) {
            return [];
        }
    }


    /**
     * Update the voucher in the database.
     *
     * @param UpdateVoucherRequest $request
     * @param Voucher $voucher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        try {
            $data = $request->validated();
            $this->voucherRepository->update($voucher, $data);
            return redirect()->route('admin.vouchers_index')->with('success', TextSystemConst::UPDATE_SUCCESS);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->route('admin.vouchers_index')->with('error', TextSystemConst::UPDATE_FAILED);
        }
    }

    /**
     * Delete the voucher in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            if ($this->voucherRepository->delete($this->voucherRepository->find($request->id))) {
                return response()->json(['status' => 'success', 'message' => TextSystemConst::DELETE_SUCCESS], 200);
            }

            return response()->json(['status' => 'failed', 'message' => TextSystemConst::DELETE_FAILED], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => TextSystemConst::SYSTEM_ERROR], 200);
        }
    }
}
