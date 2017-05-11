@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='css/search.css' rel='stylesheet'>
@endpush

@section('content')

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
        <li data-target="#myCarousel" data-slide-to="3"></li>
        <li data-target="#myCarousel" data-slide-to="4"></li>
      </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner">

        @foreach($filteredRestaurants as $restaurant)
            <div class="imageContainer item {{$restaurant['id']==0 ? 'active' : ''}}">
                <img class="carouselImage" src='{{$restaurant['image']}}' alt='{{$restaurant['name']}}'>
            </div>
        @endforeach


      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

@endsection
