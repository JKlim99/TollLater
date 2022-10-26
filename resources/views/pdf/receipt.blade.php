<!DOCTYPE html>
<html>
<head>
    <title>TollLater - Payment Receipt</title>
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
    .green-color{
        color:#00ac09;
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
    <h2 class="text-center m-0 p-0">Payment Receipt</h2>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left logo mt-10">
        <h1>TollLater</h1>
        <p>Thank you!<br/><i>Here's your payment receipt.</i></p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Status: <span class="green-color">{{ucfirst($payment->status)}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Payment Date: <span class="gray-color">{{date('Y-m-d h:i:s A', strtotime($payment->created_at));}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Reference Number: <span class="gray-color">{{$payment->ref_id}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Bill No.: <span class="gray-color">{{$payment->bill_id}}</span></p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <td>
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Paid Amount</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>RM{{number_format($payment->amount, 2, '.', ',');}}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
    <p>Note: This receipt is computer generated and no signature is required</p>
</div>
</html>