{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


@extends('layouts.header')

@section('content')
    <div class="mb-4">
        <?php if (session('status')): ?>
        <div class="alert alert-success">
            <?= session('status') ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="auth-main v2">
        <div class="bg-overlay bg-dark"></div>
        <div class="auth-wrapper">
            <div class="auth-sidecontent">
                <div class="auth-sidefooter">
                    <img src="{{asset('assets/images/logo-sst.svg') }}" class="img-brand img-fluid" alt="image" />
                    <hr class="mb-3 mt-4" />
                    <div class="row">
                        <div class="col my-1">
                            <p class="m-0">Developper par ♥ par <a href="#"
                                    target="_blank"> Sutura Digitale</a></p>
                        </div>
                        {{-- <div class="col-auto my-1">
                            <ul class="list-inline footer-link mb-0">
                                <li class="list-inline-item"><a href="#">Home</a></li>
                                <li class="list-inline-item"><a href="#"
                                        target="_blank">Documentation</a></li>
                                <li class="list-inline-item"><a href="#"
                                        target="_blank">Support</a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="auth-form">
                <div class="card my-5 mx-3">
                    <div class="card-body">
                        {{-- <p class="mb-3">Je suis nouveau ici <span class="ms-1">|</span> --}}
                             {{-- <a href="{{ route('register') }}" class="link-primary ms-1">Créer un compte</a> --}}
                        </p>
                        <h4 class="f-w-500 mb-1">Connectez-vous</h4>


                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" required autocomplete="email" autofocus id="floatingInput"
                                    placeholder="Entrer votre adresse email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{-- <strong>{{ $message=$errors->get('email') }}</strong> --}}
                                    </span>
                                @enderror
                                {{-- <input type="email" class="form-control" id="floatingInput" placeholder="Email Address"> --}}
                            </div>
                            <div class="mb-3">
                                <input type="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" id="floatingInput1" placeholder="Entrer votre mot de passe">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message = $errors->get('password') }}</strong>
                                    </span>
                                @enderror
                                {{-- <input type="password" class="form-control" id="floatingInput1" placeholder="Password"> --}}
                            </div>
                            {{-- <div class="block mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir') }}</span>
                                </label>
                            </div> --}}
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                @if (Route::has('password.request'))
                                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        href="{{ route('password.request') }}">
                                        {{ __('J\'ai oublié mon mot de passe ?') }}
                                    </a>
                                @endif

                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Entrer</button>

                                {{-- <button type="button" class="btn btn-primary"> {{ __('Log in') }}</button> --}}
                            </div>
                        </form>
                        {{-- <div class="saprator my-3">
                            <span>Or continue with</span>
                        </div> --}}
                        {{-- <div class="text-center">
                            <ul class="list-inline mx-auto mt-3 mb-0">
                                <li class="list-inline-item">
                                    <a href="https://www.facebook.com/" class="avtar avtar-s rounded-circle bg-facebook"
                                        target="_blank">
                                        <i class="fab fa-facebook-f text-white"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="https://twitter.com/" class="avtar avtar-s rounded-circle bg-twitter"
                                        target="_blank">
                                        <i class="fab fa-twitter text-white"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="https://myaccount.google.com/"
                                        class="avtar avtar-s rounded-circle bg-googleplus" target="_blank">
                                        <i class="fab fa-google text-white"></i>
                                    </a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
