@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center">
            <a href="/albums/create" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Add Album
            </a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>
                    {{-- Each query string checks if that column is being sorted so when clicked, the direction is reversed --}}
                    <a href="/albums?sort=name&sort_dir={{$appends['sort'] == 'name' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">Album
                                                                                                                                                                          Name</a>
                </th>
                <th>
                    <a href="/albums?sort=recorded_date&sort_dir={{$appends['sort'] == 'recorded_date' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">Recorded</a>
                </th>
                <th>
                    <a href="/albums?sort=release_date&sort_dir={{$appends['sort'] == 'release_date' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">Released</a>
                </th>
                <th>
                    <a href="/albums?sort=number_of_tracks&sort_dir={{$appends['sort'] == 'number_of_tracks' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">#
                                                                                                                                                                                                  Tracks</a>
                </th>
                <th>
                    <a href="/albums?sort=label&sort_dir={{$appends['sort'] == 'label' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">Record
                                                                                                                                                                            Label</a>
                </th>
                <th>
                    <a href="/albums?sort=producer&sort_dir={{$appends['sort'] == 'producer' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">Producer</a>
                </th>
                <th>
                    <a href="/albums?sort=genre&sort_dir={{$appends['sort'] == 'genre' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$albums->currentPage()}}">Genre</a>
                </th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($albums as $album)
                <tr>
                    <td>
                        <a href="/albums/{{$album->id}}">{{$album->name}}</a>
                    </td>
                    <td>{{$album->recorded_date}}</td>
                    <td>{{$album->release_date}}</td>
                    <td>{{$album->number_of_tracks}}</td>
                    <td>{{$album->label}}</td>
                    <td>{{$album->producer}}</td>
                    <td>{{$album->genre}}</td>
                    <td>
                        <a class="btn btn-warning" href="/albums/{{$album->id}}/edit">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <form action="/albums/{{$album->id}}" method="POST" class="inline" onsubmit="deleteAlbum()">
                            <button type="submit" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                            {{-- Spoof the delete method --}}
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination-container">
            {{-- Display pagination with links that have query data sent back from previous request --}}
            {{$albums->appends($appends)->links()}}

            {{-- Changes the rows per page and filters by band --}}
            <div class="pull-right">
                <form action="/albums" method="GET" class="inline pull-right">
                    {{-- Existing sort data --}}
                    {{-- We leave out current page in case that page does not exist when a band is selected --}}
                    <input type="hidden" name="sort" value="{{$appends['sort']}}"/>
                    <input type="hidden" name="sort_dir" value="{{$appends['sort_dir']}}"/>

                    {{-- Filters by the selected band --}}
                    <div class="form-group">
                        <label for="band_id">Search by Band</label>

                        {{-- If we are filtered by a band, we add a "Reset" button the user can click --}}
                        @if(isset($appends['band_id']) && $appends['band_id'] > 0)

                        {{-- We remove the band id from the $appends (temporarily) so we can build the query --}}
                        @php $band_id = $appends['band_id']; unset($appends['band_id']); @endphp

                        <a href="/albums?{{http_build_query($appends)}}">Reset</a>

                        {{-- We reset the band_id back to what it was for the rest of the template --}}
                        @php $appends['band_id'] = $band_id; @endphp

                        @endif

                        <select id="band_id" name="band_id"
                                class="form-control"
                                onchange="this.form.submit()"
                        >
                            <option value="">None</option>
                            @foreach($bands as $band)
                                <option value="{{$band->id}}" {{$appends['band_id'] == $band->id ? 'selected' : ''}}>{{$band->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Changes the rows per page --}}
                    <div class="form-group">
                        <label for="per_page">Per Page</label>
                        <select id="per_page" name="per_page"
                                class="form-control"
                                onchange="this.form.submit()"
                        >
                            <option {{$appends['per_page'] == 5 ? 'selected' : ''}}>5</option>
                            <option {{$appends['per_page'] == 10 ? 'selected' : ''}}>10</option>
                            <option {{$appends['per_page'] == 15 ? 'selected' : ''}}>15</option>
                            <option {{$appends['per_page'] == 25 ? 'selected' : ''}}>25</option>
                            <option {{$appends['per_page'] == 50 ? 'selected' : ''}}>50</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function deleteAlbum(){
            if(!confirm('Are you sure you want to delete this album?'))
                event.preventDefault();
        }
    </script>
@endsection