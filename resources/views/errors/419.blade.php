@extends('layouts.app')

@section('custom-page')
    <div class="notfoundpanel">
        <h1>419!</h1>
        <h3>La página ha expirado debido a inactividad.</h3>
        <p>Por favor, actualice y pruebe de nuevo.</p>
        <button type="button" class="btn btn-sm bt-primary" 
        		onclick="window.location.assign(window.location.href)">
        	Actualizar
        </button>
    </div>
@stop