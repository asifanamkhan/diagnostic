<?php

namespace App\Http\Controllers;

use App\{ProductCategory, Purchase, PurchaseList, Product, PurchasePayment, Supplier};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all('id','name');
        return view('pages.purchase.index',compact('products'));
    }

    public function getPurchaseList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'invoice',
            3 => 'supplier_id',
            4 => 'final_amount',
            5 => 'actions'
        );

        $totalData = $totalFiltered = Purchase::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $purchases = Purchase::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $purchases =  Purchase::with('supplier')
            ->where('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhere('final_amount', 'LIKE', "%{$search}%")
            ->orWhereHas('supplier', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Purchase::with('supplier')
            ->where('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhere('final_amount', 'LIKE', "%{$search}%")
            ->orWhereHas('supplier', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($purchases)) {
            foreach ($purchases as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['date'] = $value->date;
                $nestedData['invoice'] = $value->invoice;
                if($value->status == 'ordered'){
                    $nestedData['purchase_status'] = '<span class="badge badge-warning">'.$value->status.'</span>';
                }else{
                    $nestedData['purchase_status'] = '<span class="badge badge-success">'.$value->status.'</span>';
                }
                $nestedData['supplier_id'] = $value->supplier->name;
                $nestedData['final_amount'] = $value->final_amount;
                $purchase_payment_amount = PurchasePayment::where('purchase_id',$value->id)->sum('amount');
                $nestedData['paid_amount'] = $purchase_payment_amount;
                $nestedData['due_amount'] = $value->final_amount - $purchase_payment_amount;
                $nestedData['actions'] = '<div class="">
                    <a href=" '.route('purchase-payment.createFromPurchase', $value->id).' " title="Make Payment">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-print">
                                <i class="pe-7s-piggy btn-icon-wrapper"></i>
                            </button>
                        </a>
                    <a href=" '.route('purchase.show', $value->id).' " title="View">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-primary">
                            <i class="pe-7s-note2 btn-icon-wrapper"></i>
                        </button>
                    </a> 
                    <a href=" '.route('purchase.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('purchase.destroy', $value->id).' " title="Delete">
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
        $products = Product::all('id','name');
        $products_category = ProductCategory::all('id','name');
        $suppliers = Supplier::all('id','name');
        $purchase = null;
        $lists[] = [
            'id' => 0,
            'product_id' => '',
            'quantity' => 0,
            'rate' => 0,
            'amount' => 0,
            'unit' => ''
        ];
        return view('pages.purchase.create', compact('purchase', 'lists', 'products', 'suppliers','products_category'));
    }

    public function purchaseProductUnit($product_id){
        $product = Product::findOrFail($product_id);
        $unit = '';
        if($product->unit == 1){
            $unit = 'Piece';
        }
        else{
            $unit = 'Box';
        }
        return response($unit);
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
            'lists.*.product_id.required' => 'Product is Required.',
            'lists.*.quantity.required' => 'Quantity is Required.',
            'lists.*.rate.required' => 'Rate is Required.',
            'lists.*.amount.required' => 'Amount is Required.',
            'total_amount.required' => 'Total Amount is Required.',
            'final_amount.required' => 'Final Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'supplier_id' => ['required', Rule::notIn(['','0'])],
            'lists' => 'required|array|min:1',
            'lists.*.product_id' => ['required', Rule::notIn(['','0'])],
            'lists.*.quantity' => 'required|numeric|min:0',
            'lists.*.rate' => 'required|numeric|min:0',
            'lists.*.amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $invoice = mt_rand(1000000, 9999999);
            } while (Purchase::where('invoice', $invoice)->exists());

            $purchase = Purchase::create([
                'invoice' => $invoice,
                'date' => $request->date,
                'status' => $request->status,
                'supplier_id' => $request->supplier_id,
                'total_amount' => $request->total_amount,
                'final_amount' => $request->final_amount,
                'discount' => $request->discount ?? 0,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            foreach ($request->lists as $key => $value) {
                PurchaseList::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['quantity'],
                    'rate' => $value['rate'],
                    'amount' => $value['amount'],
                    'created_by' => Auth::id()
                ]);

                $product = Product::find($value['product_id']);
                $product->update([
                    'stock' => $product->stock + $value['quantity'],
                    'purchased' => $product->purchased + $value['quantity']
                ]);
            }
        }, 5);

        return response()->json('Created Successfully');
    }

    public function purchasePayment($id){

        $purchase = Purchase::findOrFail($id);
        $purchasePayment = null;
        $supplier_id = $purchase->supplier_id;
        $purchase_payment_amount = PurchasePayment::where('purchase_id',$id)->sum('amount');
        $due = $purchase->final_amount - $purchase_payment_amount;
        return view('pages.purchase.payment',compact('due','id','purchasePayment','supplier_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $purchase_payment = PurchasePayment::where('purchase_id',$purchase->id)->get();
        return view('pages.purchase.show', compact('purchase','purchase_payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $products = Product::all('id','name');
        $suppliers = Supplier::all('id','name');
        $products_category = ProductCategory::all('id','name');
        $lists = $purchase->lists;
        return view('pages.purchase.edit', compact('purchase', 'lists', 'products', 'suppliers','products_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'supplier_id.required' => 'Supplier is Required.',
            'lists.*.product_id.required' => 'Product is Required.',
            'lists.*.quantity.required' => 'Quantity is Required.',
            'lists.*.rate.required' => 'Rate is Required.',
            'lists.*.amount.required' => 'Amount is Required.',
            'total_amount.required' => 'Total Amount is Required.',
            'final_amount.required' => 'Final Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'supplier_id' => ['required', Rule::notIn(['','0'])],
            'lists' => 'required|array|min:1',
            'lists.*.product_id' => ['required', Rule::notIn(['','0'])],
            'lists.*.quantity' => 'required|numeric|min:0',
            'lists.*.rate' => 'required|numeric|min:0',
            'lists.*.amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $purchase) {
            foreach ($purchase->lists as $key => $value) {
                $product = Product::find($value->product_id);
                $product->update([
                    'stock' => $product->stock - $value->quantity,
                    'purchased' => $product->purchased - $value->quantity
                ]);
            }

            $purchase->update([
                'date' => $request->date,
                'status' => $request->status,
                'supplier_id' => $request->supplier_id,
                'total_amount' => $request->total_amount,
                'final_amount' => $request->final_amount,
                'discount' => $request->discount ?? 0,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);

            $previous = $purchase->lists()->count();
            $current = count($request->lists);
            $purchaseList = $purchase->lists;
            if ($previous == $current) {
                foreach ($request->lists as $key => $value) {
                    $purchaseList[$key]->update([
                        'product_id' => $value['product_id'],
                        'quantity' => $value['quantity'],
                        'rate' => $value['rate'],
                        'amount' => $value['amount'],
                        'updated_by' => Auth::id()
                    ]);

                    $product = Product::find($value['product_id']);
                    $product->update([
                        'stock' => $product->stock + $value['quantity'],
                        'purchased' => $product->purchased + $value['quantity']
                    ]);
                }
            } elseif ($current > $previous) {
                foreach ($request->lists as $key => $value) {
                    if (isset($purchaseList[$key])) {
                        $purchaseList[$key]->update([
                            'product_id' => $value['product_id'],
                            'quantity' => $value['quantity'],
                            'rate' => $value['rate'],
                            'amount' => $value['amount'],
                            'updated_by' => Auth::id()
                        ]);

                        $product = Product::find($value['product_id']);
                        $product->update([
                            'stock' => $product->stock + $value['quantity'],
                            'purchased' => $product->purchased + $value['quantity']
                        ]);
                    } else {
                        PurchaseList::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $value['product_id'],
                            'quantity' => $value['quantity'],
                            'rate' => $value['rate'],
                            'amount' => $value['amount'],
                            'created_by' => Auth::id()
                        ]);

                        $product = Product::find($value['product_id']);
                        $product->update([
                            'stock' => $product->stock + $value['quantity'],
                            'purchased' => $product->purchased + $value['quantity']
                        ]);
                    }     
                }
            } else {
                foreach ($request->lists as $key => $value) {
                    if ($value['id'] != 0) {
                        $singleItem = PurchaseList::find($value['id']);
                        $singleItem->update([
                            'product_id' => $value['product_id'],
                            'quantity' => $value['quantity'],
                            'rate' => $value['rate'],
                            'amount' => $value['amount'],
                            'updated_by' => Auth::id()
                        ]);

                        $product = Product::find($value['product_id']);
                        $product->update([
                            'stock' => $product->stock + $value['quantity'],
                            'purchased' => $product->purchased + $value['quantity']
                        ]);
                    } else {
                        PurchaseList::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $value['product_id'],
                            'quantity' => $value['quantity'],
                            'rate' => $value['rate'],
                            'amount' => $value['amount'],
                            'created_by' => Auth::id()
                        ]);

                        $product = Product::find($value['product_id']);
                        $product->update([
                            'stock' => $product->stock + $value['quantity'],
                            'purchased' => $product->purchased + $value['quantity']
                        ]);
                    }     
                }

                foreach ($request->deleted as $key => $value) {
                    $singleItem = PurchaseList::find($value);
                    $singleItem->delete();
                }
            }
        }, 5);

        return response()->json('Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            foreach ($purchase->lists as $key => $value) {
                $product = Product::find($value->product_id);
                $product->update([
                    'stock' => $product->stock - $value->quantity,
                    'purchased' => $product->purchased - $value->quantity
                ]);
            }
            
            $purchase->lists()->delete();
            $purchase->delete();
        }, 5);

        return response()->json('Deleted Successfully');
    }

    public function returnPageView()
    {
        $products = Product::all('id', 'name');
        return view('report.view', compact('products'));
    }

    public function purchasesReport(Request $request)
    {
        $this->validate($request, array(
            'product' => ['nullable', Rule::notIn(['0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ));

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $product = $request->product;
        $prod = Product::find($product);

        $purchases = Purchase::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->product != '', function ($q) use ($request) {
            return $q->whereHas('lists', function($query) use ($request) {
                return $query->where('product_id', $request->product);
            });
        })
        ->orderBy('date', 'asc')
        ->get();

        $total_amount = Purchase::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->product != '', function ($q) use ($request) {
            return $q->whereHas('lists', function($query) use ($request) {
                return $query->where('product_id', $request->product);
            });
        })
        ->sum('total_amount');

        $discount = Purchase::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->product != '', function ($q) use ($request) {
            return $q->whereHas('lists', function($query) use ($request) {
                return $query->where('product_id', $request->product);
            });
        })
        ->sum('discount');

        $shipping_cost = Purchase::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->product != '', function ($q) use ($request) {
            return $q->whereHas('lists', function($query) use ($request) {
                return $query->where('product_id', $request->product);
            });
        })
        ->sum('shipping_cost');

        $final_amount = Purchase::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->product != '', function ($q) use ($request) {
            return $q->whereHas('lists', function($query) use ($request) {
                return $query->where('product_id', $request->product);
            });
        })
        ->sum('final_amount');

        return view('report.purchases.report', compact('purchases', 'total_amount', 'discount', 'shipping_cost', 'final_amount', 'start_date', 'end_date', 'product', 'prod'));
    }
}
