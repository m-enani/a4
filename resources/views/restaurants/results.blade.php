@extends('layouts.master')

@section('title')
    Lunch Out
@endsection

@push('head')
    <link href='css/results.css' rel='stylesheet'>

    <!-- implement anchors -->
    <!-- <script type="text/javascript">
        $(function () {
            window.location = '#' + $anchor;
        });
    </script> -->
@endpush

@section('search')
    <div class="choices" id="choicesTable">
        <h3>Your Current Choices</h3>
        <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($choices as $choice)
              <tr>
                <td>{{$choice['name']}}</td>
                <td>{{$choice['type']}}</td>
                <td>
                    <form id="listForm{{$choice['id']}}" method='POST' action="/removechoice">

                        {{ csrf_field() }}

                        <input type='hidden' name='id'value='{{$choice['id']}}'>
                        <input type='hidden' name='userid' id="userid{{$choice['id']}}" value='{{$choice['user_id']}}'>
                        <button type='submit' class="btn btn-danger btn-xs" id="removeChoice">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </form>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          @php
            $id = '';
          @endphp
          @if (count($choices))
            <Label id="linkLbl">Ready? Enter an expiration time (mins) and gather votes!</label>
            <div id="share">
                <input type="number" class="form-control" name="time" id="timer" placeholder="Link expiration (default 60 minutes)" required="true">
                <button class="btn btn-success pull-right" id="linkBtn" onclick="generateLink()">Gather Votes!</button>
            <div>
            @php
                $id = $choice['id'];
            @endphp
          @endif
          <script>
                function generateLink() {
                    if(!document.getElementById("link")) {
                        var message = document.createElement("p");
                        message.setAttribute("id", "message");
                        message.setAttribute("style", "margin-top:5px;");
                        message.setAttribute("style", "margin-left:0px;");
                        message.innerHTML = "Here is you link. It has been copied to your clipboard!";
                        document.getElementById("choicesTable").appendChild(message);

                        var element = document.createElement("input");
                        element.setAttribute("value", window.location.origin  +  "/vote/number?" + document.getElementById('userid{{$id}}').value + "&" + Math.abs(document.getElementById('timer').value));
                        element.setAttribute("class","form-control");
                        element.setAttribute("id", "link")
                        document.getElementById("choicesTable").appendChild(element);
                        document.getElementById("link").select();
                        document.getElementById("linkBtn").innerHTML  = "Copied!";
                        document.execCommand("copy");



                    }
                }
          </script>
    </div>
@endsection

@section('content')

        <h2>Results for restaurants in {{$location}}</h2>

        @foreach($filteredRestaurants as $restaurant)
            <div class="row">
                <div class="col-md-4">
                    <img src='{{$restaurant['image']}}' alt='{{$restaurant['name']}}'>
                    <input type='hidden' name='address{{$restaurant['id']}}' value='{{$restaurant['address']}}'>
                </div>
                <form class="form-horizontal" id="choicesForm{{$restaurant['id']}}" method='POST'>

                    {{ csrf_field() }}

                    <div class="displayChoices col-md-8">
                        <input type='hidden' name='image{{$restaurant['id']}}' value='{{$restaurant['image']}}'>
                        <h4><span>{{$restaurant['name']}}</span><hr class='storeName'></h4>
                        <input type='hidden' name='name{{$restaurant['id']}}' value='{{$restaurant['name']}}'>
                        <h5>Address: <span>{{$restaurant['address']}}</span></h5>
                        <input type='hidden' name='address{{$restaurant['id']}}' value='{{$restaurant['address']}}'>
                        <h5>Phone: <span>{{$restaurant['phone']}}</span></h5>
                        <input type='hidden' name='phone{{$restaurant['id']}}' value='{{$restaurant['phone'] }}'>
                        <h5>Price: <span>{{$restaurant['price']}}</span></h5>
                        <input type='hidden' name='price{{$restaurant['id']}}' value='{{$restaurant['price']}}'>
                        <h5>Rating: <span>{{$restaurant['rating']}}/5</span></h5>
                        <input type='hidden' name='rating{{$restaurant['id']}}' value='{{$restaurant['rating']}}'>
                        <h5><a href="{{$restaurant['url']}}">Find out more!</a></h5>
                        <input type='hidden' name='url{{$restaurant['id']}}' value='{{$restaurant['url']}}'>
                        <input type='hidden' name='yelpType{{$restaurant['id']}}' value='{{$restaurant['yelpType']}}'>
                        <input type='hidden' name='id' value='{{$restaurant['id']}}'>

                        @if (isset($choices))
                            @foreach ($choices as $choice)
                                @if ($choice['name'] == $restaurant['name'])
                                    @php
                                        $exists = true
                                    @endphp
                                    @break
                                @else
                                    @php
                                        $exists = false
                                    @endphp
                                @endif
                            @endforeach
                        @endif

                        @if ($exists == true)
                            <button type="submit" class="btn btn-danger btn-md pull-right"  id="remove{{$restaurant['id']}}">Remove from list</button>
                        @else
                            <button type="submit" class="btn btn-primary btn-md pull-right" id="add{{$restaurant['id']}}">Add to list</button>
                        @endif

                        @php
                            $_SESSION['restaurant'] = $filteredRestaurants;
                            $_SESSION['choices'] = $choices;
                            $_SESSION['location'] = $location;
                        @endphp

                        <script type="text/javascript">

                            $(document).ready(function() {
                                $('#add{{$restaurant['id']}}').click(function(){
                                    $('#choicesForm{{$restaurant['id']}}').attr('action', '/add');
                                });
                                $('#remove{{$restaurant['id']}}').click(function(){
                                    $('#choicesForm{{$restaurant['id']}}').attr('action', '/remove');
                                });
                            });
                        </script>

                    </div>
                </form>
            </div>
            <hr class="divder">

        @endforeach

@endsection
