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
				<li><a href="{{url('master/company')}}" class="slide-item"> Master Company</a></li>
				<li><a href="{{url('master/kategori-material')}}" class="slide-item"> Kategori Material</a></li>
				<li><a href="{{route('kategori_produk')}}" class="slide-item"> Kategori Produk</a></li>
				<li><a href="{{route('kategori_balans')}}" class="slide-item"> Kategori Balans</a></li>
				<li><a href="{{route('map_kategori_balans')}}" class="slide-item"> Mapping Kategori Balans</a></li>
				<li><a href="{{url('master/group-account')}}" class="slide-item"> Group Account VC</a></li>
				<li><a href="{{url('master/gl-account')}}" class="slide-item"> General Ledger VC</a></li>
				<li><a href="{{route('group_account_fc')}}" class="slide-item"> Group Account FC</a></li>
				<li><a href="{{route('gl_account_fc')}}" class="slide-item"> General Ledger FC</a></li>
				<li><a href="{{route('cost_center')}}" class="slide-item"> Cost Center</a></li>
				<li><a href="{{url('master/material')}}" class="slide-item"> Material</a></li>
				<li><a href="{{url('master/plant')}}" class="slide-item"> Plant</a></li>
				<li><a href="{{url('master/glos-cc')}}" class="slide-item"> Glos CC</a></li>
				<li><a href="{{url('master/tarif')}}" class="slide-item"> Tarif</a></li>
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
                <li><a href="{{route('consrate')}}" class="slide-item"> Consumption Rate</a></li>
				<li><a href="{{url('buku-besar/saldo-awal')}}" class="slide-item"> Saldo Awal</a></li>
				<li><a href="{{url('buku-besar/qty-renprod')}}" class="slide-item"> Kuantiti Rencana Produksi</a></li>
				<li><a href="{{url('buku-besar/qty-rendaan')}}" class="slide-item"> Kuantiti Rencana Pengadaan</a></li>
				<li><a href="{{url('buku-besar/price-rendaan')}}" class="slide-item"> Price Rencana Pengadaan</a></li>
				<li><a href="{{url('buku-besar/total-daan')}}" class="slide-item"> Total Pengadaan</a></li>
				<li><a href="{{url('buku-besar/zco')}}" class="slide-item"> ZCO</a></li>
				<li><a href="{{url('buku-besar/salr')}}" class="slide-item"> SALR</a></li>
				<li><a href="{{route('laba_rugi')}}" class="slide-item"> Laba Rugi</a></li>
				<li class="sub-slide">
					<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Pakai Jual</span><i class="sub-angle fe fe-chevron-right"></i></a>
					<ul class="sub-slide-menu">
						<li><a class="sub-slide-item" href="{{url('buku-besar/pakai-jual/pemakaian')}}">Pemakaian</a></li>
						<li><a class="sub-slide-item" href="{{url('buku-besar/pakai-jual/penjualan')}}">Penjualan</a></li>
					</ul>
				</li>
			</ul>
		</li>
        <li class="slide">
            <a class="side-menu__item" href="{{route('dasar_balans')}}">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"> <path d="M22 7.999a1 1 0 0 0-.516-.874l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5A1 1 0 0 0 22 7.999zm-9.977 3.855L5.06 7.965l6.917-3.822 6.964 3.859-6.918 3.852z"></path> <path d="M20.515 11.126 12 15.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"></path> <path d="M20.515 15.126 12 19.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"></path> </svg>
                <span class="side-menu__label">Balans</span>
            </a>
        </li>
		<li class="slide">
			<a class="side-menu__item" href="{{url('simulasi-proyeksi')}}">
				<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z"></path>
                </svg>
			<span class="side-menu__label">Simulasi Proyeksi</span></a>
		</li>
		<li class="slide">
			<a class="side-menu__item" href="{{url('kontrol-proyeksi')}}">
				<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.084 0 2 .916 2 2s-.916 2-2 2-2-.916-2-2 .916-2 2-2z"> </path> <path d="m2.845 16.136 1 1.73c.531.917 1.809 1.261 2.73.73l.529-.306A8.1 8.1 0 0 0 9 19.402V20c0 1.103.897 2 2 2h2c1.103 0 2-.897 2-2v-.598a8.132 8.132 0 0 0 1.896-1.111l.529.306c.923.53 2.198.188 2.731-.731l.999-1.729a2.001 2.001 0 0 0-.731-2.732l-.505-.292a7.718 7.718 0 0 0 0-2.224l.505-.292a2.002 2.002 0 0 0 .731-2.732l-.999-1.729c-.531-.92-1.808-1.265-2.731-.732l-.529.306A8.1 8.1 0 0 0 15 4.598V4c0-1.103-.897-2-2-2h-2c-1.103 0-2 .897-2 2v.598a8.132 8.132 0 0 0-1.896 1.111l-.529-.306c-.924-.531-2.2-.187-2.731.732l-.999 1.729a2.001 2.001 0 0 0 .731 2.732l.505.292a7.683 7.683 0 0 0 0 2.223l-.505.292a2.003 2.003 0 0 0-.731 2.733zm3.326-2.758A5.703 5.703 0 0 1 6 12c0-.462.058-.926.17-1.378a.999.999 0 0 0-.47-1.108l-1.123-.65.998-1.729 1.145.662a.997.997 0 0 0 1.188-.142 6.071 6.071 0 0 1 2.384-1.399A1 1 0 0 0 11 5.3V4h2v1.3a1 1 0 0 0 .708.956 6.083 6.083 0 0 1 2.384 1.399.999.999 0 0 0 1.188.142l1.144-.661 1 1.729-1.124.649a1 1 0 0 0-.47 1.108c.112.452.17.916.17 1.378 0 .461-.058.925-.171 1.378a1 1 0 0 0 .471 1.108l1.123.649-.998 1.729-1.145-.661a.996.996 0 0 0-1.188.142 6.071 6.071 0 0 1-2.384 1.399A1 1 0 0 0 13 18.7l.002 1.3H11v-1.3a1 1 0 0 0-.708-.956 6.083 6.083 0 0 1-2.384-1.399.992.992 0 0 0-1.188-.141l-1.144.662-1-1.729 1.124-.651a1 1 0 0 0 .471-1.108z"> </path></svg>
			<span class="side-menu__label">Kontrol Proyeksi</span></a>
		</li>
	</ul>
</aside>
<!--aside closed-->
