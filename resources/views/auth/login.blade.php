@extends('layouts.custom-app')

@section('styles')

@endsection

@section('class')

<div class="register1">

@endsection

@section('content')

<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-12">
                            <div class="row p-0 m-0">
                                <div class="col-lg-6 p-0">
                                    <div class="text-justified text-white p-5 register-1 overflow-hidden">
                                        <div class="custom-content">
                                            <div class="ms-4 mb-8 br-2">
                                                <img src="{{asset('assets/images/brand/logopipg.png')}}"
                                                    class="header-brand-img desktop-lgo" alt="Petrokimia logo" style="width:300px;height:70px;" class="center">
                                            </div>
                                            <div class="ms-3 text-center">
                                                <div class="fs-18 mb-5 font-weight-bold text-white">Sistem Proyeksi <p class="mb-1 font-weight-bold text-white">Akutansi Biaya <p class="mb-1 font-weight-bold text-white">PT. Petrokimia Gresik </div>
                                            </div>
                                            <div class="ms-4 br-6 mt-8">
                                                <img src="{{asset('assets/images/brand/logog20.png')}}"
                                                    class="header-brand-img desktop-lgo" alt="Petrokimia logo" style="width:300px;height:70px;" class="center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10 col-lg-6 p-0 mx-auto">
                                    <div class="bg-white text-dark br-7 br-tl-0 br-bl-0">
                                        <div class="card-body">
                                            <div class="text-center mt-3 mb-3">
                                            <img src="{{asset('assets/images/brand/logopg.png')}}"
                                                    class="header-brand-img desktop-lgo" alt="logo" style="width:120px;height:50px;">
                                                <!-- <h3 class="mb-2">Log In</h3> -->
                                                <p>
                                                <a href="javascript:void(0);" class="mb-2"><h5>Silahkan Login</h5></a>
                                            </div>
                                            <!-- Session Status -->
                                            {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

                                            <!-- Validation Errors -->
                                            {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}

                                            <form class="mt-6" method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="input-group mb-5">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-user"></i>
                                                    </div>
                                                    <input id="username" class="form-control" type="text"
                                                        name="username" :value="old('username')" required autofocus
                                                        placeholder="Username">
                                                </div>
                                                <div class="input-group mb-7">
                                                    <div class="input-group" id="Password-toggle">
                                                        <a href="" class="input-group-text">
                                                            <i class="fe fe-eye" aria-hidden="true"></i>
                                                        </a>
                                                        <input id="password" class="form-control" type="password"
                                                            name="password" required autocomplete="current-password"
                                                            placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="form-group text-center mb-6">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-lg w-100 br-7">Log In</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
