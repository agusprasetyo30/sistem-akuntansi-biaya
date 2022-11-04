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
								<div class="text-light">
									<div class="display-1 mb-5 font-weight-bold error-text">400</div>
									<h1 class="h3  mb-3 font-weight-bold">Address Not Found Error!</h1>
									<p class="h5 font-weight-normal mb-7 leading-normal">The address you have typed was incorrect! Please try with correct address.</p>
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
