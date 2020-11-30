<?php

namespace App\Http\Controllers;

use App\{TestCategory, Test, Doctor};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.test.index');
    }

    public function getTestList(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'test_category_id',
            4 => 'cost',
            5 => 'actions'
        );

        $totalData = $totalFiltered = Test::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $tests = Test::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $tests =  Test::with('category')
            ->where('code', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('cost', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Test::with('category')
            ->where('code', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('cost', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($tests)) {
            foreach ($tests as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['code'] = $value->code;
                $nestedData['name'] = $value->name;
                $nestedData['test_category_id'] = $value->category->name;
                $nestedData['cost'] = $value->cost;
                $nestedData['actions'] = '<div class="">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('test.show', $value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button> 
                    <a href=" '.route('test.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('test.destroy', $value->id).' " title="Delete">
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
        $test_categories = TestCategory::all('id','name');
        $test = null;
        $doctors = Doctor::all();
        return view('pages.test.create', compact('test', 'test_categories', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd(1);

        $messages = array(
            'name.required' => 'Name is Required.',
            'test_category_id.required' => 'Category is Required.',
            'cost.required' => 'Cost is Required.',
            'commission_type.*.required' => 'Commission Type is Required.',
            'commission.*.required' => 'Commission is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'test_category_id' => ['required', Rule::notIn(['','0'])],
            'cost' => 'required|numeric|min:0',
            'commission_type' => 'required|array',
            'commission_type.*' => ['required', Rule::notIn(['','0'])],
            'commission' => 'required|array',
            'commission.*' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $code = mt_rand(100000, 999999);
            } while (Test::where('code', $code)->exists());

            $test = Test::create([
                'name' => $request->name,
                'code' => $code,
                'test_category_id' => $request->test_category_id,
                'cost' => $request->cost,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);

            $doctors = Doctor::all();
            foreach ($doctors as $key => $value) {
                $test->commissions()->attach($value->id, [
                    'commission_type' => $request->commission_type[$key] ?? 1,
                    'commission' => $request->commission[$key] ?? 0,
                    'description' => $request->note[$key] ?? "",
                    'created_by' => Auth::id()
                ]);
            }
        }, 5);

        return redirect()
        ->route('test.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        $test->load('createdBy', 'category', 'commissions');
        if ($test->updated_by) {
            $test->load('updatedBy');
        }
        return response()->json($test);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Test $test)
    {
        $test_categories = TestCategory::all('id','name');
        $doctors = Doctor::all();
        return view('pages.test.edit', compact('test', 'test_categories', 'doctors'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'test_category_id.required' => 'Category is Required.',
            'cost.required' => 'Cost is Required.',
            'commission_type.*.required' => 'Commission Type is Required.',
            'commission.*.required' => 'Commission is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'test_category_id' => ['required', Rule::notIn(['','0'])],
            'cost' => 'required|numeric|min:0',
            'commission_type' => 'required|array',
            'commission_type.*' => ['required', Rule::notIn(['','0'])],
            'commission' => 'required|array',
            'commission.*' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $test) {
            $test->update([
                'name' => $request->name,
                'test_category_id' => $request->test_category_id,
                'cost' => $request->cost,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);

            $doctors = Doctor::all();
            $syncData = [];
            foreach ($doctors as $key => $value) {
                $syncData[$value->id] = [
                    'commission_type' => $request->commission_type[$key] ?? 1,
                    'commission' => $request->commission[$key] ?? 0,
                    'description' => $request->note[$key] ?? "",
                    'updated_by' => Auth::id()
                ];
            }
            $test->commissions()->sync($syncData);
        }, 5);

        return redirect()
        ->route('test.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        $test->delete();
        return response()->json("Deleted Successfully");
    }
}
