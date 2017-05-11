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
            <input type="text" class="form-control" name="location" placeholder="City or zip code" required="true">
            <span class="input-group-addon textarea-addon">Type</span>
            <input type="text" class="form-control" name="type" placeholder="Type">
            <span type="submit" class="input-group-btn">
                <button type="submit" class="btn btn-secondary" id="searchButton" type="button">Find Lunch!</button>
            </span>
        </div>
    </form>
@endsection

@section('content')

    <h2>Welcome to <span>LunchOUT!</span></h2>

    <p>LunchOUT! is an app built to make planning lunch out with your friends, coworker, or whomever else you make be inclined to dine with a whole lot easier!
         Simply compile a list of five choices, share the link with your friends, and everyone gets to vote. Having a difficult time choosing one of the great restaurants on
         your list? The random button has you covered. If the majority of your crew chooses random, one of the restaurants on your list will be choosen at, you guessed it, random.
    </p>

    <p>Go out to lunch often? Want to keep track of all the places you've been to recently? Create an account and you can do all that and more!</p>

@endsection
