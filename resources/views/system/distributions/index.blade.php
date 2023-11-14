@extends('adminlte::page')
@section('title', 'Distributions')
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

    #distributions-table {
        width: 100% !important;
    }

    #lines-table {
        width: 100% !important;
    }
</style>
@vite(['resources/js/app.js'])
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ajoutre une liste de distribution</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-6">
                            <form action="{{ route('distributions.import') }}" method="POST"
                                  enctype="multipart/form-data" class="form-inline">
                                @csrf
                                <div class="form-group">
                                    <label for="file">Add a file</label>
                                    <input type="file" name="file" id="file" class="form-control-file">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"
                                        id="fileBtn">Import
                                    Distributions
                                </button>
                            </form>
                        </div>
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
                                <tr id="distributionRow-{{$distribution->id_distribution_header }}"
                                    class="distribution-row">
                                    <td>{{ str_pad($distribution->code_distribution, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{$distribution->distributionType->type_distribution}}</td>
                                    <td>{{$distribution->client->name_client}}</td>
                                    <td>{{$distribution->truckCategory->truck_category}}</td>
                                    <td>Ship{{str_pad($distribution->code_distribution, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{$distribution->axe_distribution}}</td>
                                    <td>{{date('d/m/Y', strtotime($distribution->date_order))}}</td>
                                    <td>{{$distribution->qty}}</td>
                                    <td>{{number_format($distribution->volume)}}</td>
                                    <td>{{$distribution->nbr_delivery_points}}</td>
                                    <td>{{$distribution->nbr_expected_days}}</td>
                                    <td><i class="fas fa-info-circle show-distribution-details"
                                           data-distribution-id="{{$distribution->id_distribution_header}}"></i></td>
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
                <div class="modal-footer">
                    <i type="button" class="fas fa-trash-alt btn btn-secondary"> Solder Distribution</i>
                    <i type="button" class="fas fa-edit btn btn-secondary"> Modifier Distribution</i>
                    <i type="button" class="fas fa-file btn btn-secondary" id="planifier-btn"> Planifier
                        Distribution</i>
                </div>
            </div>
        </div>
    </div>

    <div class="fade modal" id="planifier-modal" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="plainify_form">
                        <input type="hidden" name="distribution_id" id="distribution_id">

                        <div class="form-group">
                            <label for="execution-date">Exécution Date *</label>
                            <input type="date" class="form-control" id="execution_date" name="execution_date"
                                   placeholder="17/12/2022">
                        </div>
                        <div class="form-group">
                            <label for="driver-info">Driver *</label>
                            <select name="driver_id" id="driver_id" class="form-control">
                                <option value="">Select value</option>
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id_driver}}">
                                        {{$driver->firstname}} {{$driver->lastname}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="truck-info">Truck *</label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control">
                                <option value="">Select value</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{$vehicle->id_vehicle}}">
                                        {{$vehicle->marque_vehicle}} {{$vehicle->modele_vehicle}} {{$vehicle->immatriculation}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <i type="submit" class="fas fa-file btn btn-secondary" id="planifier-btn-submit"> Planifier
                        Distribution</i>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var table = $('#distributions-table').DataTable({
            responsive: true
        });

        // Get the CSRF token value from the meta tag in your HTML
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include the CSRF token in your Ajax request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        $(document).ready(function () {
            // When the file form is submitted
            $("#fileBtn").click(function (event) {
                // Get the value of the file input field
                var fileInput = $("#file")[0];

                // Check if a file has been selected
                if (fileInput.files.length === 0) {
                    // No file selected, display an error message or perform any desired action
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Required file',
                        subtitle: false,
                        body: 'Please select a file'
                    })
                    event.preventDefault(); // Prevent form submission
                }
                setTimeout(function () {
                    $('.toast').toast('hide');
                }, 3000);
            });
            // Click event handler for the icon
            $(document).on('click', '.show-distribution-details', function () {
                // Get the distribution ID from the data attribute
                var distributionId = $(this).data('distribution-id');

                // Make an Ajax request to the Laravel route
                $.ajax({
                    url: '/distribution-details/' + distributionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {

                        // Populate the modal with the response data
                        var distributionDetails = response.distribution;
                        var modalTitle = $('#distributionModal .modal-title');
                        modalTitle.text('Details Distribution Ship00' + distributionDetails.code_distribution + ' - Creee');

                        // Create an HTML string for additional details
                        var additionalDetailsHtml = '<p><b>Client: </b>' + distributionDetails.is_mutual + '</p>';
                        additionalDetailsHtml += '<p><b>Type: </b>' + distributionDetails.distribution_type.type_distribution + '</p>';
                        additionalDetailsHtml += '<p><b>AXE: </b>' + distributionDetails.axe_distribution + '</p>';
                        additionalDetailsHtml += '<p><b>Quantite: </b>' + distributionDetails.qty + '</p>';
                        additionalDetailsHtml += '<p><b>Volume: </b>' + (distributionDetails.volume).toLocaleString() + '</p>';
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
                        $.each(distributionLines, function (index, line) {
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
                        $('#lines-table').DataTable({
                            responsive: true
                        });

                        // Show the modal
                        $('#distributionModal').modal('show');

                        $('#planifier-btn').attr('data-distribution-id', distributionId);
                        $('#distribution_id').val(distributionId);
                        var planifierModalTitle = $('#planifier-modal .modal-title');
                        planifierModalTitle.text('Details Distribution ' + (distributionDetails.code_distribution).padStart(5, '0') + ' - Planifi la distribution');
                    },
                    error: function (xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });

            $('#planifier-btn').click(function () {
                var distributionId = $(this).data('distribution-id');
                $('#distributionModal').modal('hide');
                $('#plainify_form')[0].reset();
                $('#planifier-modal').modal('show');
            });

            $("#planifier-btn-submit").click(function (e) {
                e.preventDefault(); // Prevent the default form submission

                // Get form data
                var formData = {
                    'execution_date': $('#execution_date').val(),
                    'driver_id': $('#driver_id').val(),
                    'vehicle_id': $('#vehicle_id').val(),
                    'distribution_id': $('#distribution_id').val()
                    // Add other form fields here if needed
                };

                // Send an AJAX request to your server
                $.ajax({
                    type: 'POST',
                    url: '{{ route("distribution.planify") }}',
                    data: formData,
                    success: function (data) {
                        $('#planifier-modal').modal('hide');
                        // Handle the success response from the server
                        Swal.fire(
                            false,
                            data.message,
                            'success'
                        );
                    },
                    error: function (xhr, status, error) {
                        // You can display an error message or handle errors as needed
                        if (xhr.status === 409) {
                            $('#planifier-modal').modal('hide');
                            Swal.fire(
                                false,
                                xhr.responseJSON.message,
                                'error'
                            );
                        } else {
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Required fields',
                                subtitle: false,
                                body: 'Please choose some data for required fields'
                            })
                        }
                    }
                });
                setTimeout(function () {
                    $('.toast').toast('hide');
                }, 3000);
            });
        });

    </script>
@endsection
