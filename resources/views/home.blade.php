@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center">
                <h2>Senhas a chamar</h2>
                <hr>
                <div class="passwords-not-called">
                    {{--<h3 style="display: inline-block;">N-05 &bullet;</h3>--}}
                    {{--<h3 style="display: inline-block;">N-07 &bullet;</h3>--}}
                    {{--<h3 style="display: inline-block;">P-05 </h3>--}}
                    <h3 id="msg"></h3>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <h2>Realizar atendimento</h2>
                <hr>

                @if(Session::has('message'))
                    <p class="alert alert-danger">{{ Session::get('message') }}</p>
                @endif

                @if(Session::has('button'))
                    <a style="display: block;" href="{{ url('/call_again') }}">{{ Session::get('button') }}</a>
                    <a style="display: block;" href="{{ url('/end') }}">Finalizar atendimento</a>
                @else
                    <a href="{{ url('/call') }}">Chamar senha</a>
                @endif

            </div>
            <div class="col-md-4 text-center">
                <h2>Informações</h2>

            </div>
        </div>
    </div>
@endsection

@section('other-js')
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('32b9b3ee6c8dfa9040f9', {
            cluster: 'us2',
            encrypted: true
        });

        var channel = pusher.subscribe('passwords');
        channel.bind('App\\Events\\UpdateNextPasswords', function(data) {
            $('#msg').text(data.passwords);
        });
    </script>
@endsection
