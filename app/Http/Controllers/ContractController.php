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
            'monthly_payment' => 'nullable|numeric',
            'user_quantity' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $contract = Contract::create($validated_contract);

        return $contract;
    }

    public function show($id)
    {
        return Contract::find($id);
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
            'monthly_payment' => 'nullable|numeric',
            'user_quantity' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $contract->update($validated_contract);

        return $contract;
    }

    public function destroy($id)
    {
        return Contract::find($id)->delete();
    }
}
