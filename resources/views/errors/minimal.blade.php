<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @vite('resources/js/app.css')
</head>
<body>

    <div class="container my-5 py-5">
        <div class="row">
            <div class="col-12">
                <section class="my-5 text-center">
                    <div class="d-flex justify-content-center pb-4">
                        <img style="width: 85px" src="{{ Vite::asset('resources/js/img/fuego-bites.png') }}">
                    </div>

                    <h1 class="display-1">@yield('code')</h1>
                    <h4 class="mb-4">@yield('message')</h4>


                    <a class="btn btn-primary" href="{{  app('router')->has('home') ? route('home') : url('/')  }}" role="button">
                       {{ __('Go back to the homepage') }}
                    </a>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
