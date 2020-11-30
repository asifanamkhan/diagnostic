<?php

namespace App\Http\Controllers;

use App\{Purchase, PurchasePayment, Supplier};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;
use Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('id','DESC')->get();
        return view('pages.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supplier = null;
        return view('pages.supplier.create', compact('supplier'));
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
            'mobile.required' => 'Mobile Number is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'mobile' => 'required'
        ), $messages);

        $result = DB::transaction(function () use ($request) {
            do {
                $code = mt_rand(100000, 999999);
            } while (Supplier::where('code', $code)->exists());

            $supplier = Supplier::create([
                'name' => $request->name,
                'code' => $code,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'company' => $request->company,
                'address' => $request->address,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            return $supplier;
        });

        if ($request->wantsJson()) {
            $suppliers = Supplier::all();
            return response()->json([$result, $suppliers]);
        }

        return redirect()
        ->route('supplier.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('createdBy');
        if ($supplier->updated_by) {
            $supplier->load('updatedBy');
        }
        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('pages.supplier.edit', compact('supplier'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'mobile.required' => 'Mobile Number is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'mobile' => 'required'
        ), $messages);

        DB::transaction(function () use ($request, $supplier) {
            $supplier->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'company' => $request->company,
                'address' => $request->address,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('supplier.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        
        return redirect()
        ->route('supplier.index')
        ->with('success', 'Deleted Successfully');
    }

    public function returnPageView()
    {
        $suppliers = Supplier::all('id','name');
        return view('report.view', compact('suppliers'));
    }

    public function supplierReport(Request $request)
    {
        $messages = array(
            'supplier.required' => 'Supplier is Required.'
        );
        $this->validate($request, array(
            'supplier' => ['required', Rule::notIn(['','0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ), $messages);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $supplier = Supplier::findOrFail($request->supplier);

        $purchases = collect(Purchase::with('lists')->where('supplier_id', $request->supplier)
        ->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->orderBy('date', 'asc')
        ->get());

        $purchase_total_amount = Purchase::where('supplier_id',$request->supplier)
        ->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('total_amount');

        $purchase_discount = Purchase::where('supplier_id',$request->supplier)
        ->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('discount');

        $purchase_payments = collect(PurchasePayment::where('supplier_id', $request->supplier)
        ->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->orderBy('date', 'asc')
        ->get());

        $purchase_payment_amount = PurchasePayment::where('supplier_id',$request->supplier)
        ->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('amount');

        $balance = 0;
        $all = $purchases->merge($purchase_payments)->sortBy(function ($all) {
            return Carbon::parse($all->date)->format('Y-m-d');
        });

        return view('report.supplier.report', compact('supplier', 'start_date', 'end_date', 'purchases', 'all', 'purchase_total_amount', 'purchase_discount', 'purchase_payment_amount', 'balance'));
    }
}
