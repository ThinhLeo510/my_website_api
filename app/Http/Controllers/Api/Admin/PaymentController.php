<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getListPayment(){
        // dd(json_decode(Payment::all()));
        return Payment::all();
    }
}
