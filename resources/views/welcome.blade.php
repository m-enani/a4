@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='css/main.css' rel='stylesheet'>
@endpush

<!-- @section('header')
    <h2>Lunch is always better when good friends are involved.</h2>
@endsection -->

@section('search')
    <form  class="form-horizontal" method='GET' action='/search'>
        <div class="input-group input-group-lg" id="searchBox">
            <span class="input-group-addon textarea-addon">Location</span>
            <input type="text" class="form-control" name="location" placeholder="City or zip code (required)" required="true">
            <span class="input-group-addon textarea-addon">Price</span>
            <select class="form-control selectpicker" name='price'>
              <option value='1'>$</option>
              <option value='1,2'>$$</option>
              <option value='1,2,3'>$$$</option>
              <option value='1,2,3,4'>$$$$</option>
            </select>
            <span class="input-group-addon textarea-addon">Type</span>
            <input type="search" class="typeahead form-control" autocomplete="off" name="type" placeholder="ex. 'Japanase'">
            <span type="submit" class="input-group-btn">
                <button type="submit" class="btn btn-secondary" id="searchButton" type="button">Find Lunch!</button>
            </span>
        </div>
        <div id="radiusBox" >
            <span class="radio-inline">Radius</span>
            <label class="radio-inline">
              <input type="radio" name="radius" value="16093">10 mi
            </label>
            <label class="radio-inline">
              <input type="radio" name="radius" value="32187">20 mi
            </label>
            <label class="radio-inline">
              <input type="radio" name="radius" checked="checked" value=''>Any
            </label>
        </div>
    </form>

    <!-- script from: http://itsolutionstuff.com/post/laravel-5-autocomplete-using-bootstrap-typeahead-js-example-with-demoexample.html -->
    <script type="text/javascript">
        var path = "{{ route('autocomplete') }}";
        $('input.typeahead').typeahead({
            source:  function (query, process) {
            return $.get(path, { query: query }, function (data) {
                    return process(data);
                });
            }
        });
    </script>

    @if(count($errors) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class='alert alert-danger'>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('content')

    <h2>Welcome to <span>LunchOUT!</span></h2>

    <p>LunchOUT! is an app built to make planning lunch out with your friends, coworker, or whomever else you make be inclined to dine with a whole lot easier!
         Simply compile a list of five choices, share the link with your friends, and everyone gets to vote. Having a difficult time choosing one of the great restaurants on
         your list? The random button has you covered. If the majority of your crew chooses random, one of the restaurants on your list will be chosen at, you guessed it, random.
    </p>

    <p>Go out to lunch often? Want to keep track of all the places you've been to recently? Create an account and you can do all that and more!</p>

@endsection
