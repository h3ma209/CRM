<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        return Contract::with('customer')->latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);

        $validated_contract = $request->validate([
            'user_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'start_date' => 'required|date',
            'expire_date' => 'nullable|date',
            'prepayment' => 'nullable|numeric',
            'prepayment_currency' => 'nullable|string',
            'monthly_payment' => 'nullable|numeric',
            'monthly_payment_currency' => 'nullable|string',
            'user_quantity' => 'required|integer',
            'note' => 'nullable|string',
            'credentials.*.username' => "required|string",
            'credentials.*.password' => "required|string",
            'credentials.*.note' => "required|string",
        ]);

        $contract = Contract::create($validated_contract);
        $contract->credentials()->createMany($validated_contract['credentials']);

        return $contract;
    }

    public function show($id)
    {
        return Contract::with('credentials')->where('id', $id)->first();
    }

    public function update(Request $request, Contract $contract)
    {
        $request->merge(['user_id' => auth()->user()->id]);
        $validated_contract = $request->validate([
            'user_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'start_date' => 'required|date',
            'expire_date' => 'nullable|date',
            'prepayment' => 'nullable|numeric',
            'prepayment_currency' => 'nullable|string',
            'monthly_payment' => 'nullable|numeric',
            'monthly_payment_currency' => 'nullable|string',
            'user_quantity' => 'required|integer',
            'note' => 'nullable|string',
            'credentials.*.username' => "required|string",
            'credentials.*.password' => "required|string",
            'credentials.*.note' => "required|string",
        ]);

        $contract->update($validated_contract);
        $contract->credentials()->updateMany($validated_contract['credentials']);

        return $contract;
    }

    public function destroy($id)
    {
        return Contract::find($id)->delete();
    }
}
