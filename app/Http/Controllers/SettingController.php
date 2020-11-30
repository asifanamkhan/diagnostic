<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use DB;
use Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::first();
        return view('pages.settings.index', compact('settings'));
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
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $messages = array(
            'name.required' => 'Name is Required.'
        );
        $this->validate($request, array(
            'name' => 'required'
        ), $messages);
        
        DB::transaction(function () use ($request, $setting) {
            if ($request->hasfile('logo')) {
                $image = $request->file('logo');
                $attached = mt_rand(111, 999) . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path().'/images/setting';
                $image->move($destinationPath, $attached);
            }

            $setting->update([
                'name' => $request->name ?? $setting->name,
                'logo'  => $attached ?? $setting->logo,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('setting.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
