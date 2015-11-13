<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>{{ $title or 'Codice' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,700,400italic&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset('assets/css/codice.css') !!}">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                    <span class="sr-only">Rozwiń menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href=""><img alt="Codice" src="{!! asset('assets/images/brand.png') !!}"></a>
            </div>
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="nav navbar-nav">
                    <li><a href=""><span class="fa fa-plus"></span> Dodaj</a></li>
                    <li><a href=""><span class="fa fa-tags"></span> Etykiety</a></li>
                    <li><a href=""><span class="fa fa-bell"></span> Przypomnienia</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="navbar-text nav-search-label"><span class="fa fa-search"></span></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user"></span> Sobak <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href=""><span class="fa fa-cog"></span> Ustawienia</a></li>
                            <li><a href=""><span class="fa fa-bar-chart"></span> Statystyki</a></li>
                            <li><a href=""><span class="fa fa-sign-out"></span> Wyloguj</a></li>
                        </ul>
                    </li>
                </ul>
                <form action="" method="post" class="navbar-form navbar-right hidden">
                    <div class="form-group">
                        <input type="search" name="search" class="form-control" placeholder="Szukaj" value="" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span></button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <script src="{!! asset('assets/js/jquery.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('assets/js/codice.js') !!}"></script>
</body>
</html>