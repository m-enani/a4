@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='/css/vote.css' rel='stylesheet'>
@endpush

@section('content')

    @if (count($choices)>0)

        <h2>Ready for LunchOUT?! Time to place your vote!</h2>
        <p>Have your say in where you go to lunch. Simply pick how you feel about each option below and we'll tally the votes. What's that you say? What happens in an event of a tie?
            Well...then the almighty computer will make the decision by randomly picking from the options with the tied scores!</p>

        <div class="row">

            <form class="form-horizontal"  method='POST' action="\tally">

                {{ csrf_field() }}

                @php
                    $count = 0;
                @endphp

                @foreach($choices as $choice)

                    <div class="col-md-4">
                        <img src='{{$choice['image_url']}}' alt='{{$choice['name']}}'>
                        <input type='hidden' name='id{{$count}}' value='{{$choice['id']}}'>
                    </div>
                    <div class="displayChoices col-md-8">
                        <!-- <input type='hidden' name='image{{$choice['id']}}' value='{{$choice['image_url']}}'> -->
                        <h4><span>{{$choice['name']}}</span><hr class='storeName'></h4>
                        <!-- <input type='hidden' name='name{{$choice['id']}}' value='{{$choice['name']}}'> -->
                        <h5>Address: <span>{{$choice['address']}}</span></h5>
                        <!-- <input type='hidden' name='address{{$choice['id']}}' value='{{$choice['address']}}'> -->
                        <h5>Phone: <span>{{$choice['phone']}}</span></h5>
                        <!-- <input type='hidden' name='phone{{$choice['id']}}' value='{{$choice['phone'] }}'> -->
                        <h5>Price: <span>{{$choice['price']}}</span></h5>
                        <!-- <input type='hidden' name='price{{$choice['id']}}' value='{{$choice['price']}}'> -->
                        <h5>Rating: <span>{{$choice['rating']}}/5</span></h5>
                        <!-- <input type='hidden' name='rating{{$choice['id']}}' value='{{$choice['rating']}}'> -->
                        <h5><a href="{{$choice['more_info']}}">Find out more!</a></h5>
                        <!-- <input type='hidden' name='id' value='{{$choice['id']}}'> -->
                    </div>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 pull-right">
                            <select class="selectRank form-control selectpicker btn-primary show-menu-arrow" id='rank{{$count}}' name='rank{{$count}}' required="true">
                                <option value="" disabled selected>* What'd you say?</option>
                                @foreach($ranks as $rank)
                                     <option value='{{ $rank['description']}}'>
                                         {{$rank['description']}}
                                     </option>
                                 @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="divder">
                    @php
                        $count +=1;
                    @endphp
                @endforeach
                <div class="row">
                    <div class="col-md-4">
                        <span class="glyphicon glyphicon-check" id="checkImg"></span>
                    </div>
                    <div class="col-md-8" id="voterEntry">
                        <Label id="voterNameLbl">Enter your name and vote!</label>
                        <input type="text" class="form-control inline" name="name" id="voterName" placeholder="Your Name" required="true">
                        <button type="submit" class="btn btn-md inline" id="voteBtn">Cast my vote!</button>
                    </div>
            </form>
        </div>
    @else
        <h2>Looks like some changed their mind...all the LunchOUT! options where removed!</h2>
    @endif


@endsection
