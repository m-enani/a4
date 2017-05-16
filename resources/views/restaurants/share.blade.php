@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='css/results.css' rel='stylesheet'>
@endpush

@section('content')

    <!-- Implementing in the future! -->

    <h2>Time to for some democracy!</h2>
    <h4 style="text-align:center">Enter the emails for as many - or as few - friends as you would like below.</h4>
    <form class="form-horizontal" method='post' action="\send">

        {{ csrf_field() }}
        <label for="name">Your name</label>
        <input type="text" class="form-control" name="name" placeholder="Umm...your name???" required="true">
        <br>
        <label for="name">Your email</label>
        <input type="text" class="form-control" name="senderEmail" placeholder="You get the idea." required="true">
        <br>
        <label for="name">The emails of those you'd like to join you for LunchOUT</label>
        <br>
        <textarea class="form-control" rows="5" name="emails" placeholder="Must be comma separated! (ex. someonet@test.com, someoneelse@test.com)"></textarea>
        <br>
        <button type='submit' class="btn btn-primary btn-lg pull-right" id="removeChoice">Let them vote!</button>
    </form>

@endsection
