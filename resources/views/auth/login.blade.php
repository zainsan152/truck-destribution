@extends('layouts.assets')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

</head>

<body>
<!-- Navbar -->
@include('navbar')
<div class="container">


    <!-- Login Form -->
    <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
        <div class="card" style="width: 30rem;">
            <div class="card-body">
                <h5 class="card-title text-center">Login to Drive backoffice</h5>
                <form method="post" action="{{ url('/login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="login" class="col-sm-3 col-form-label">Login:</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                   class="form-control @error('email') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password:</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" placeholder="Password"
                                   class="form-control @error('password') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                            <label class="custom-control-label" for="customSwitch1">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign-in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</body>

</html>
