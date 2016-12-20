@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="/albums{{ isset($album) ? '/'.$album->id : '' }}" method="POST">
            <h2 class="text-center">{{ isset($album) ? 'Edit '.$album->name : 'New Album' }}</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Album Name<span class="required">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               value="{{$album->name or ''}}"
                               required
                        />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="band_id">Band</label>
                        <select name="band_id"
                                id="band_id"
                                class="form-control"
                                required
                        >
                            @foreach($bands as $band)
                                <option value="{{$band->id}}" {{isset($album) && $album->band_id === $band->id ? 'selected' : ''}}>{{$band->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="label">Record Label</label>
                        <input type="text"
                               name="label"
                               id="label"
                               class="form-control"
                               value="{{$album->label or ''}}"
                        />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="producer">Producer</label>
                        <input type="text"
                               name="producer"
                               id="producer"
                               class="form-control"
                               value="{{$album->producer or ''}}"
                        />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="recorded_date">Recorded Date</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                            <input type="text"
                                   name="recorded_date"
                                   id="recorded_date"
                                   class="form-control datepicker"
                                   value="{{$album->recorded_date or ''}}"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="release_date">Release Date</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                            <input type="text"
                                   name="release_date"
                                   id="release_date"
                                   class="form-control datepicker"
                                   value="{{$album->release_date or ''}}"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="number_of_tracks"># Tracks</label>
                        <input type="number"
                               name="number_of_tracks"
                               id="number_of_tracks"
                               class="form-control"
                               value="{{$album->number_of_tracks or ''}}"
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <input type="text"
                               name="genre"
                               id="genre"
                               class="form-control"
                               value="{{$album->genre or ''}}"
                        />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit"
                            class="btn btn-primary"
                    >Save</button>
                    <a href="/albums"
                       class="btn btn-default"
                    >Cancel</a>
                </div>
            </div>

            {{ isset($album) ? method_field('PUT') : '' }}
            {{ csrf_field() }}
        </form>
    </div>
@endsection