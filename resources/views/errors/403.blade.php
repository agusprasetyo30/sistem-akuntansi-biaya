@extends('layouts.custom-app')

@section('styles')

@endsection

@section('class')

		<div class="bg-primary">

@endsection

@section('content')

            <div class="box">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>

		<div class="page">
			<div class="page-content">
				<div class="container text-center">
					<div class="row">
						<div class="col-md-12">
							<div class="">
								<div class="text-white">
									<div class="display-1 mb-5 font-weight-bold error-text">403</div>
									<h1 class="h3  mb-3 font-weight-bold">Sorry, Forbidden Error, Requested Page not found!</h1>
									<p class="h5 font-weight-normal mb-7 leading-normal">You may have mistyped the address or the page may have moved.</p>
									<a class="btn text-light border-light mb-5 ms-2 fs-18" href="{{url('/')}}">Back to Home</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


@endsection('content')

@section('scripts')

@endsection
