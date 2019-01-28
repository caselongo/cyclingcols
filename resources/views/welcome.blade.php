@extends('layouts.master')

@section('content')
<main role="main" class="bd-content p-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome to CyclingCols!</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   Keep track of the mountains you have climbed and still want to climb. Thic functionalicty heps you to:
                    <ul>
                        <li>Know which clibms need to be conquered</li>
                        <li>Which colls you have done so far (connect with strava)</li>
                        <li>Vacation planning</li>
                        <li>Discover new climbs near your (vacantion) location</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
