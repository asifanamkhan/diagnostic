<?php

namespace App\Http\Controllers;

use App\{Product, StockAdjustment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.stock_adjustment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all('id','name');
        $stockAdjustment = null;
        return view('pages.stock_adjustment.create', compact('stockAdjustment', 'products'));
    }

    public function getAdjustmentList(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'product_id',
            3 => 'adjustment_class',
            4 => 'adjustment_type',
            5 => 'adjusted_quantity',
            6 => 'actions'
        );

        $totalData = $totalFiltered = StockAdjustment::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $stock_adjustment = StockAdjustment::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $stock_adjustment = StockAdjustment::with('product')
            ->where('date', 'LIKE', "%{$search}%")
            ->orWhere('adjusted_quantity', 'LIKE', "%{$search}%")
            ->orWhereHas('product', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = StockAdjustment::with('product')
            ->where('date', 'LIKE', "%{$search}%")
            ->orWhere('adjusted_quantity', 'LIKE', "%{$search}%")
            ->orWhereHas('product', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($stock_adjustment)) {
            foreach ($stock_adjustment as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['date'] = date("d M, Y", strtotime($value->date));
                $nestedData['product_id'] = $value->product->name;
                if ($value->adjustment_class == 1) {
                    $nestedData['adjustment_class'] = 'Uses';
                } elseif ($value->adjustment_class == 2) {
                    $nestedData['adjustment_class'] = 'Wastage';
                } elseif ($value->adjustment_class == 3) {
                    $nestedData['adjustment_class'] = 'Adjustment';
                } else {
                    $nestedData['adjustment_class'] = 'Others';
                }

                if ($value->adjustment_type == 1) {
                    $nestedData['adjustment_type'] = 'Addition';
                } elseif ($value->adjustment_type == 2) {
                    $nestedData['adjustment_type'] = 'Subtraction';
                } else {
                    $nestedData['adjustment_type'] = '--';
                }
                $nestedData['adjusted_quantity'] = $value->adjusted_quantity;
                $nestedData['actions'] = '<div class="">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('stock-adjustment.show',$value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'product_id.required' => 'Product is Required.',
            'adjustment_class.required' => 'Adjustment Class is Required.',
            'adjustment_type.required' => 'Adjustment Type is Required.',
            'adjusted_quantity.required' => 'Adjusted Quantity is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'product_id' => ['required', Rule::notIn(['','0'])],
            'adjustment_class' => ['required', Rule::notIn(['','0'])],
            'adjustment_type' => Rule::requiredIf($request->adjustment_class == 3 || $request->adjustment_class == 4, Rule::notIn(['','0'])),
            'adjusted_quantity' => 'required'
        ), $messages);

        $stock = 0;
        $product = Product::where('id', $request->product_id)->first();

        if ($request->adjustment_class == 1 || $request->adjustment_class == 2 ) {
            $stock = $product->stock - $request->adjusted_quantity; //uses and wastage
        } else {
            if ($request->adjustment_type == 1) {
                $stock = $product->stock + $request->adjusted_quantity;//addition
            }
            else {
                $stock = $product->stock - $request->adjusted_quantity; //subtraction
            }
        }

        if ($stock >= 0) {
            DB::transaction(function () use ($request, $product, $stock) {
                StockAdjustment::create([
                    'date' => $request->date,
                    'product_id' => $request->product_id,
                    'adjustment_class' => $request->adjustment_class,
                    'adjustment_type' => $request->adjustment_type ?? 2,
                    'prev_quantity' => $product->stock,
                    'adjusted_quantity' => $request->adjusted_quantity,
                    'after_quantity' => $stock,
                    'description' => $request->description,
                    'created_by' => Auth::id()
                ]);

                $product->update([
                    'stock' => $stock
                ]);
            }, 5 );

            return redirect()
            ->route('stock-adjustment.index')
            ->with('success', 'Created Successfully');
        } else {
            return back()->with('warning', "Wrong Adjustment Quantity. Stock Can't be Less than Zero");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return \Illuminate\Http\Response
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->load('createdBy', 'product');
        if ($stockAdjustment->updated_by) {
            $stockAdjustment->load('updatedBy');
        }
        return response()->json($stockAdjustment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return \Illuminate\Http\Response
     */
    public function edit(StockAdjustment $stockAdjustment)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockAdjustment $stockAdjustment)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        //
    }

    public function returnPageView()
    {
        $products = Product::all('id', 'name');
        return view('report.view', compact('products'));
    }

    public function adjustmentsReport(Request $request)
    {
        $this->validate($request, array(
            'supplier' => ['nullable', Rule::notIn(['0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ));

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $product = $request->product;

        $stock_adjustments = StockAdjustment::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->product != '', function ($q) use ($request) {
            return $q->where('product_id', $request->product);
        })
        ->orderBy('date', 'desc')
        ->get();

        return view('report.stock_adjustment.report', compact('stock_adjustments', 'start_date', 'end_date', 'product'));
    }
}
