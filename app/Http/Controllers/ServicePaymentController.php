<?php

namespace App\Http\Controllers;

use App\{ServicePayment, Service};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class ServicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function createFromService(Service $service)
    {
        $servicePayment = null;
        return view('pages.service_payment.create', compact('service', 'servicePayment'));
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
            'payment_type.required' => 'Payment Type is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'payment_type' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $invoice = mt_rand(100000, 999999);
            } while (ServicePayment::where('invoice', $invoice)->exists());

            ServicePayment::create([
                'invoice' => $invoice,
                'service_id' => $request->service_id,
                'payment_type' => $request->payment_type,
                'date' => $request->date,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            $service = Service::find($request->service_id);
            $service->update([
                'paid_amount' => $service->paid_amount + $request->amount
            ]);
        });

        return redirect()
        ->route('service.show', $request->service_id)
        ->with('success', 'Payment Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServicePayment  $servicePayment
     * @return \Illuminate\Http\Response
     */
    public function show(ServicePayment $servicePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServicePayment  $servicePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(ServicePayment $servicePayment)
    {
        $service = Service::find($servicePayment->service_id);
        return view('pages.service_payment.edit', compact('service', 'servicePayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServicePayment  $servicePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServicePayment $servicePayment)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'payment_type.required' => 'Payment Type is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'payment_type' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $servicePayment) {
            $service = Service::find($request->service_id);
            $service->update([
                'paid_amount' => ($service->paid_amount - $servicePayment->amount) + $request->amount
            ]);

            $servicePayment->update([
                'payment_type' => $request->payment_type,
                'date' => $request->date,
                'amount' => $request->amount,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('service.show', $request->service_id)
        ->with('success', 'Payment Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServicePayment  $servicePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServicePayment $servicePayment)
    {
        $service_id = $servicePayment->service_id;
        DB::transaction(function () use ($servicePayment) {
            $service = Service::find($servicePayment->service_id);
            $service->update([
                'paid_amount' => $service->paid_amount - $servicePayment->amount
            ]);

            $servicePayment->delete();
        });

        return redirect()
        ->route('service.show', $service_id)
        ->with('success', 'Payment Deleted Successfully');
    }
}
