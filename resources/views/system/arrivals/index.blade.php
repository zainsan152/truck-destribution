@extends('adminlte::page')
@section('title', 'Arrivals')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Toasts', true)
<style>
    .distribution-row td {
        text-align: center;
    }

    .show-distribution-details {
        cursor: pointer;
    }

    #arrivals-table {
        width: 100% !important;
    }

    #lines-table {
        width: 100% !important;
    }

    .edit-line-button {
        color: dodgerblue;
        cursor: pointer;
    }

    .delete-line-button {
        color: red;
        cursor: pointer;
    }

    #distributionModal {
        overflow-y:scroll;
    }

</style>
@vite(['resources/js/app.js'])
@section('content')
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ajouter les distributions</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-6">
                            <form action="{{ route('arrivals.import') }}" method="POST"
                                  enctype="multipart/form-data" class="form-inline">
                                @csrf
                                <div class="form-group">
                                    <label for="file">Ajouter un fichier</label>
                                    <input type="file" name="file" id="file" class="form-control-file">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"
                                        id="fileBtn">Importer arrivage
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">Liste des Arrivages previsionnels</h3>
                    </div>
                    <div class="card-body">
                        <table id="arrivals-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Dossier tegic</th>
                                <th>Dossier client</th>
                                <th>Shipping Compagnie</th>
                                <th>POD</th>
                                <th>ETA</th>
                                <th>ATA</th>
                                <th>Lieu de chargement</th>
                                <th>Lieu de déchargement</th>
                                <th>Lieu de Restitution</th>
                                <th>Date BAE Prévisionnelle</th>
                                <th>Date magasinage</th>
                                <th>Date Surestaries</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($arrivals as $arrival)
                                    <tr id="arrivalRow-{{$arrival->id }}"
                                        class="arrival-row">
                                        <td>{{$arrival->id}}</td>
                                        <td>{{$arrival->clients->name_client}}</td>
                                        <td>{{$arrival->arrival_types->type}}</td>
                                        <td>{{$arrival->dossier_tegic}}</td>
                                        <td>{{$arrival->dossier_client}}</td>
                                        <td>{{$arrival->shipping_compagnie}}</td>
                                        <td>{{$arrival->cities->city}}</td>
                                        <td>{{$arrival->eta}}</td>
                                        <td>{{$arrival->ata}}</td>
                                        <td>{{$arrival->lieu_de_chargement}}</td>
                                        <td>{{$arrival->lieu_de_dechargement}}</td>
                                        <td>{{$arrival->lieu_de_restitution}}</td>
                                        <td>{{$arrival->date_bae_Previsionnelle}}</td>
                                        <td>{{$arrival->date_magasinage}}</td>
                                        <td>{{$arrival->date_surestaries}}</td>
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
        var table = $('#arrivals-table').DataTable({
            responsive: true,
        });
    </script>
@endsection
