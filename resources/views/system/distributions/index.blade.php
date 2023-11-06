@extends('layouts.app')
@section('title', 'Distributions')
@section('plugins.Datatables', true)
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ajoutre une liste de distribution</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('distributions.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Add a file</label>
                                <input type="file" name="file" id="file" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-left: auto;">Import
                                Distributions
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">List of distributions</h3>
                    </div>
                    <div class="card-body">
                        <table id="distributions-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Typ</th>
                                <th>Client</th>
                                <th>Ca</th>
                                <th>N Ship</th>
                                <th>Ref Shipmen</th>
                                <th>Date c</th>
                                <th>Qty</th>
                                <th>Vlm</th>
                                <th>Nb de</th>
                                <th>Nb j pr</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($distributions as $distribution)
                                <tr id="distributionRow-{{$distribution->id_client}}" class="distribution-row">
                                    <td>{{$distribution->code_distribution}}</td>
                                    <td></td>
                                    <td>{{$distribution->client->name_client}}</td>
                                    <td>{{$distribution->truckCategory->truck_category}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$distribution->date_order}}</td>
                                    <td>{{$distribution->qty}}</td>
                                    <td>{{number_format($distribution->volume)}}</td>
                                    <td>{{$distribution->nbr_delivery_points}}</td>
                                    <td>{{$distribution->nbr_expected_days}}</td>
                                    <td><i class="fas fa-info-circle"></i></td>
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
        var table = $('#distributions-table').DataTable();
    </script>
@endsection
