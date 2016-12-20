@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="/bands{{ isset($band) ? '/'.$band->id : '' }}" method="POST">
            <h2 class="text-center">{{ isset($band) ? 'Edit '.$band->name : 'New Band' }}</h2>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="name">Band Name</label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               value="{{$band->name or ''}}"
                        />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url"
                               name="website"
                               id="website"
                               class="form-control"
                               value="{{$band->website or ''}}"
                        />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                            <input type="text"
                                   name="start_date"
                                   id="start_date"
                                   class="form-control datepicker"
                                   value="{{$band->start_date or ''}}"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="still_active"
                                   id="still_active"
                                   value="true"
                                   {{isset($band) && $band->still_active ? 'checked' : ''}}
                            /> Active?
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit"
                            class="btn btn-primary"
                            >Save</button>
                    <a href="/bands"
                       class="btn btn-default"
                       >Cancel</a>
                </div>
            </div>

            {{ isset($band) ? method_field('PUT') : '' }}
            {{ csrf_field() }}
        </form>
    </div>
@endsection