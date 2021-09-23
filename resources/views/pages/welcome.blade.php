@extends('layouts.master')

@section('content')
<main role="main" class="bd-content p-3 font-weight-light">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-sm">
                <div class="card-body px-4 py-3">
					<div class="text-center mb-3">
						<h4 class="font-weight-light">Thanks for joining CyclingCols!</h4>
					</div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					<p>
					
                    You can now keep track of your own list of climbed cols.
					
                    <ul>
                        <li>From your <a href="/athlete">Dashboard</a> you can initialize and update your cols list from your Strava rides.</li>
						<li>You can also manually add cols to your list by just claiming them from their col pages.</li>
                        <li>Visit your <a href="/athlete">Dashboard</a> for an overview of when you climbed which cols as well as the amount of cols you climbed per year and per country.</li>
						<li>Follow other athletes and compare your totals with other athletes in the <a href="/athletes">All Athletes</a> page.</li>
                    </ul>

					Enjoy this functionality and good luck climbing more cols!
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
