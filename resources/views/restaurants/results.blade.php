@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='css/results.css' rel='stylesheet'>
@endpush

@section('content')
        @if ($type)
            <h2>Results for {{$type}}'s restaurants in {{$location}}</h2>
        @else
            <h2>Results for restaurants in {{$location}}</h2>
        @endif
        @foreach($filteredRestaurants as $restaurant)
            <div class="row">
                <div class="col-md-4">
                    <img src='{{$restaurant['image']}}' alt='{{$restaurant['name']}}'>
                    <input type='hidden' name='address{{$restaurant['id']}}' value='{{$restaurant['address']}}'>
                </div>
                <form  class="form-horizontal" method='POST' action='/add'>

                    {{ csrf_field() }}

                    <div class="col-md-8">
                        <input type='hidden' name='image{{$restaurant['id']}}' value='{{$restaurant['image']}}'>
                        <h4><span>{{$restaurant['name']}}</span><hr class='storeName'></h4>
                        <input type='hidden' name='name{{$restaurant['id']}}' value='{{$restaurant['name']}}'>
                        <h5>Address: <span>{{$restaurant['address']}}</span></h5>
                        <input type='hidden' name='address{{$restaurant['id']}}' value='{{$restaurant['address']}}'>
                        <h5>Phone: <span>{{$restaurant['phone']}}</span></h5>
                        <input type='hidden' name='phone{{$restaurant['id']}}' value='{{$restaurant['phone']}}'>
                        <h5>Price: <span>{{$restaurant['price']}}</span></h5>
                        <input type='hidden' name='price{{$restaurant['id']}}' value='{{$restaurant['price']}}'>
                        <h5>Rating: <span>{{$restaurant['rating']}}/5</span></h5>
                        <input type='hidden' name='rating{{$restaurant['id']}}' value='{{$restaurant['rating']}}'>
                        <h5><a href="{{$restaurant['url']}}">Find out more!</a></h5>
                        <input type='hidden' name='url{{$restaurant['id']}}' value='{{$restaurant['url']}}'>
                        <button type="submit" class="btn btn-primary btn-md pull-right">Add to list</button>
                        <input type='hidden' name='id' value='{{$restaurant['id']}}'>
                        <?php
                            $_SESSION['restaurant'] = $filteredRestaurants;
                            $_SESSION['type'] = $type;
                            $_SESSION['location'] = $location;
                        ?>
                    </div>
                </form>
            </div>
            <hr class="divder">
        @endforeach


@endsection
