<?php

namespace App\Http\Controllers;

use App\{Doctor, DoctorPayment, Service, Test, Commission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.doctor.index');
    }

    //server side datatable
    public function getDoctorList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'image',
            2 => 'code',
            3 => 'name',
            4 => 'mobile',
            5 => 'specialty',
            6 => 'actions'
        );

        $totalData = $totalFiltered = Doctor::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $doctors = Doctor::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $doctors =  Doctor::where('code', 'LIKE', "%{$search}%")
            ->orWhere('mobile', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('specialty', 'LIKE', "%{$search}%")
            ->orWhere('qualification', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

            $totalFiltered = Doctor::where('code', 'LIKE', "%{$search}%")
            ->orWhere('mobile', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('specialty', 'LIKE', "%{$search}%")
            ->orWhere('qualification', 'LIKE', "%{$search}%")
            ->count();
        }

        $data = array();
        if (!empty($doctors)) {
            foreach ($doctors as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['image'] = '<a target="_blank" class="" title="Click to View" href=" '.url("images/doctor/$value->image").' "> <img class="card-img" style="width: 45px; height: 40px" src=" '.url("images/doctor/$value->image").' " alt="Doctor"> </a>';
                $nestedData['code'] = $value->code;
                $nestedData['name'] = $value->name;
                $nestedData['mobile'] = $value->mobile;
                $nestedData['specialty'] = $value->specialty;
                $nestedData['actions'] = '<div class="">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('doctor.show', $value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button> 
                    <a href=" '.route('doctor.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>     
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('doctor.destroy', $value->id).' " title="Delete">
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
        $doctor = null;
        $tests = Test::all();
        $commissions = null;
        return view('pages.doctor.create', compact('doctor', 'tests', 'commissions'));
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
            'mobile.required' => 'Mobile is Required.',
//            'commission_type.*.required' => 'Commission Type is Required.',
//            'commission.*.required' => 'Commission is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'mobile' => 'required',
//            'commission_type' => 'required|array',
////            'commission_type.*' => ['required', Rule::notIn(['','0'])],
////            'commission' => 'required|array',
////            'commission.*' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $attached = mt_rand(111, 999) . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path().'/images/doctor';
                $image->move($destinationPath, $attached);
            }

            do {
                $code = mt_rand(100000, 999999);
            } while (Doctor::where('code', $code)->exists());

            $doctor = Doctor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'code' => $code,
                'email' => $request->email,
                'address' => $request->address,
                'specialty' => $request->specialty,
                'qualification' => $request->qualification,
                'image' => $attached ?? 'no_image.png',
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            $tests = Test::all();
            foreach ($tests as $key => $value) {
                $doctor->commissions()->attach($value->id, [
                    'commission_type' => $request->commission_type[$key] ?? 1,
                    'commission' => $request->commission[$key] ?? 0,
                    'description' => $request->note[$key] ?? "",
                    'created_by' => Auth::id()
                ]);
            }
        }, 5);

        return redirect()
        ->route('doctor.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('createdBy', 'commissions');
        if ($doctor->updated_by) {
            $doctor->load('updatedBy');
        }
        return response()->json($doctor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //dd($doctor->commissions()->first());
        $tests = Test::all();
        $commissions = null;
        return view('pages.doctor.edit', compact('doctor', 'tests', 'commissions'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'mobile.required' => 'Mobile is Required.',
            'commission_type.*.required' => 'Commission Type is Required.',
            'commission.*.required' => 'Commission is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'mobile' => 'required',
            'commission_type' => 'required|array',
            'commission_type.*' => ['required', Rule::notIn(['','0'])],
            'commission' => 'required|array',
            'commission.*' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $doctor) {
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $attached = mt_rand(111, 999) . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path().'/images/doctor';
                $image->move($destinationPath, $attached);
            }

            $doctor->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'address' => $request->address,
                'specialty' => $request->specialty,
                'qualification' => $request->qualification,
                'image' => $attached ?? $request->oldimage ?? 'no_image.png',
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);

            $tests = Test::all();
            $syncData = [];
            foreach ($tests as $key => $value) {
                $syncData[$value->id] = [
                    'commission_type' => $request->commission_type[$key] ?? 1,
                    'commission' => $request->commission[$key] ?? 0,
                    'description' => $request->note[$key] ?? "",
                    'updated_by' => Auth::id()
                ];
            }
            $doctor->commissions()->sync($syncData);
        }, 5);

        return redirect()
        ->route('doctor.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        if ($doctor->image != 'no_image.png') {
            File::delete("images/doctor/".$doctor->image);
            $doctor->update([
                'image' => 'no_image.png'
            ]);
        }
        
        $doctor->delete();

        return response()->json("Deleted Successfully");
    }

    public function returnPageView()
    {
        $doctors = Doctor::all('id','name');
        return view('report.view', compact('doctors'));
    }

    public function doctorReport(Request $request)
    {
        $messages = array(
            'doctor.required' => 'Doctor is Required.'
        );
        $this->validate($request, array(
            'doctor' => ['required', Rule::notIn(['','0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ), $messages);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $doctor = Doctor::findOrFail($request->doctor);

        $doctor_total_paid = $doctor->payments()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->sum('amount');

        $doctor_payments = $doctor->payments()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
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

        $services = $doctor->services()->when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
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

        $total = 0;
        $commissions = [];

        foreach ($services as $service) {
            foreach ($service->lists as $list) {
                $commission = $doctor->commissions()
                ->where('test_id', $list->test_id)
                ->first();

                if ($commission->pivot->commission_type == 1) {
                    $amount = (($list->cost * $commission->pivot->commission) / 100);
                } else{
                    $amount = $commission->pivot->commission;
                }
                $total += $amount;
                $single_item = [];
                array_push($single_item, $service->date, $list->test->name, $list->cost, $commission->pivot->commission_type, $commission->pivot->commission, $amount);
                array_push($commissions, $single_item);
            }
        }

        return view('report.doctor.report', compact('start_date', 'end_date', 'commissions', 'total', 'doctor', 'doctor_payments', 'doctor_total_paid'));
    }
}