<?php

namespace App\Http\Controllers;

use App\{Commission, Doctor, Test};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class CommissionController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commission $commission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commission $commission)
    {
        //
    }

    public function returnPageView()
    {
        $doctors = Doctor::all('id', 'name');
        $tests = Test::all('id', 'name');
        return view('report.view', compact('doctors', 'tests'));
    }

    public function doctorBasedCommission(Request $request)
    {
        $messages = array(
            'doctor.required' => 'Doctor is Required.'
        );
        $this->validate($request, array(
            'doctor' => ['required', Rule::notIn(['','0'])]
        ), $messages);

        $doctor = Doctor::findOrfail($request->doctor);
        $commissions = $doctor->commissions()->get();

        return view('report.doctor_commission.report', compact('commissions', 'doctor'));
    }

    public function testBasedCommission(Request $request)
    {
        $messages = array(
            'test.required' => 'Test is Required.'
        );
        $this->validate($request, array(
            'test' => ['required', Rule::notIn(['','0'])]
        ), $messages);

        $test = Test::findOrfail($request->test);
        $commissions = $test->commissions()->get();

        return view('report.test_commission.report', compact('commissions', 'test'));
    }
}
