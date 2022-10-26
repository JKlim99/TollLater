<!DOCTYPE html>
<html>
<head>
    <title>TollLater - Bill Statement</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h2 class="text-center m-0 p-0">Bill Overview</h2>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left logo mt-10">
        <h1>TollLater</h1>
        <p>Hello LIM JINQ KUEN<br/><i>Here's a summary of your {{date('M-Y', strtotime($bill->created_at));}} bill.</i></p>
    </div>
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">ID: <span class="gray-color">{{$user->ic_no}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Bill No.: <span class="gray-color">{{$bill->id}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Bill Type: <span class="gray-color">{{$card ? 'Card' : 'Penalty'}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Card Serial No.: <span class="gray-color">{{$card->card_serial_no ?? '-'}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Bill Date: <span class="gray-color">{{date('d M Y', strtotime($bill->created_at));}}</span></p>

    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">CHARGES</th>
            <th class="w-25">Due Date</th>
            <th class="w-25">Amount</th>
        </tr>
        <?php
        $total = 0.00;
        ?>
        @foreach($unpaid_bills as $unpaid_bill)
        <tr>
            <td>Remaining balance from previous month ({{date('M-Y', strtotime($unpaid_bill->bill->created_at));}})</td>
            <td align="center">{{date('d M Y', strtotime($unpaid_bill->bill->due_date));}}</td>
            <td align="right">RM{{number_format($unpaid_bill->bill->amount, 2, '.', ',');}}</td>
        </tr>
        <?php
        $total += $unpaid_bill->bill->amount;
        ?>
        @endforeach
        <tr style="font-style:bold">
            <td>Current charges</td>
            <td align="center">{{date('d M Y', strtotime($bill->due_date));}}</td>
            <td align="right">RM{{number_format($bill->amount, 2, '.', ',');}}</td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total Payable</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>RM{{number_format($bill->amount+$total, 2, '.', ',');}}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th colspan="4">Detailed Charges</th>
        </tr>
        <tr>
            <th class="w-20">Date</th>
            <th class="w-20">Type</th>
            <th class="w-40">Toll Station</th>
            <th class="w-20">Charge</th>
        </tr>
        <?php $details = false;?>
        @foreach($transactions as $transaction)
        <tr>
            <td>{{date('Y-m-d h:i:s A', strtotime($transaction->created_at));}}</td>
            <td>{{ucfirst($transaction->type)}}</td>
            <td>{{$transaction->station->name}}</td>
            <td>RM{{number_format($transaction->amount, 2, '.', ',');}}</td>
        </tr>
        <?php $details = true;?>
        @endforeach
        @if(!$details)
        <tr>
            <td colspan="4" align="center">
                No transaction(s) made
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="4">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>RM{{number_format($bill->amount, 2, '.', ',');}}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
</html>