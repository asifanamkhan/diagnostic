<?php

namespace App\Http\Controllers;

use App\{PurchasePayment, Supplier};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;
use PDF;
use Excel;

class PurchasePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all('id','name');
        return view('pages.purchase_payment.index',compact('suppliers'));
    }

    public function getPurchasePaymentList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'supplier_id',
            3 => 'invoice',
            4 => 'amount',
            5 => 'actions'
        );

        $totalData = $totalFiltered = PurchasePayment::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $purchasePayments = PurchasePayment::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $purchasePayments = PurchasePayment::with('supplier')
            ->where('amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhereHas('supplier', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = PurchasePayment::with('supplier')
            ->where('amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhereHas('supplier', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($purchasePayments)) {
            foreach ($purchasePayments as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['date'] = date("d M, Y", strtotime($value->date));
                $nestedData['supplier_id'] = $value->supplier->name;
                $nestedData['invoice'] = $value->invoice;
                $nestedData['amount'] = $value->amount;
                $nestedData['actions'] = '<div class="btn-group">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('purchase-payment.show',$value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button>           
                    <a href=" '.route('purchase-payment.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('purchase-payment.destroy', $value->id).' " title="Delete">
                        <i class="fa fa-trash btn-icon-wrapper"></i>
                    </button>
                </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all('id','name');
        $purchasePayment = null;
        return view('pages.purchase_payment.create', compact('purchasePayment', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'supplier_id.required' => 'Supplier is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'supplier_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $invoice = mt_rand(100000, 999999);
            } while (PurchasePayment::where('invoice', $invoice)->exists());

            PurchasePayment::create([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'invoice' => $invoice,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('purchase-payment.index')
        ->with('success', 'Created Successfully');
    }

    public function purchasePaymentStore(Request $request){
        if($request->amount > $request->due){
            return redirect()
               ->back()
                ->with('warning', 'Payment amount should less then due amount');
        }

        $messages = array(
            'date.required' => 'Date is Required.',
            'supplier_id.required' => 'Supplier is Required.',
            'amount.required' => 'Amount is Required.'
        );

        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'supplier_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $invoice = mt_rand(100000, 999999);
            } while (PurchasePayment::where('invoice', $invoice)->exists());

            PurchasePayment::create([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'purchase_id' => $request->purchase_id,
                'invoice' => $invoice,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
            ->route('purchase.show', $request->purchase_id)
            ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchasePayment  $purchasePayment
     * @return \Illuminate\Http\Response
     */
    public function show(PurchasePayment $purchasePayment)
    {
        $purchasePayment->load('createdBy', 'supplier');
        if ($purchasePayment->updated_by) {
            $purchasePayment->load('updatedBy');
        }
        return response()->json($purchasePayment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchasePayment  $purchasePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchasePayment $purchasePayment)
    {
        $suppliers = Supplier::all('id','name');
        return view('pages.purchase_payment.edit', compact('suppliers', 'purchasePayment'));
    }

    public function purchasePaymentEdit($id){
        $purchasePayment = PurchasePayment::findOrFail($id);
        $supplier_id = $purchasePayment->supplier_id;
        $due = null;
        return view('pages.purchase.payment_edit', compact('supplier_id', 'purchasePayment','id','due'));
    }

    public function purchasePaymentUpdate(Request $request){
//        if($request->amount > $request->due){
//            return redirect()
//                ->back()
//                ->with('warning', 'Payment amount should less then due amount');
//        }

        $messages = array(
            'date.required' => 'Date is Required.',
            'supplier_id.required' => 'Supplier is Required.',
            'amount.required' => 'Amount is Required.'
        );

        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'supplier_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        $purchasePayment = PurchasePayment::findOrFail($request->purchase_id);
        DB::transaction(function () use ($request, $purchasePayment) {
            $purchasePayment->update([
                'date' => $request->date,
                'amount' => $request->amount,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });


        return redirect()
            ->route('purchase.show', $purchasePayment->purchase_id)
            ->with('success', 'Created Successfully');
    }

    public function purchasePaymentDestroy($id){
        $purchasePayment = PurchasePayment::findOrFail($id);
        $purchasePayment->delete();

        return redirect()
            ->route('purchase.show', $purchasePayment->purchase_id)
            ->with('success', 'Created Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchasePayment  $purchasePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchasePayment $purchasePayment)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'supplier_id.required' => 'Supplier is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'supplier_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $purchasePayment) {
            $purchasePayment->update([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('purchase-payment.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchasePayment  $purchasePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchasePayment $purchasePayment)
    {
        $purchasePayment->delete();
    }

    public function returnPageView()
    {
        $suppliers = Supplier::all('id', 'name');
        return view('report.view', compact('suppliers'));
    }

    public function purchasePaymentsReport(Request $request)
    {
        $this->validate($request, array(
            'supplier' => ['nullable', Rule::notIn(['0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ));

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $supplier = $request->supplier;

        $purchase_payments = PurchasePayment::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->supplier != '', function ($q) use ($request) {
            return $q->where('supplier_id', $request->supplier);
        })
        ->orderBy('date', 'asc')
        ->get();

        $total = PurchasePayment::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->supplier != '', function ($q) use ($request) {
            return $q->where('supplier_id', $request->supplier);
        })
        ->sum('amount');

        return view('report.purchase_payment.report', compact('purchase_payments', 'total', 'start_date', 'end_date', 'supplier'));
    }
}
