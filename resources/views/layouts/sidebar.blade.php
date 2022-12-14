<!--aside open-->
<aside class="app-sidebar">
	<div class="app-sidebar__logo">
		<a class="header-brand" href="{{url('dashboard')}}">
			<img src="{{asset('assets/images/logo/pg_logo_header.png')}}" class="header-brand-img desktop-lgo" alt="Azea logo">
			<img src="{{asset('assets/images/brand/logo1.png')}}" class="header-brand-img dark-logo" alt="Azea logo">
			<img src="{{asset('assets/images/logo/Petrokimia_Gresik_logo.png')}}" class="header-brand-img mobile-logo" alt="Azea logo">
			<img src="{{asset('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="Azea logo">
		</a>
	</div>
	<ul class="side-menu app-sidebar3">
		<li class="side-item side-item-category">Main</li>
		<li class="slide">
			<a class="side-menu__item"  href="{{url('dashboard')}}">
				<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z"/></svg>
			<span class="side-menu__label">Dashboard</span></a>
		</li>
		<li class="side-item side-item-category">Components</li>
		<li class="slide">
			<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
				<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 17V7c0-2.168-3.663-4-8-4S4 4.832 4 7v10c0 2.168 3.663 4 8 4s8-1.832 8-4zM12 5c3.691 0 5.931 1.507 6 1.994C17.931 7.493 15.691 9 12 9S6.069 7.493 6 7.006C6.069 6.507 8.309 5 12 5zM6 9.607C7.479 10.454 9.637 11 12 11s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2V9.607zM6 17v-2.393C7.479 15.454 9.637 16 12 16s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2z"/></svg>
			<span class="side-menu__label">Master</span><i class="angle fe fe-chevron-right"></i></a>
			<ul class="slide-menu">
				<li><a href="{{url('master/kategori-material')}}" class="slide-item"> Kategori Material</a></li>
				<li><a href="{{url('master/group-account')}}" class="slide-item"> Group Account</a></li>
				<li><a href="{{url('master/material')}}" class="slide-item"> Material</a></li>
				<li><a href="{{url('master/plant')}}" class="slide-item"> Plant</a></li>
                <li><a href="{{route('asumsi_umum')}}" class="slide-item"> Asumsi Umum</a></li>
                <li><a href="{{route('kurs')}}" class="slide-item"> Kurs</a></li>
                <li><a href="{{route('regions')}}" class="slide-item"> Region</a></li>
                <li><a href="{{route('role')}}" class="slide-item"> Role</a></li>
                <li><a href="{{route('user')}}" class="slide-item"> Users</a></li>
			</ul>
		</li>
		<li class="slide">
			<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
				<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM9 9H5V5h4v4zm11-6h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zm-1 6h-4V5h4v4zm-9 4H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6H5v-4h4v4zm8-6c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"></path></svg>
			<span class="side-menu__label">Buku Besar</span><i class="angle fe fe-chevron-right"></i></a>
			<ul class="slide-menu">
{{--                <li><a href="{{route('asumsi_umum')}}" class="slide-item"> Asumsi Umum</a></li>--}}
{{--                <li><a href="{{route('cost_center')}}" class="slide-item"> Cost Center</a></li>--}}
                <li><a href="{{route('consrate')}}" class="slide-item"> Consumption Rate</a></li>
				<li><a href="{{url('buku-besar/saldo-awal')}}" class="slide-item"> Saldo Awal</a></li>
				<li><a href="{{url('buku-besar/qty-renprod')}}" class="slide-item"> Kuantiti Rencana Produksi</a></li>
				<li><a href="{{url('buku-besar/qty-rendaan')}}" class="slide-item"> Kuantiti Rencana Pengadaan</a></li>
				<li><a href="{{url('buku-besar/price-rendaan')}}" class="slide-item"> Price Rencana Pengadaan</a></li>
				<li><a href="{{url('buku-besar/total-daan')}}" class="slide-item"> Total Pengadaan</a></li>
				<!-- <li><a href="#" class="slide-item"> ZCO</a></li>
				<li><a href="#" class="slide-item"> SALR</a></li>
				<li><a href="#" class="slide-item"> Laba Rugi</a></li>
				<li><a href="#" class="slide-item"> Pakai Jual</a></li>
				<li><a href="#" class="slide-item"> Balans</a></li> -->
			</ul>
		</li>
		<li class="slide">
			<a class="side-menu__item"  href="#">
				<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z"></path></svg>
			<span class="side-menu__label">Simulasi Proyeksi</span></a>
		</li>
	</ul>
</aside>
<!--aside closed-->
