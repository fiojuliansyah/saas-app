@extends('layouts.guest')


@section('content')
    <div id="main-wrapper">
        <div class="unix-login">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="login-content card">
                            <div class="text-center m-b-20">
                                <img src="/assets/images/gihud.png" alt="" class="img-fluid text-center" width="30%">
                                <h4>Sign In</h4>
                                <p class="text-muted">Enter your email and password to access admin panel</p>
                            </div>
                            <div class="login-form">
                                <form id="sign-form" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label>Email address</label>
                                        <input type="email" name="email" class="form-control" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Remember Me
                                        </label>
                                        <label class="pull-right">
                                            <a href="{{ route('password.request') }}">Forgotten Password?</a>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button>
                                    <div class="register-link m-t-15 text-center">
                                        <p>Don't have account ? <a href="{{ route('register') }}"> Sign Up Here</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
