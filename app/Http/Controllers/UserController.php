<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Role};
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{Auth, Hash};
use Carbon\{Carbon, CarbonPeriod};
use DB;
use PDF;
use Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = null;
        $roles = Role::all();
        return view('pages.user.create', compact('user', 'roles'));
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
            'email.required' => 'Email is Required.',
            'password.required' => 'Password is Required.',
            'role.required' => 'Role is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->whereNull('deleted_at')],
            'password' => 'required|min:6',
            'role' => ['required', Rule::notIn(['','0'])]
        ),$messages);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => 'avatar.jpg'
            ]);

            $user->attachRole($request->role);                    
        }, 5);

        return redirect()
            ->route('user.index')
            ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('pages.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'email.required' => 'Email is Required.',
            'role.required' => 'Role is Required.'
        );
        $this->validate($request, array(
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user)->whereNull('deleted_at')],
            'role' => ['required', Rule::notIn(['','0'])]
        ),$messages);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            $user->syncRoles([$request->role]);                    
        }, 5);

        return redirect()
            ->route('user.index')
            ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //if we detach roles when deleting, relationship will cause an error if we restore a user.
        // DB::transaction(function () use ($user) {
        //     $user->detachRoles($user->roles);
        //     $user->delete();                           
        // }, 5);

        $user->delete();
        return redirect()
            ->route('user.index')
            ->with('success', 'Deleted Successfully');
    }

    public function printAllUsers()
    {
        dd('working process is going on. please try again later');
    }

    public function pdfAllUsers()
    {
        dd('working process is going on. please try again later');
    }

    public function excelAllUsers()
    {
        dd('working process is going on. please try again later');
    }
}
