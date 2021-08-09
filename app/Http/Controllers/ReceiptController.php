<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\ReceiptDetail;
use App\Models\LastInvoiceNumber;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function index()
    {
        return Receipt::with('customer', 'details', 'totalDetails')->latest()->paginate(20);
    }

    public function show($id)
    {
        return Receipt::with('customer', 'details')->whereId($id)->first();
    }

    public function store(Request $request)
    {
        $invoiceid = LastInvoiceNumber::firstOrCreate(
            [
                'id' => 1,
            ],
            [
                "last_invoice_no" => '10000',
            ]
        );
        $invoiceid = $invoiceid->last_invoice_no + 1;

        $request->merge(['invoice_id' => $invoiceid]);

        $validated_receipt = $request->validate([
            "customer_id" => "required|integer",
            "invoice_id" => "required|integer",
            "date" => "required|date",
            "paid_at" => "requied|date",
            "details" => "required|array|min:1",
            "details.*.name" => "required|string",
            "details.*.price" => "required|numeric",
            "details.*.quantity" => "required|integer",
            "details.*.note" => "nullable|string",
        ]);

        $recept = Receipt::create($validated_receipt);
        if ($recept) {
            // Create Details
            foreach ($request->details as $arr) {
                Arr::set($arr, 'receipt_id', $recept->id);
                ReceiptDetail::create($arr);
            }

            // Update last invoice number
            LastInvoiceNumber::find(1)->update(['last_invoice_no' => $invoiceid]);
        }
    }
    public function update(Request $request)
    {
        // dd($request->all());
        $receipt = Receipt::updateOrCreate([
            "id" => $request->id,
        ], [
            "customer_id" => $request->customer_id,
            "date" => $request->date,
        ]);

        foreach ($request->details as $detail) {
            ReceiptDetail::updateOrCreate([
                "id" => $detail['id'],
                "receipt_id" => $detail['receipt_id'],
            ], [
                "receipt_id" => $receipt->id,
                "name" => $detail["name"],
                "price" => $detail["price"],
                "quantity" => $detail["quantity"],
                "currency" => $detail["currency"],
                "note" => $detail["note"],
            ]);
        }
        return $receipt;
    }
    public function destroy($id)
    {
        $receipt = Receipt::find($id);
        if ($receipt) {
            if ($receipt->details()->delete()) {
                return $receipt->delete();
            }
            else{
                return $receipt->delete();
            }
        } else {
            return response(["message" => "Receipt id doesn't exist"], 500);
        }
    }

    public function newInvoiceNumber()
    {
        $lastInvoiceNo = LastInvoiceNumber::firstOrCreate(
            ['id' => 1],
            ['last_invoice_no' => 10000]
        );

        return $lastInvoiceNo->last_invoice_no + 1;
    }
}
