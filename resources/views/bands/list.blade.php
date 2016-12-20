@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center">
            <a href="/bands/create" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Add Band
            </a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>
                    {{-- Each query string checks if that column is being sorted so when clicked, the direction is reversed --}}
                    <a href="/bands?sort=name&sort_dir={{$appends['sort'] == 'name' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$bands->currentPage()}}">Band Name</a>
                </th>
                <th>
                    <a href="/bands?sort=website&sort_dir={{$appends['sort'] == 'website' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$bands->currentPage()}}">Website</a>
                </th>
                <th>
                    <a href="/bands?sort=start_date&sort_dir={{$appends['sort'] == 'start_date' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$bands->currentPage()}}">Founded</a>
                </th>
                <th>
                    <a href="/bands?sort=still_active&sort_dir={{$appends['sort'] == 'still_active' && $appends['sort_dir'] == 'asc' ? 'desc' : 'asc'}}&page={{$bands->currentPage()}}">Active</a>
                </th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bands as $band)
                <tr>
                    <td>
                        <a href="/bands/{{$band->id}}">{{$band->name}}</a>
                    </td>
                    <td>{{$band->website}}</td>
                    <td>{{$band->start_date}}</td>
                    <td>
                        <span class="label label-small label-{{$band->still_active ? 'success' : 'default'}}">{{$band->still_active ? 'Active' : 'Inactive'}}</span>
                    </td>
                    <td>
                        <a class="btn btn-warning" href="/bands/{{$band->id}}/edit">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <form action="/bands/{{$band->id}}" method="POST" class="inline" onsubmit="deleteBand()">
                            <button type="submit" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>

                            {{-- Persists existing sort data and/or current page --}}
                            <input type="hidden" name="sort" value="{{$appends['sort']}}" />
                            <input type="hidden" name="sort_dir" value="{{$appends['sort_dir']}}" />
                            <input type="hidden" name="page" value="{{$bands->currentPage()}}" />
                            <input type="hidden" name="per_page" value="{{$appends['per_page']}}" />

                            {{-- Spoof the DELETE method --}}
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
            {{$bands->appends($appends)->links()}}

            {{-- Changes the rows per page --}}
            <form action="/bands" method="GET" class="inline pull-right">

                {{--Existing sort data and current page --}}
                <input type="hidden" name="sort" value="{{$appends['sort']}}" />
                <input type="hidden" name="sort_dir" value="{{$appends['sort_dir']}}" />
                <input type="hidden" name="page" value="{{$bands->currentPage()}}" />

                {{-- Changes rows per page --}}
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
    <script>
        function deleteBand(){
            if(!confirm('Are you sure you want to delete this band?'))
                event.preventDefault();
        }
    </script>
@endsection