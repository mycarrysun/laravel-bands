@extends('layouts.app')

@section('content')
    <div class="container text-center detail-view">
        <h2>Band Detail</h2>
        <h4>{{$band->name}} </h4>

        <span class="label label-{{$band->active ? 'success' : 'default'}}">{{$band->active ? 'Active' : 'Inactive'}}</span>

        <div class="row">
            <h5>Founded</h5>
            <span>{{$band->start_date}}</span>
        </div>

        <div class="row">
            <h5>Website</h5>
            <a href="{{$band->website}}" target="_blank">{{$band->website}}</a>
        </div>

        <div class="row">
            <h5>Albums</h5>
            <div class="albums-list">
                @foreach($band->albums as $album)
                    <div class="album">
                        <a href="/albums/{{$album->id}}">{{$album->name}}</a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row">
            <a href="/bands/{{$band->id}}/edit"
               class="btn btn-warning"
               >
                <i class="glyphicon glyphicon-pencil"></i> Edit
            </a>
            <form action="/bands/{{$band->id}}" method="POST" class="inline">
                <button type="submit" class="btn btn-danger">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                </button>
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
        </div>

    </div>
@endsection