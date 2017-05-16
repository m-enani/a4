@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='css/results.css' rel='stylesheet'>
@endpush

@section('content')
    <h2>Your vote has been submitted! Thank your for using <span>LunchOUT!</span></h2>
    <h4 style="text-align:center">Results feature is undercontruction!</h4>
    <!-- <a href='{{ $_SERVER["SERVER_NAME"]}}/winner/{{$invitation}}'>{{ $_SERVER["SERVER_NAME"]}}/winner/{{$invitation}}</a> -->
@endsection
