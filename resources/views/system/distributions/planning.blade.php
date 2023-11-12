@extends('layouts.app')
@section('title', 'Planning')
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 5px;
    }

    a {
        color: black !important;
    }
</style>
@section('content')
    <div class="container">
        <div class="row pt-5">
            @foreach($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <i class="fas fa-truck-moving fa-3x mb-3"></i>
                            <h5 class="card-title">Available Trucks ({{$category->truck_category}}) : {{$category->truck_count}}</h5>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">Planning</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th></th>
                                @foreach ($distributions as $distribution)
                                    <th>
                                        Distribution {{ str_pad($distribution->code_distribution, 5, '0', STR_PAD_LEFT) }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($drivers as $driver)
                                <tr>
                                    <td><b>{{ $driver->firstname }} {{ $driver->lastname }}</b></td>
                                    @foreach ($distributions as $distribution)
                                        <td>
                                            @foreach ($driverMappings as $mapping)
                                                @if ($mapping->id_driver === $driver->id_driver && $mapping->id_distribution_header === $distribution->id_distribution_header)
                                                    {{ $mapping->flag_status }}
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $("TD:contains('pending')").css("background-color", "skyblue");
            $("TD:contains('ongoing')").css("background-color", "yellow");
            $("TD:contains('done')").css("background-color", "lightgreen");
        });
    </script>
@endsection

