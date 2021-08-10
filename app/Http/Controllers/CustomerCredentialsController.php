<?php

namespace App\Http\Controllers;

use App\Models\CustomerCredentials;
use Illuminate\Http\Request;

class CustomerCredentialsController extends Controller
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
     * @param  \App\Models\CustomerCredentials  $customerCredentials
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCredentials $customerCredentials)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCredentials  $customerCredentials
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCredentials $customerCredentials)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCredentials  $customerCredentials
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCredentials $customerCredentials)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCredentials  $customerCredentials
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cred = CustomerCredentials::find($id);
        if($cred){
            $cred->delete();
        }

    }
}
