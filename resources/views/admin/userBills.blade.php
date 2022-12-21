@extends('admin.layout.public')
@section('title', 'User Bills')

@section('sidebar_active')
<?php
$active = 'user';
?>
@endsection

@section('header', 'User Bills')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-secondary" href="/admin/users">
        Back to list
    </a>
</div>
@endsection

@section('content')
<?php $tab_active = 'billing'; ?>
@include('admin.layout.userTab')

<form>
    <div class="input-group mb-3">
        <select class="form-select" name="card_serial_no" onchange="this.form.submit()">
            @foreach($cards as $card)
            <option value="{{$card->card_serial_no}}" @if($card_serial_no == $card->card_serial_no) selected @endif>Card # {{$card->card_serial_no}}</option>
            @endforeach
            <option value="penalty" @if($card_serial_no == 'penalty') selected @endif>Penalty</option>
        </select>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Bill Date</th>
                <th scope="col">Bill Amount</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            ?>
            @foreach($bills as $bill)
            <?php
            $date1 = date('Y-m-d', strtotime($bill->created_at));
            $date2 = date('Y-m-d', strtotime($bill->due_date));
            if($date1 == $date2){
                continue;
            }
            ?>
            <tr>
                <td>{{$bill->id}}</td>
                <td>{{date('d M Y', strtotime($bill->created_at));}}</td>
                <td>RM{{number_format($bill->amount, 2, '.', ',');}}</td>
                <td>
                    <a target="_blank" href="/pdf/bill/{{$bill->id}}">
                        PDF view                       
                    </a>
                </td>
            </tr>
            <?php $count++; ?>
            @endforeach

            @if($count == 0)
            <tr>
                <td colspan="4" class="text-center">
                    No result found.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    {{ $bills->links('pagination::bootstrap-5') }}
</div>
@endsection