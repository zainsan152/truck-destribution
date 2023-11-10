@extends('layouts.app')
@section('title', 'Dashboard')
<style>
    a {
        color: black !important;
    }
</style>
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-tachometer-alt fa-3x mb-3"></i>
                            <h5 class="card-title">Tableau de bord</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <h5 class="card-title">Planning</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-truck-moving fa-3x mb-3"></i>
                            <h5 class="card-title">Gestion des OTs</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-file fa-3x mb-3"></i>
                            <h5 class="card-title">POD / IOD</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="{{route('distributions')}}">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <h5 class="card-title">Distribution</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="far fa-calendar-alt fa-3x mb-3"></i>
                            <h5 class="card-title">Arrivages</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-car fa-3x mb-3"></i>
                            <h5 class="card-title">Gestion des interventions</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="far fa-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Autre</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="#">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-cog fa-3x mb-3"></i>
                            <h5 class="card-title">Parametrage</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
@endsection
