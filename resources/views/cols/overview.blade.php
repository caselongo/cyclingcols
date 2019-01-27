@extends('layouts.app')

@section('content')

    <style type="text/css">
        .table {
            border-collapse: collapse !important;
        }

        .table td,
        .table th {
            background-color: #fff !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
            margin:10px;
        }
    </style>
    <div class="container" style="margin-top:40px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome to cyclingcols</div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <th>Height</th>
                                <th>Country</th>
                                <th>Done</th>
                                <th>Favorite</th>
                                <th>Todo</th>
                                <th>Ranking</th>
                            </tr>

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
                                <td>{{$col->pivot->Done ?? '-'}}</td>
                                <td>{{$col->pivot->Favorite ?? '-'}}</td>
                                <td>{{$col->pivot->ToDo ?? '-'}}</td>
                                <td>{{$col->pivot->Rating ?? '-'}}</td>
                            </tr>

                        @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
