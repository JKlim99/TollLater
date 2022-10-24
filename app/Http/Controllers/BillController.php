<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    public function payPage()
    {
        return view('pay');
    }

    public function billPage()
    {
        return view('bills');
    }

    public function receiptPage()
    {
        return view('receipts');
    }
}
