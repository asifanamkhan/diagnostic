<?php

namespace App\Http\Controllers;

use App\{Service, ServiceList, ServicePayment, Doctor, Patient, Test};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tests = Test::all();
        $total_amount = Service::sum('total_amount');
        $final_amount = Service::sum('paid_amount');
        $amount_after_discount = Service::sum('amount_after_discount');
        $due_amount = $amount_after_discount - $final_amount;
        return view('pages.service.index',compact('tests'
        ,'total_amount','amount_after_discount','due_amount','final_amount'));
    }

    public function getServiceList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'invoice',
            3 => 'patient_id',
            4 => 'doctor_id',
            5 => 'payment_status',
            6 => 'status',
            7 => 'total_amount',
            8 => 'discount',
            9 => 'amount_after_discount',
            10 => 'paid_amount',
            11 => 'due_amount',
            12 => 'actions'
        );

        $totalData = $totalFiltered = Service::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {

            $services = Service::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->latest()
                ->get();

        } else {
            $search = $request->input('search.value');

            $services =  Service::with('patient', 'doctor')
            ->where('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhere('paid_amount', 'LIKE', "%{$search}%")
            ->orWhereHas('patient', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('doctor', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Service::with('patient', 'doctor')
            ->where('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhere('paid_amount', 'LIKE', "%{$search}%")
            ->orWhereHas('patient', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('doctor', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($services)) {
            foreach ($services as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['date'] = $value->date;
                $nestedData['invoice'] = $value->invoice;
                $nestedData['patient_id'] = $value->patient->name;
                if ($value->doctor_id) {
                    $nestedData['doctor_id'] = $value->doctor->name;
                } else {
                    $nestedData['doctor_id'] = '--';
                }
                $payment_status = ($value->total_amount - (($value->total_amount/100)*$value->discount)) - $value->paid_amount;
                if($value->status != 'pending'){
                    $nestedData['payment_status'] = '<span class="badge badge-success">Paid</span>';
                }
                else{
                    if($payment_status > 0){
                        $nestedData['payment_status'] = '<span class="badge badge-danger">Due</span>';
                    }else{
                        $nestedData['payment_status'] = '<span class="badge badge-success">Paid</span>';
                    }
                }
                if($value->status == 'pending'){
                    $nestedData['service_status'] = '<span class="badge badge-warning">'.$value->status.'</span>';
                }else{
                    $nestedData['service_status'] = '<span class="badge badge-success">'.$value->status.'</span>';
                }

                $nestedData['total_amount'] = $value->total_amount;
                $nestedData['discount'] = $value->discount.'%';
                $nestedData['amount_after_discount'] = number_format(($value->total_amount - (($value->total_amount/100)*$value->discount)),2);
                $nestedData['paid_amount'] = $value->paid_amount;
                $nestedData['due_amount'] =  number_format($payment_status, 2) ;

                if (($value->total_amount - ((($value->total_amount/100)*$value->discount) + $value->paid_amount)) >= 0) {
                    $nestedData['actions'] = '<div class="btn-group">
                        <a href=" '.route('service-payment.createFromService', $value->id).' " title="Make Payment">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-print">
                                <i class="pe-7s-piggy btn-icon-wrapper"></i>
                            </button>
                        </a>
                        <a href=" '.route('service.show', $value->id).' " title="View">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-primary">
                                <i class="pe-7s-note2 btn-icon-wrapper"></i>
                            </button>
                        </a> 
                        <a href=" '.route('service.edit', $value->id).' " title="Edit">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                            </button>
                        </a>
                        <a href=" '.route('service.invoice.print', $value->id).' " title="Invoice">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-info">
                                <i class="pe-7s-print btn-icon-wrapper"></i>
                            </button>
                        </a>
                        <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('service.destroy', $value->id).' " title="Delete">
                            <i class="fa fa-trash btn-icon-wrapper"></i>
                        </button>
                    </div>';
                } else {
                    $nestedData['actions'] = '<div class="">
                        <a href=" '.route('service.show', $value->id).' " title="View">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-primary">
                                <i class="pe-7s-note2 btn-icon-wrapper"></i>
                            </button>
                        </a> 
                        <a href=" '.route('service.edit', $value->id).' " title="Edit">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                            </button>
                        </a>
                        <a href=" '.route('service.show', $value->id).' " title="Invoice">
                            <button class="mr-2 btn-icon btn-icon-only btn btn-info">
                                <i class="pe-7s-print btn-icon-wrapper"></i>
                            </button>
                        </a>
                        <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('service.destroy', $value->id).' " title="Delete">
                            <i class="fa fa-trash btn-icon-wrapper"></i>
                        </button>
                    </div>';
                }
                
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
        $tests = Test::with('category')->orderBy('test_category_id')->get();
        $doctors = Doctor::all('id', 'name', 'specialty');
        $patients = Patient::all();
        return view('pages.service.create', compact('tests', 'doctors', 'patients'));
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
            'delivery_date.required' => 'Delivery Date is Required.',
            'patient_id.required' => 'Patient is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'delivery_date' => 'required|date|date_format:Y-m-d H:i:s',
            'patient_id' => ['required', Rule::notIn(['','0'])],
            'lists' => 'required|array|min:1',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $invoice = mt_rand(1000000, 9999999);
            } while (Service::where('invoice', $invoice)->exists());

            $service = Service::create([
                'invoice' => $invoice,
                'date' => $request->date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id == 0 ? null : $request->doctor_id,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount ?? 0,
                'discount' => $request->discount ?? 0,
                'amount_after_discount' => ($request->total_amount-(($request->total_amount/100)*$request->discount)) ?? 0,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            foreach ($request->lists as $key => $value) {
                ServiceList::create([
                    'service_id' => $service->id,
                    'test_id' => $value['test_id'],
                    'cost' => $value['cost'],
                    'description' => $value['list_description'],
                    'created_by' => Auth::id()
                ]);
            }

            if ($request->paid_amount > 0) {
                ServicePayment::create([
                    'invoice' => $invoice,
                    'service_id' => $service->id,
                    'payment_type' => 1,
                    'date' => $request->date,
                    'amount' => $request->paid_amount,
                    'description' => $request->description,
                    'created_by' => Auth::id()
                ]);
            }
        }, 5);

        return response()->json('Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return view('pages.service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $tests = Test::with('category')->orderBy('test_category_id')->get();
        $doctors = Doctor::all('id', 'name', 'specialty');
        $patients = Patient::all();
        $paid_amount = $service->payments()->sum('amount');
        $lists = $service->lists()->with('test')->get();
        return view('pages.service.edit', compact('service', 'tests', 'doctors', 'patients', 'paid_amount', 'lists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'delivery_date.required' => 'Delivery Date is Required.',
            'patient_id.required' => 'Patient is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'delivery_date' => 'required|date|date_format:Y-m-d H:i:s',
            'patient_id' => ['required', Rule::notIn(['','0'])],
            'lists' => 'required|array|min:1',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $service) {
            $service->update([
                'date' => $request->date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id == 0 ? null : $request->doctor_id,
                'total_amount' => $request->total_amount,
                'discount' => $request->discount ?? 0,
                'amount_after_discount' => ($request->total_amount-(($request->total_amount/100)*$request->discount)) ?? 0,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);

            $previous = $service->lists()->count();
            $current = count($request->lists);
            $serviceList = $service->lists;
            if ($previous == $current) {
                foreach ($request->lists as $key => $value) {
                    $serviceList[$key]->update([
                        'test_id' => $value['test_id'],
                        'cost' => $value['cost'],
                        'description' => $value['description'],
                        'updated_by' => Auth::id()
                    ]);
                }
            } elseif ($current > $previous) {
                foreach ($request->lists as $key => $value) {
                    if (isset($serviceList[$key])) {
                        $serviceList[$key]->update([
                            'test_id' => $value['test_id'],
                            'cost' => $value['cost'],
                            'description' => $value['description'],
                            'updated_by' => Auth::id()
                        ]);
                    } else {
                        ServiceList::create([
                            'service_id' => $service->id,
                            'test_id' => $value['test_id'],
                            'cost' => $value['cost'],
                            'description' => $value['description'],
                            'created_by' => Auth::id()
                        ]);
                    }     
                }
            } else {
                foreach ($request->lists as $key => $value) {
                    if ($value['id'] != 0) {
                        $singleItem = ServiceList::find($value['id']);
                        $singleItem->update([
                            'test_id' => $value['test_id'],
                            'cost' => $value['cost'],
                            'description' => $value['description'],
                            'updated_by' => Auth::id()
                        ]);
                    } else {
                        ServiceList::create([
                            'service_id' => $service->id,
                            'test_id' => $value['test_id'],
                            'cost' => $value['cost'],
                            'description' => $value['description'],
                            'created_by' => Auth::id()
                        ]);
                    }     
                }

                foreach ($request->deleted as $key => $value) {
                    $singleItem = ServiceList::find($value);
                    $singleItem->delete();
                }
            }
        }, 5);

        return response()->json('Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        DB::transaction(function () use ($service) {
            $service->lists()->delete();
            $service->delete();
        }, 5);

        return response()->json('Deleted Successfully');
    }

    public function returnPageView()
    {
        $total_amount = Service::sum('total_amount');
        $discount = Service::sum('discount');
        $final_amount = Service::sum('paid_amount');
        $due_amount = ($total_amount - $discount) - $final_amount;
        $start_date = '';
        $end_date = "";
        return view('report.service.graph', compact('total_amount','discount','final_amount','due_amount','start_date','end_date'));
    }

    public function allServicesReport(Request $request)
    {
        $this->validate($request, array(
            'test' => ['nullable', Rule::notIn(['0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ));

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $test = $request->test;
        $prod = Test::find($test);

        $services = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->test != '', function ($q) use ($request) {
                return $q->whereHas('lists', function($query) use ($request) {
                    return $query->where('test_id', $request->test);
                });
            })
            ->orderBy('date', 'asc')
            ->get();

        $total_amount = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->test != '', function ($q) use ($request) {
                return $q->whereHas('lists', function($query) use ($request) {
                    return $query->where('test_id', $request->test);
                });
            })
            ->sum('total_amount');

        $discount = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->test != '', function ($q) use ($request) {
                return $q->whereHas('lists', function($query) use ($request) {
                    return $query->where('test_id', $request->test);
                });
            })
            ->sum('discount');

        $final_amount = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->test != '', function ($q) use ($request) {
                return $q->whereHas('lists', function($query) use ($request) {
                    return $query->where('test_id', $request->test);
                });
            })
            ->sum('paid_amount');

        return view('report.service.report', compact('services', 'total_amount', 'discount', 'final_amount', 'start_date', 'end_date', 'test', 'prod'));
    }

    public function serviceGraph(Request $request){
        $this->validate($request, array(
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ));

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $total_amount = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->sum('total_amount');

        $discount = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->sum('discount');

        $final_amount = Service::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
            ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
                return $q->where('date', '>=', $request->start_date);
            })
            ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
                return $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->sum('paid_amount');

        $due_amount = ($total_amount - $discount) - $final_amount;

        return view('report.service.graph', compact( 'total_amount', 'discount', 'final_amount','due_amount', 'start_date', 'end_date'));
    }

    public function serviceInvoicePrint($id){

        $service = Service::findOrFail($id);
        return view('pages.service.invoice_print',compact('service'));
    }
}
