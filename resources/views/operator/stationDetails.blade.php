@extends('operator.layout.public')
@section('title', 'Toll Station Details')

@section('sidebar_active')
<?php
$active = 'station';
?>
@endsection

@section('header', 'Toll Station Details')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-secondary" href="/operator/stations">
        Back to list
    </a>
</div>
@endsection

@section('content')
<form method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Station Name</span>
        </label>
        <input type="text" name="name" placeholder="Station Name" class="form-control" required value="{{old('name', $station->name)}}" onkeyup='errorNoted();'/>
        @if(session('error'))
        <label class="form-label" id="error">
            <span class="label-text-alt text-danger">{{session('error')}}</span>
        </label>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Highway</span>
        </label>
        <input type="text" name="highway" placeholder="Highway" class="form-control" required value="{{old('highway', $station->highway)}}"/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Toll Type</span>
        </label>
        <select class="form-control" name="type" onchange="showPrice(this)">
            <option value="open" @if($station->type=='open') selected @endif>Open</option>
            <option value="closed" @if($station->type=='closed') selected @endif>Closed</option>
        </select>
    </div>
    <div class="mb-3" id="open_price" @if($station->type!='open') style="display:none" @endif>
        <label class="form-label">
            <span class="label-text">Open Toll Price</span>
        </label>
        <input type="number" min='0' name="price" class="form-control" step=".01" value="{{old('price', $station->price)}}"/>
    </div>
    <div id="closed_prices" @if($station->type!='closed') style="display:none" @endif>
        <div class="mb-3">
            <label class="form-label">
                <span class="label-text">Pricing based on Entry Points</span>
            </label>
            <a class="btn btn-success float-end" onclick="addPrice()">+ Add Price</a>
        </div>
        <div id="closed_toll_prices">
            @foreach($closed_stations as $closed_station)
            <div class="input-group mb-3 price">
                <select class="form-select" name="station_ids[]">
                    @foreach($stations as $station)
                    <option value="{{$station->id}}" @if($closed_station->toll_station_id == $station->id) selected @endif>{{$station->name}}</option>
                    @endforeach
                </select>
                <input type="number" min=0 step=".01" class="form-control" name="prices[]" value="{{$closed_station->price}}"/>
                <input type="hidden" value="{{$closed_station->id}}" name="ids[]"/>
                <a class="btn btn-danger" onclick="removePrice(this)">Remove</a>
            </div>
            @endforeach
        </div>
    </div>
    <hr/>
    <div class="mb-3">
        <button class="btn btn-primary float-end" id="button">Update Toll Station</button>
    </div>
</form>
<script>
    var addPrice = function() {
        var options = '';
        var stations = @json($stations);
        stations.forEach( function (station) {
            options += '<option value="'+station['id']+'">'+station['name']+'</option>';
        });

        document.getElementById("closed_toll_prices").innerHTML += 
            '<div class="input-group mb-3 price">'+
                '<select class="form-select" name="station_ids[]">'+
                    options+
                '</select>'+
                '<input type="number" min=0 step=".01" class="form-control" name="prices[]"/>'+
                '<a class="btn btn-danger" onclick="removePrice(this)">Remove</a>'+
                '<input type="hidden" value="0" name="ids[]"/>'+
            '</div>';
    }

    var removePrice = function(el) {
        el.closest('.price').remove();
    }

    var showPrice = function(type) {
        var open_price = document.getElementById('open_price');
        var closed_price = document.getElementById('closed_prices');

        if(type.value == 'open')
        {
            open_price.style.display = 'block';
            closed_price.style.display = 'none';
        }
        else if(type.value == 'closed')
        {
            open_price.style.display = 'none';
            closed_price.style.display = 'block';
        }
    }
</script>
@endsection