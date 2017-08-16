@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1>Pedir uma senha</h1>
        <form method="post" action="{{ url('/request_password') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="checkbox" name="type" id="type" value="on">
                <label for="type">Atendimento preferencial?</label>
            </div>
            <div class="form-group">
                <label for="types">Tipo de atendimento:</label>
                <select class="form-control" id="types" name="attendance_type">
                    @foreach($at as $a)
                        <option>{{ $a->type_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-default">Pedir senha</button>
        </form>
        @if(Session::has('message'))
            <h2>{{ Session::get('message') }}</h2>
            {!! QrCode::size(200)->generate(Session::get('password')); !!}
        @endif
    </div>
@endsection