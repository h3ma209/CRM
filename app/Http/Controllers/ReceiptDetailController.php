<?php

namespace App\Http\Controllers;

use App\Models\ReceiptDetail;
use Illuminate\Http\Request;

class ReceiptDetailController extends Controller
{
    public function index(Request $request)
    {
        
    }
    public function destroy($id){
        $rd = ReceiptDetail::find($id);
        if($rd){
            $rd->delete();
            return "deleted ".$id;
        }
        else{
            return response(["message"=>"detail does not exist in database"]);
        }

    }
}
