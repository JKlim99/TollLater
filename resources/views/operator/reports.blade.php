@extends('operator.layout.public')
@section('title', 'Transaction Reports')

@section('sidebar_active')
<?php
$active = 'report';
?>
@endsection

@section('header', 'Transaction Reports')

@section('content')
<form>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Highway</span>
        </label>
        <select class="form-control" name="highway">
            <option value="">- All Highway -</option>
            @foreach($highways as $highway)
            <option value="{{$highway->highway}}" @if($highway->highway==$selected_highway ) selected @endif>{{$highway->highway}}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">From Date</span>
        </label>
        <input type="date" class="form-control" name="from_date" value="{{$from_date}}"/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">To Date</span>
        </label>
        <input type="date" class="form-control" name="to_date" value="{{$to_date}}"/>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary form-control" id="button">Filter</button>
    </div>
</form>
<canvas id="myChart" width="400" height="200"></canvas>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">Station Name</th>
                <th scope="col">Highway</th>
                <th scope="col">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            ?>
            @foreach($stations as $station)
            <tr>
                <td>{{$station->name}}</td>
                <td>{{$station->highway}}</td>
                <td>RM{{number_format($station->amount??0.00, 2, '.', ',');}}</td>
            </tr>
            <?php $count++; ?>
            @endforeach

            @if($count == 0)
            <tr>
                <td colspan="3" class="text-center">
                    No result found.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> 
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'bar',
// The data for our dataset
        data: {
            labels:  {!!json_encode($stations->pluck('name'))!!} ,
            datasets: [
                {
                    label: 'Transaction Amount (RM)',
                    backgroundColor: '#5291AD',
                    data:  {!! json_encode($stations->pluck('amount'))!!} ,
                },
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    },
                    scaleLabel: {
                        display: false
                    }
                }]
            },
            legend: {
                labels: {
                    // This more specific font property overrides the global property
                    fontColor: '#122C4B',
                    fontFamily: "'Muli', sans-serif",
                    padding: 25,
                    boxWidth: 25,
                    fontSize: 14,
                }
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 0,
                    bottom: 10
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return 'Transaction Amount (RM): ' + tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                }
            }
        }
    });
</script>
@endsection