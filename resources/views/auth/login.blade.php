@extends('layouts.assets')
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <h5 class="card-title text-center">Connexion au back-office Drive</h5>
                <form method="post" action="{{ url('/login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="login" class="col-sm-3 col-form-label">Identifiant:</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Identifiant"
                                   class="form-control @error('email') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password:</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" placeholder="Mot de passe"
                                   class="form-control @error('password') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                            <label class="custom-control-label" for="remember"> Se souvenir de moi</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</body>

</html>

<script>
    $(document).ready(function(){
        $('#remember').change(function() {
            if($(this).is(':checked')) {
                // Toggle is on
                $(this).val(true);
            } else {
                // Toggle is off
                $(this).val(false);
            }
        });
    });
</script>
