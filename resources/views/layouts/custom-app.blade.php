<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="Azea – Laravel Admin & Dashboard Template" name="description">
		<meta content="Spruko Private Limited" name="author">
		<meta name="keywords" content="laravel ui admin template, laravel admin template, laravel dashboard template,laravel ui template, laravel ui, livewire, laravel, laravel admin panel, laravel admin panel template, laravel blade, laravel bootstrap5, bootstrap admin template, admin, dashboard, admin template">

		<!-- Title -->
		<title>Akuntansi Biaya</title>

        @include('components.custom-styles')

	</head>

	<body class="h-100vh error-bg">

	@yield('class')

	    <!-- GLOBAL-LOADER -->
		<div id="global-loader">
			<img src="{{asset('assets/images/svgs/loader.svg')}}" alt="loader">
		</div>
		<!-- End GLOBAL-LOADER -->

            @yield('content')

        @include('components.custom-scripts')

	</div>

	</body>
</html>
