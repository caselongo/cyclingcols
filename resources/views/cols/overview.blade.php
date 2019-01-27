@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top:40px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome to cyclingcols</div>

                    <div class="card-body">

                        <table style="border:1px;solid;grey;" class="table">
                            <tr>
                                <th>Name</th>
                                <th>Height</th>
                                <th>Country</th>
                                <th>Done</th>
                                <th>Favorite</th>
                                <th>Todo</th>
                                <th>Ranking</th>
                            </tr>
                        </table>
                        @foreach($cols as $col)
                            <tr>
                                <td>
                                    {{$col->Col}}
                                </td>
                                <td>
                                    {{$col->Height}}
                                </td>
                                <td>
                                    {{$col->Country1}}
                                </td>
                                <td>{{$col->pivot->done}}</td>
                                <td>{{$col->pivot->favorite}}</td>
                                <td>{{$col->pivot->todo}}</td>
                                <td>{{$col->pivot->ranking}}</td>
                            </tr>

                            @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
