@extends('layouts.app')

@section('content')
    <div class="container text-center detail-view">
        <h2>Album Detail</h2>
        <h4>{{$album->name}}</h4>
        <h5><a href="/bands/{{$album->band->id}}">{{$album->band->name}}</a></h5>

        <div class="row">
            <h5>Producer</h5>
            <span>{{$album->producer}}</span>
        </div>

        <div class="row">
            <h5>Record Label</h5>
            <span>{{$album->label}}</span>
        </div>

        <div class="row">
            <div class="col-md-2 col-md-offset-4 col-sm-4 col-sm-offset-2">
                <h5>Recorded Date</h5>
                <span>{{$album->recorded_date}}</span>
            </div>
            <div class="col-md-2 col-sm-4">
                <h5>Release Date</h5>
                <span>{{$album->release_date}}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 col-md-offset-4 col-sm-4 col-sm-offset-2">
                <h5># Tracks</h5>
                <span>{{$album->number_of_tracks}}</span>
            </div>
            <div class="col-md-2 col-sm-4">
                <h5>Genre</h5>
                <span>{{$album->release_date}}</span>
            </div>
        </div>

        <div class="row">
            <a href="/albums/{{$album->id}}/edit"
               class="btn btn-warning"
            >
                <i class="glyphicon glyphicon-pencil"></i> Edit
            </a>
            <form action="/albums/{{$album->id}}" method="POST" class="inline">
                <button type="submit" class="btn btn-danger">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                </button>
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
        </div>

    </div>
@endsection