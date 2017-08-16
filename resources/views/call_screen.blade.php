@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h2>Senha: <span id="called"></span></h2>
                <h3>Guichê: </h3>
            </div>
            <div class="col-md-3">
                <h2>Últimas chamadas</h2>
                <p id="already_called"></p>
            </div>
        </div>
    </div>
@endsection

@section('other-js')
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('32b9b3ee6c8dfa9040f9', {
            cluster: 'us2',
            encrypted: true
        });

        var channel = pusher.subscribe('on_call');
        channel.bind('App\\Events\\ShowPasswordCalled', function(data) {
            $('#called').text(data.called);
            var audio = new Audio('{{ asset("audio/call_alert.mp3") }}');
            audio.play();
            $('#already_called').text(data.already_called);
        });
    </script>
@endsection