<?php

namespace App\Http\Controllers;

use App\{Doctor, DoctorPayment};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;
use PDF;
use Excel;

class DoctorPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.doctor_payment.index');
    }

    public function getDoctorPaymentList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'doctor_id',
            3 => 'invoice',
            4 => 'amount',
            5 => 'actions'
        );

        $totalData = $totalFiltered = DoctorPayment::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $doctorPayments = DoctorPayment::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $doctorPayments = DoctorPayment::with('doctor')
            ->where('amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhereHas('doctor', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = DoctorPayment::with('doctor')
            ->where('amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhereHas('doctor', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($doctorPayments)) {
            foreach ($doctorPayments as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['date'] = $value->date;
                $nestedData['doctor_id'] = $value->doctor->name;
                $nestedData['invoice'] = $value->invoice;
                $nestedData['amount'] = $value->amount;
                $nestedData['actions'] = '<div class="btn-group">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('doctor-payment.show',$value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button>
                    <a href=" '.route('doctor-payment.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('doctor-payment.destroy', $value->id).' " title="Delete">
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
        $doctors = Doctor::all('id','name');
        $doctorPayment = null;
        return view('pages.doctor_payment.create', compact('doctorPayment', 'doctors'));
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
            'doctor_id.required' => 'Doctor is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'doctor_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $invoice = mt_rand(100000, 999999);
            } while (DoctorPayment::where('invoice', $invoice)->exists());

            DoctorPayment::create([
                'date' => $request->date,
                'doctor_id' => $request->doctor_id,
                'invoice' => $invoice,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('doctor-payment.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorPayment  $doctorPayment
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorPayment $doctorPayment)
    {
        $doctorPayment->load('createdBy', 'doctor');
        if ($doctorPayment->updated_by) {
            $doctorPayment->load('updatedBy');
        }
        return response()->json($doctorPayment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorPayment  $doctorPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorPayment $doctorPayment)
    {
        $doctors = Doctor::all('id','name');
        return view('pages.doctor_payment.edit', compact('doctors', 'doctorPayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorPayment  $doctorPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DoctorPayment $doctorPayment)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'doctor_id.required' => 'Doctor is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'doctor_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $doctorPayment) {
            $doctorPayment->update([
                'date' => $request->date,
                'doctor_id' => $request->doctor_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('doctor-payment.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DoctorPayment  $doctorPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(DoctorPayment $doctorPayment)
    {
        $doctorPayment->delete();
    }
}
