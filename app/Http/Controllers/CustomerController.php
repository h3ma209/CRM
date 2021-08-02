<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\LastInvoiceNumber;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function list()
    {
        return Customer::select('id', 'name')->get();
    }

    public function index(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date ?? $start_date;

        $customers = Customer::when($start_date, function($q) use($start_date, $end_date) {
            $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
        })->latest();

        return $customers->paginate(20);
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);

        $validated_customer = $request->validate([
            'user_id' => 'required',
            'name' => 'required|string',
            'address' => 'nullable|string',
            'contact_1' => 'nullable|numeric',
            'contact_2' => 'nullable|numeric',
            'free_trial' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $customer = Customer::create($validated_customer);

        return $customer;
    }

    public function show($id)
    {
        return Customer::find($id);

    }

    public function update(Request $request, Customer $customer)
    {
        $validated_customer = $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'contact_1' => 'nullable|integer',
            'contact_2' => 'nullable|integer',
            'free_trial' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $customer->update($validated_customer);

        return $customer;
    }

    public function destroy($id)
    {
        return Customer::find($id)->delete();
    }

    public function contracts(Request $request, $customer)
    {
        return Contract::where('customer_id', $customer)->with('customer')->latest()->paginate(20);
    }
    public function receipts(Request $request, $customer)
    {
        return Receipt::where('customer_id', $customer)->with('customer','details')->latest()->paginate(20);
    }

    public function newMonthlyReceipt(Request $request, $customer)
    {
        $contract = Contract::where('customer_id', $customer)->latest()->first();

        if (! $contract) {
            return response(['message' => 'Customer does not have contract.'], 422);
        }
        
        $invoiceid = LastInvoiceNumber::firstOrCreate(
            [
                'id' => 1,
            ],
            [
                "last_invoice_no" => '10000'
            ]
        );
        $invoiceid = $invoiceid->last_invoice_no + 1;

        $receipt = Receipt::create([
            "customer_id" => $customer,
            "invoice_id" => $invoiceid,
            "date" => date('Y-m-d'),
        ]);

        if ($receipt) {
            // Create Details
            $receipt->details()->create([
                'name' => 'Monthly Payment',
                'price' => $contract->monthly_payment,
                'quantity' => $contract->user_quantity,
            ]);

            // Update last invoice number
            LastInvoiceNumber::find(1)->update(['last_invoice_no' => $invoiceid]);
        }
    }
}