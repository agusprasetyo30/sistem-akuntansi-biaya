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
                        <div class="col-xl-7 col-lg-12">
                            <div class="row p-0 m-0">
                                <div class="col-lg-6 p-0">
                                    <div class="text-justified text-white p-5 register-1 overflow-hidden">
                                        <div class="custom-content">
                                            <div class="mb-5 br-7">
                                                <img src="{{asset('assets/images/brand/logo1.png')}}"
                                                    class="header-brand-img desktop-lgo" alt="Azea logo">
                                            </div>
                                            <div class="ms-5">
                                                <div class="fs-18 mb-6 font-weight-bold text-white">Welcome Back To
                                                    Azea !</div>
                                                <div class="mb-6 text-white-50">
                                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                                                    Exercitationem et esse in velit deleniti facilis quo!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-6 p-0 mx-auto">
                                    <div class="bg-white text-dark br-7 br-tl-0 br-bl-0">
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <h1 class="mb-2">Log In</h1>
                                                <a href="javascript:void(0);" class="">Hello There !</a>
                                            </div>
                                            <!-- Session Status -->
                                            {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

                                            <!-- Validation Errors -->
                                            {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}

                                            <form class="mt-5" method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="input-group mb-4">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-user"></i>
                                                    </div>
                                                    <input id="username" class="form-control" type="text"
                                                        name="username" :value="old('username')" required autofocus
                                                        placeholder="Username">
                                                </div>
                                                <div class="input-group mb-6">
                                                    <div class="input-group" id="Password-toggle">
                                                        <a href="" class="input-group-text">
                                                            <i class="fe fe-eye" aria-hidden="true"></i>
                                                        </a>
                                                        <input id="password" class="form-control" type="password"
                                                            name="password" required autocomplete="current-password"
                                                            placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="form-group text-center mb-5">
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
