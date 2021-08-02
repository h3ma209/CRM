<?php

namespace App\Http\Controllers;

use App\Models\LastInvoiceNumber;
use Illuminate\Http\Request;

class LastInvoiceNumberController extends Controller
{
    public function index(){
        return LastInvoiceNumber::first()->last_invoice_no;
    }
}
