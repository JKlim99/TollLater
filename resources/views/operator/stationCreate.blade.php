@extends('operator.layout.public')
@section('title', 'Toll Station Creation')

@section('sidebar_active')
<?php
$active = 'station';
?>
@endsection

@section('header', 'Create Toll Station')

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
        <input type="text" name="name" placeholder="Station Name" class="form-control" required value="{{old('name', null)}}" onkeyup='errorNoted();'/>
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
        <input type="text" name="highway" placeholder="Highway" class="form-control" required value="{{old('highway', null)}}"/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Toll Type</span>
        </label>
        <select class="form-control" name="type" onchange="showPrice(this)">
            <option value="open">Open</option>
            <option value="closed_entry">Closed (Entry)</option>
            <option value="closed_exit">Closed (Exit)</option>
        </select>
    </div>
    <div class="mb-3" id="open_price">
        <label class="form-label">
            <span class="label-text">Open Toll Price</span>
        </label>
        <input type="number" min='0' name="price" class="form-control" value="{{old('price', null)}}"/>
    </div>
    <div id="closed_prices" style="display:none">
        <div class="mb-3">
            <label class="form-label">
                <span class="label-text">Closed Toll Exit Price</span>
            </label>
            <a class="btn btn-success float-end" onclick="addPrice()">+ Add Price</a>
        </div>
        <div id="closed_toll_prices">
            <div class="input-group mb-3 price">
                <select class="form-select" name="station_ids[]">
                    @foreach($stations as $station)
                    <option value="{{$station->id}}">{{$station->name}}</option>
                    @endforeach
                </select>
                <input type="number" min=0 step=".01" class="form-control" name="prices[]"/>
                <a class="btn btn-danger" onclick="removePrice(this)">Remove</a>
            </div>
        </div>
    </div>
    <hr/>
    <div class="mb-3">
        <button class="btn btn-primary float-end" id="button">Create Toll Station</button>
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
        else if(type.value == 'closed_entry')
        {
            open_price.style.display = 'none';
            closed_price.style.display = 'block';
        }
        else
        {
            open_price.style.display = 'none';
            closed_price.style.display = 'none';
        }
    }
</script>
@endsection