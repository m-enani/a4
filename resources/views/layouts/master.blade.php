<!DOCTYPE html>
<html>
<head>

    <title>
        @yield("title", "Lunch Out!")
    </title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <!-- Google Fonts -->
    <link href='//fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>

    @stack('head')

</head>

<body>
    <div class="navBar navbar-fixed-top">
        <div class="navContainer">
            <ul>
                <li class="navBar"><a href="/">LunchOUT!</a></li>
                <li class="navBar" style="float:right"><a href="#">Sign up</a></li>
                <li class="navBar" style="float:right"><a href="#">Log in</a></li>
            </ul>
        </div>
    </div>
    <!-- <div id="header">
        <section>
            @yield('header')
        </section>
    <div> -->
    <div class="search">
        <section>
            @yield('search')
        </section>
    </div>
    <div class="container-fluid">
        <section>
            @yield('content')
        </section>
    </div>
    @stack('body')

</body>
</html>
