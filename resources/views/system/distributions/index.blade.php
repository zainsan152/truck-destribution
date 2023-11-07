@extends('layouts.app')
@section('title', 'Distributions')
@section('plugins.Datatables', true)
<style>
    .distribution-row td {
        text-align: center;
    }

    .show-distribution-details {
        cursor: pointer;
    }
</style>
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
                        <table id="distributions-table" class="table table-bordered table-striped">
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
                                    <td>Ship00{{$distribution->code_distribution}}</td>
                                    <td>{{$distribution->distributionType->type_distribution}}</td>
                                    <td>{{$distribution->client->name_client}}</td>
                                    <td>{{$distribution->truckCategory->truck_category}}</td>
                                    <td>Ship00{{$distribution->code_distribution}}</td>
                                    <td>{{$distribution->axe_distribution}}</td>
                                    <td>{{date('d/m/Y', strtotime($distribution->date_order))}}</td>
                                    <td>{{$distribution->qty}}</td>
                                    <td>{{number_format($distribution->volume)}}</td>
                                    <td>{{$distribution->nbr_delivery_points}}</td>
                                    <td>{{$distribution->nbr_expected_days}}</td>
                                    <td><i class="fas fa-info-circle show-distribution-details" data-distribution-id="{{$distribution->id_distribution_header}}"></i></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fade modal" id="distributionModal" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script>
        var table = $('#distributions-table').DataTable();

        $(document).ready(function() {
            // Click event handler for the icon
            $('.show-distribution-details').click(function() {
                // Get the distribution ID from the data attribute
                var distributionId = $(this).data('distribution-id');

                // Make an Ajax request to the Laravel route
                $.ajax({
                    url: '/distribution-details/' + distributionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {

                        // Populate the modal with the response data
                        var distributionDetails = response.distribution;
                        var modalTitle = $('#distributionModal .modal-title');
                        modalTitle.text('Details Distribution Ship00' + distributionDetails.code_distribution + ' - Creee');

                        // Create an HTML string for additional details
                        var additionalDetailsHtml = '<p><b>Client: </b>' + distributionDetails.is_mutual +'</p>';
                        additionalDetailsHtml += '<p><b>Type: </b>' + distributionDetails.distribution_type.type_distribution +'</p>';
                        additionalDetailsHtml += '<p><b>AXE: </b>' + distributionDetails.axe_distribution +'</p>';
                        additionalDetailsHtml += '<p><b>Quantite: </b>' + distributionDetails.qty +'</p>';
                        additionalDetailsHtml += '<p><b>Volume: </b>' + (distributionDetails.volume).toLocaleString() +'</p>';
                        additionalDetailsHtml += '<hr>';
                        // Add more details as needed

                        var distributionLines = distributionDetails.distribution_lines;
                        var tableHtml = '' +
                            '<table class="table table-bordered table-striped" id="lines-table">' +
                                '<thead>' +
                                    '<tr>' +
                                        '<th>Code</th>' +
                                        '<th>NBL</th>' +
                                        '<th>Nom Livraison l</th>' +
                                        '<th>Qte</th>' +
                                        '<th>Volume</th>' +
                                        '<th>Order de l</th>' +
                                    '</tr>' +
                                '</thead>' +
                            '<tbody>';
                        $.each(distributionLines, function(index, line) {
                            tableHtml += '<tr>';
                            tableHtml += '<td>' + line.id_distribution_line + '</td>';
                            tableHtml += '<td>' + line.num_bl + '</td>';
                            tableHtml += '<td>' + line.name_delivery + '</td>';
                            tableHtml += '<td>' + line.qty_line + '</td>';
                            tableHtml += '<td>' + line.volume_line + '</td>';
                            tableHtml += '<td>' + line.line_order + '</td>';
                            tableHtml += '</tr>';
                        });

                        tableHtml += '</tbody></table>';

                        // Populate the modal body with the table
                        var modalBody = $('#distributionModal .modal-body');
                        modalBody.empty(); // Clear previous content
                        modalBody.append(additionalDetailsHtml);
                        modalBody.append(tableHtml);
                        $('#lines-table').DataTable();

                        // Show the modal
                        $('#distributionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });
        });

    </script>
@endsection
