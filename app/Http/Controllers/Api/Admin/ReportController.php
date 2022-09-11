<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //thong ke doanh thu theo thoi gian
    public function report(Request $request){
        dd($request->all());
        
    }

}
