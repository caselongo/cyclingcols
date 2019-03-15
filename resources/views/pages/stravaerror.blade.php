@extends('layouts.master')

@section('content')
<main role="main" class="bd-content p-3 font-weight-light">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4 text-center">
            <div class="card">
                <div class="card-header">Error while connecting to Strava</div>
                <div class="card-body">
                    Please try again later.
					<div class="p-1">
						<a class="btn btn-primary" href="/athlete">
							Close
                        </a>
					</div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection