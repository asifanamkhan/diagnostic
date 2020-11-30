<?php

namespace App\Http\Controllers;

use App\{Patient, Service, ServicePayment};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patient = null;
        return view('pages.patients.create', compact('patient'));
    }

    public function getPatientList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'mobile',
            4 => 'age',
            5 => 'gender',
            6 => 'actions'
        );

        $totalData = $totalFiltered = Patient::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $patients = Patient::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $patients =  Patient::where('code', 'LIKE', "%{$search}%")
            ->orWhere('mobile', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('age', 'LIKE', "%{$search}%")
            ->orWhere('gender', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Patient::where('code', 'LIKE', "%{$search}%")
            ->orWhere('mobile', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('age', 'LIKE', "%{$search}%")
            ->orWhere('gender', 'LIKE', "%{$search}%")
            ->count();
        }

        $data = array();
        if (!empty($patients)) {
            foreach ($patients as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['name'] = $value->name;
                $nestedData['code'] = $value->code;
                $nestedData['mobile'] = $value->mobile;
                $nestedData['age'] = $value->age;
                if ($value->gender == 'male') {
                    $nestedData['gender'] = 'Male';
                } elseif ($value->gender == 'female') {
                    $nestedData['gender'] = 'Female';
                } elseif ($value->gender == 'others') {
                    $nestedData['gender'] = 'Other';
                } else {
                    $nestedData['gender'] = '--';
                }
                $nestedData['actions'] = '<div class="">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('patient.show',$value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button> 
                    <a href=" '.route('patient.edit',$value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('patient.destroy',$value->id).' " title="Delete">
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'mobile.required' => 'Mobile Number is Required.',
            'gender.required' => 'Gender is Required.',
            'age.required' => 'Age is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'mobile' => 'required',
            'gender' => ['required', Rule::notIn(['','0'])],
            'age' => 'required|numeric|min:0'
        ), $messages);

        $result = DB::transaction(function () use ($request) {
            do {
                $code = mt_rand(100000, 999999);
            } while (Patient::where('code', $code)->exists());

            $patient = Patient::create([
                'name' => $request->name,
                'code' => $code,
                'mobile' => $request->mobile,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'address' => $request->address,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            return $patient;
        });

        if ($request->wantsJson()) {
            $patients = Patient::all();
            return response()->json([$result, $patients]);
        }
        return redirect()
        ->route('patient.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        $patient->load('createdBy');
        if ($patient->updated_by) {
            $patient->load('updatedBy');
        }
        return response()->json($patient);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        return view('pages.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'mobile.required' => 'Mobile Number is Required.',
            'gender.required' => 'Gender is Required.',
            'age.required' => 'Age is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'mobile' => 'required',
            'gender' => ['required', Rule::notIn(['','0'])],
            'age' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $patient) {
            $patient->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'address' => $request->address,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('patient.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json("Deleted Successfully");
    }

    public function returnPageView()
    {
        $patients = Patient::orderBy('name','ASC')->get(['id', 'name']);
        return view('report.view', compact('patients'));
    }

    public function patientReport(Request $request)
    {
        $messages = array(
            'patient.required' => 'Patient is Required.'
        );
        $this->validate($request, array(
            'patient' => ['required', Rule::notIn(['','0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ), $messages);


        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $patient = Patient::findOrFail($request->patient);

        $services = $patient->services()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->orderBy('date', 'asc')
        ->get();

        $total_amount = $patient->services()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('total_amount');

        $discount = $patient->services()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('discount');

        $paid_amount = $patient->services()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('paid_amount');

        return view('report.patient.report', compact('start_date', 'end_date', 'services', 'total_amount', 'discount', 'paid_amount', 'patient'));
    }

}
