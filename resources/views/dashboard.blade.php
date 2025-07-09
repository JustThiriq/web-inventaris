@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome {{ auth()->user()?->name ?? 'User' }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($boxes as $box)
                            <div class="col-md-3">
                                <a href="{{ $box['link'] }}">

                                    <div class="small-box bg-{{ $box['color'] }}">
                                        <div class="inner">
                                            <h3 style="color:black!important">{{ $box['count'] }}</h3>
                                            <p style="color:black!important">{{ $box['text'] }}</p>
                                        </div>
                                        <div class="icon">
                                            <i class="{{ $box['icon'] }}"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
