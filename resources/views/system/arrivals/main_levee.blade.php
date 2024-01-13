@extends('adminlte::page')
@section('title', 'Arrivals-Main Levee')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Toasts', true)
<style>
    .arrival-row td {
        text-align: center;
    }

    .show-arrival-details {
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

    #arrivalFormModal {
        overflow-y: scroll;
    }

</style>
@vite(['resources/js/app.js'])
@section('content')
    <div class="container">
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
                                <th>Date Remise</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($arrivals as $arrival)
                                <tr id="arrivalRow-{{$arrival->id}}"
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
                                    <td>{{$arrival->date_remise}}</td>
                                    <td><i class="fas fa-info-circle show-arrival-details"
                                           data-arrival-id="{{$arrival->id}}"></i></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="arrivalFormModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="arrivalModalLabel">Ajouter un nouveau arrivage</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="arrivalForm" action="#" method="POST">
                        @csrf
                        <input type="hidden" id="arrival_id">
                        <div id="step1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client">Client*</label>
                                        <select name="client_id" id="client_id" class="form-control" required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($clients as $client)
                                                <option value="{{$client->id_client}}">
                                                    {{$client->name_client}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type*</label>
                                        <select name="arrival_type_id" id="arrival_type_id" class="form-control"
                                                required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($arrivalTypes as $arrivalType)
                                                <option value="{{$arrivalType->id}}">
                                                    {{$arrivalType->type}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dossier_tegic">Dossier Tegic*</label>
                                        <input type="text" class="form-control" id="dossier_tegic" name="dossier_tegic"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dossier_tegic">Dossier Client</label>
                                        <input type="text" class="form-control" id="dossier_client"
                                               name="dossier_client">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_compagnie">Shipping Compagnie*</label>
                                        <input type="text" class="form-control" id="shipping_compagnie"
                                               name="shipping_compagnie" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city_id">POD*</label>
                                        <select name="city_id" id="city_id" class="form-control" required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($cities as $city)
                                                <option value="{{$city->id_city}}">
                                                    {{$city->city}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="eta">ETA*</label>
                                        <input type="date" class="form-control" id="eta" name="eta"
                                               placeholder="17/12/2022" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ata">ATA</label>
                                        <input type="date" class="form-control" id="ata" name="ata"
                                               placeholder="17/12/2022">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lieu_de_chargement">Lieu de chargement*
                                        </label>
                                        <select name="lieu_de_chargement" id="lieu_de_chargement" class="form-control"
                                                required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($deliveryPoints as $points)
                                                <option value="{{$points->name_delivery}}">
                                                    {{$points->name_delivery}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lieu_de_dechargement">Lieu de déchargement*
                                        </label>
                                        <select name="lieu_de_dechargement" id="lieu_de_dechargement"
                                                class="form-control" required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($deliveryPoints as $points)
                                                <option value="{{$points->name_delivery}}">
                                                    {{$points->name_delivery}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lieu_de_restitution">Lieu de Restitution*</label>
                                        <select name="lieu_de_restitution" id="lieu_de_restitution"
                                                class="form-control" required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($deliveryPoints as $points)
                                                <option value="{{$points->name_delivery}}">
                                                    {{$points->name_delivery}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_bae_Previsionnelle">Date BAE Prévisionnelle</label>
                                        <input type="date" class="form-control" id="date_bae_Previsionnelle"
                                               name="date_bae_Previsionnelle" placeholder="17/12/2022">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_magasinage">Date magasinage</label>
                                        <input type="date" class="form-control" id="date_magasinage"
                                               name="date_magasinage"
                                               placeholder="17/12/2022">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_surestaries">Date Surestaries</label>
                                        <input type="date" class="form-control" id="date_surestaries"
                                               name="date_surestaries" placeholder="17/12/2022">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step2" style="display: none;">
                            <div class="row inputFormRow">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero">Numéro*</label>
                                        <input type="text" class="form-control numero" id="numero" name="numero[]"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="arrival_line_type_id">Type*</label>
                                        <select name="arrival_line_type_id[]" id="arrival_line_type_id"
                                                class="form-control arrival_line_type_id" required>
                                            <option value="">Sélectionner une valeur</option>
                                            @foreach($lineTypes as $lineType)
                                                <option value="{{$lineType->id}}">
                                                    {{$lineType->type}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nb_de_pieces">Nb de pièces*</label>
                                        <input type="text" class="form-control nb_de_pieces" id="nb_de_pieces"
                                               name="nb_de_pieces[]" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="poids">Poids</label>
                                        <input type="text" class="form-control poids" id="poids" name="poids[]">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="volume">Volume</label>
                                        <input type="text" class="form-control volume" id="volume" name="volume[]">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button id="addRow" type="button" class="btn btn-info addRow">+</button>
                                <button type="button" class="btn btn-danger removeRow" style="display: none;">-</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    <button type="button" class="btn btn-success" id="submitBtn" style="display: none;">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="fade modal" id="arrivalModal" style="display: none;">
        <div class="modal-dialog modal-xl">
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
                    <i type="button" class="fas fa-trash-alt btn btn-secondary" id="delete-arrival-btn"> Solder
                        Arrivage</i>
                    <a type="button" class="fas fa-edit btn btn-secondary" id="edit-arrival-btn"> Modifier
                        Arrivage</a>
                    <a type="button" class="fas fa-file btn btn-secondary" id="taxation-btn">Taxation</a>
                </div>
            </div>
        </div>
    </div>

    <div class="fade modal" id="taxationModal" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        @csrf
                        <input type="hidden" id="taxation_arrival_id">
                        <div class="form-group">
                            <label for="date_remise">Date de taxation</label>
                            <input type="date" class="form-control" id="date_taxation"
                                   name="date_taxation" placeholder="17/12/2022">
                        </div>
                        <div class="form-group">
                            <label for="taxation_agent">AgentDe taxation</label>
                            <select name="taxation_agent" id="taxation_agent"
                                    class="form-control taxation_agent">
                                <option value="">Sélectionner une valeur</option>
                                @foreach($agents as $agent)
                                    <option value="{{$agent->id}}">
                                        {{$agent->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a type="button" class="fas fa-file btn btn-secondary" id="validerTaxation">Valider Taxation</a>
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

        $(document).ready(function () {
            let currentStep = 1;
            // $('#arrivalModalLabel').text('Ajouter un nouveau arrivage');

            // Function to show the current step
            function showStep(step) {
                $('#step1, #step2').hide();
                $(`#step${step}`).show();
            }

            // Next button click event
            $('#nextBtn').click(function () {
                currentStep++;
                showStep(currentStep);

                // Show/hide buttons based on current step
                if (currentStep === 1) {
                    $('#prevBtn').hide();
                    $('#submitBtn').hide();
                    // $('#arrivalModalLabel').text('Ajouter un nouveau arrivage');
                } else if (currentStep === 2) {
                    $('#prevBtn').show();
                    $('#nextBtn').hide();
                    $('#submitBtn').show();
                    // $('#arrivalModalLabel').text(`Multi-Step Form - Step ${step}`);
                }
            });

            // Previous button click event
            $('#prevBtn').click(function () {
                currentStep--;
                showStep(currentStep);

                // Show/hide buttons based on current step
                if (currentStep === 1) {
                    $('#prevBtn').hide();
                    $('#nextBtn').show();
                    $('#submitBtn').hide();
                    // $('#arrivalModalLabel').text('Ajouter un nouveau arrivage');
                } else if (currentStep === 2) {
                    $('#nextBtn').hide();
                    $('#submitBtn').show();
                    // $('#arrivalModalLabel').text(`Multi-Step Form - Step ${step}`);
                }
            });

            // Function to update the visibility of minus buttons
            function updateMinusButtons() {
                if ($('.inputFormRow').length === 1) {
                    $('.removeRow').hide();
                } else {
                    $('.removeRow').show();
                }
            }

            // Initially call the update function
            updateMinusButtons();

            // Add row
            $(".addRow").click(function () {
                var newRow = $('.inputFormRow:first').clone();
                newRow.find('input').val(''); // Clear the values in the inputs
                if (!newRow.find('.removeRow').length) {
                    newRow.append('<div class="col-md-12"><button type="button" class="btn btn-danger removeRow">-</button></div>');
                }
                $('.inputFormRow:last').after(newRow);
                updateMinusButtons();
            });

            // Remove row
            $(document).on('click', '.removeRow', function () {
                $(this).closest('.inputFormRow').remove();
                updateMinusButtons();
            });

            // Get the CSRF token value from the meta tag in your HTML
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Include the CSRF token in your Ajax request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            $('#submitBtn').click(function (e) {
                e.preventDefault();
                // Collect data from the form
                var formData = {
                    // Collect all necessary data
                    'arrival': {
                        'arrival_id': $('#arrival_id').val(),
                        'client_id': $('#client_id').val(),
                        'arrival_type_id': $('#arrival_type_id').val(),
                        'dossier_tegic': $('#dossier_tegic').val(),
                        'dossier_client': $('#dossier_client').val(),
                        'shipping_compagnie': $('#shipping_compagnie').val(),
                        'city_id': $('#city_id').val(),
                        'eta': $('#eta').val(),
                        'ata': $('#ata').val(),
                        'lieu_de_chargement': $('#lieu_de_chargement').val(),
                        'lieu_de_dechargement': $('#lieu_de_dechargement').val(),
                        'lieu_de_restitution': $('#lieu_de_restitution').val(),
                        'date_bae_Previsionnelle': $('#date_bae_Previsionnelle').val(),
                        'date_magasinage': $('#date_magasinage').val(),
                        'date_surestaries': $('#date_surestaries').val(),
                    },
                    'arrival_lines': [] // Array to hold arrival line data
                };

                // Loop over arrival lines to collect their data
                $('.inputFormRow').each(function () {
                    formData.arrival_lines.push({
                        'numero': $(this).find('.numero').val(), // Use class selector here
                        'arrival_line_type_id': $(this).find('.arrival_line_type_id').val(), // Use class selector here
                        'nb_de_pieces': $(this).find('.nb_de_pieces').val(), // Use class selector here
                        'poids': $(this).find('.poids').val(), // Use class selector here
                        'volume': $(this).find('.volume').val() // Use class selector here
                    });
                });

                // AJAX call
                $.ajax({
                    type: 'POST',
                    url: '/update-arrivals', // URL to your route
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 422) {
                            Swal.fire(
                                false,
                                'Please fill the required fields',
                                'warning'
                            );
                            return;
                        } else {
                            Swal.fire(
                                false,
                                data.message,
                                'success'
                            );
                            $('#arrivalFormModal').modal('hide');
                        }
                        var arrivalId = $('#arrival_id').val();
                        if (arrivalId) {
                            var row = table.row(('#arrivalRow-' + arrivalId)).data([
                                data.arrival.id,
                                data.arrival.clients.name_client,
                                data.arrival.arrival_types.type,
                                data.arrival.dossier_tegic,
                                data.arrival.dossier_client,
                                data.arrival.shipping_compagnie,
                                data.arrival.cities.city,
                                data.arrival.eta,
                                data.arrival.ata,
                                data.arrival.lieu_de_chargement,
                                data.arrival.lieu_de_dechargement,
                                data.arrival.lieu_de_restitution,
                                data.arrival.date_bae_Previsionnelle,
                                data.arrival.date_magasinage,
                                data.arrival.date_surestaries,
                                data.arrival.date_remise,
                                '<i class="fas fa-info-circle show-arrival-details" data-arrival-id=' + data.arrival.id + '></i>'
                            ]).draw();
                        }
                    },
                    error: function (response) {
                        console.log(response.error);
                    }
                });
            });

            // Click event handler for the icon
            $(document).on('click', '.show-arrival-details', function () {
                var arrivalId = $(this).data('arrival-id');

                $.ajax({
                    url: '/arrival-details/' + arrivalId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var arrivalDetails = data.arrival;
                        var modalTitle = $('#arrivalModal .modal-title');
                        modalTitle.text('Details arrivage ' + arrivalDetails.id);

                        var modalBaeTitle = $('#taxationModal .modal-title');
                        modalBaeTitle.text(arrivalDetails.dossier_tegic + ' ' + arrivalDetails.dossier_client + ' Remise BAE');

                        // Create an HTML string for additional details
                        var additionalDetailsHtml = '<p name="client_id_ajax"><b>Client: </b>' + arrivalDetails.clients.name_client + '</p>';
                        additionalDetailsHtml += '<p name="id_type_ot_ajax"><b>Type: </b>' + arrivalDetails.arrival_types.type + '</p>';
                        additionalDetailsHtml += '<p name="doss_tegic"><b>Dossier tegic: </b>' + arrivalDetails.dossier_tegic + '</p>';
                        additionalDetailsHtml += '<p name="doss_client"><b>Dossier client: </b>' + arrivalDetails.dossier_client + '</p>';
                        additionalDetailsHtml += '<p name="shipping"><b>Shipping compagnie: </b>' + arrivalDetails.shipping_compagnie + '</p>';
                        additionalDetailsHtml += '<p name="pod"><b>POD: </b>' + arrivalDetails.cities.city + '</p>';
                        additionalDetailsHtml += '<p name="date_bae"><b>Date Bae: </b>' + arrivalDetails.date_remise + '</p>';
                        additionalDetailsHtml += '<p name="status"><b>Status: </b>' + arrivalDetails.status.replace('_', ' '); + '</p>';
                        additionalDetailsHtml += '<hr>';
                        var arrivalLines = arrivalDetails.arrival_lines;

                        var tableHtml = '' +
                            '<table class="table table-bordered table-striped" id="lines-table">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>Numero</th>' +
                            '<th>Nb de pieces</th>' +
                            '<th>Poids</th>' +
                            '<th>Volume</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>';
                        $.each(arrivalLines, function (index, line) {
                            tableHtml += '<tr id="lineRow-' + line.id + '">';
                            tableHtml += '<td>' + line.numero + '</td>';
                            tableHtml += '<td>' + line.nb_de_pieces + '</td>';
                            tableHtml += '<td>' + line.poids + '</td>';
                            tableHtml += '<td>' + line.volume + '</td>';
                            tableHtml += '</tr>';
                        });

                        tableHtml += '</tbody></table>';

                        var modalBody = $('#arrivalModal .modal-body');
                        modalBody.empty(); // Clear previous content
                        modalBody.append(additionalDetailsHtml);
                        modalBody.append(tableHtml);
                        $('#arrivalModal').modal('show');
                        $('#delete-arrival-btn').attr('data-arrival-id', arrivalId);
                        $('#edit-arrival-btn').attr('data-arrival-id', arrivalId);
                        $('#taxation-btn').attr('data-arrival-id', arrivalId);
                    },
                    error: function (xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });

            $(document).on('click', '#delete-arrival-btn', function () {
                var arrivalId = $(this).data('arrival-id');

                // Confirm the deletion with the user (optional)
                // Send a DELETE request to delete the client
                Swal.fire({
                    title: 'Etes-vous sûr?',
                    text: 'Voulez-vous supprimer cette arrivage ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Non',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ route('arrival.delete') }}', // Use the correct route URL
                            data: {id: arrivalId},
                            success: function (data) {
                                // Handle the success response here
                                Swal.fire(
                                    false,
                                    data.message,
                                    'success'
                                );
                                $('#arrivalModal').modal('hide');
                                // Remove the row from table
                                table.row('#arrivalRow-' + arrivalId).remove().draw();
                            },
                            error: function (xhr, status, error) {
                                console.log(xhr.responseText);
                                // Handle the error response here
                            }
                        });
                    }
                })
            });

            $(document).on('click', '#edit-arrival-btn', function () {
                var arrivalId = $(this).data('arrival-id');
                $('#arrival_id').val(arrivalId);
                $('#arrivalModal').modal('hide');
                $('#arrivalFormModal').modal('show');
                $.ajax({
                    type: 'GET',
                    url: '/arrival-details/' + arrivalId,
                    data: {id: arrivalId},
                    success: function (data) {
                        var arrival = data.arrival;
                        var arrivalLines = data.arrival.arrival_lines;
                        $('#client_id option[value="' + arrival.client_id + '"]').attr('selected', 'selected');
                        $('#arrival_type_id option[value="' + arrival.arrival_type_id + '"]').attr('selected', 'selected');
                        $('#city_id option[value="' + arrival.city_id + '"]').attr('selected', 'selected');
                        $('#lieu_de_chargement option[value="' + arrival.lieu_de_chargement + '"]').attr('selected', 'selected');
                        $('#lieu_de_dechargement option[value="' + arrival.lieu_de_dechargement + '"]').attr('selected', 'selected');
                        $('#lieu_de_restitution option[value="' + arrival.lieu_de_restitution + '"]').attr('selected', 'selected');
                        $('#dossier_tegic').val(arrival.dossier_tegic)
                        $('#dossier_client').val(arrival.dossier_client)
                        $('#shipping_compagnie').val(arrival.shipping_compagnie)
                        $('#eta').val(arrival.eta)
                        $('#ata').val(arrival.ata)
                        $('#date_magasinage').val(arrival.date_magasinage)
                        $('#date_bae_Previsionnelle').val(arrival.date_bae_Previsionnelle)
                        $('#date_surestaries').val(arrival.date_surestaries)

                        // Clear any existing rows
                        $('.inputFormRow').not(':first').remove();

                        // Iterate over each arrival line and create a row for it
                        $.each(arrivalLines, function (index, line) {
                            // Clone the first row to use as a template
                            var newRow = $('.inputFormRow:first').clone();

                            // Populate the newRow with data from arrival line
                            $(newRow).find('.numero').val(line.numero);
                            $(newRow).find('.arrival_line_type_id').val(line.arrival_line_type_id);
                            $(newRow).find('.nb_de_pieces').val(line.nb_de_pieces);
                            $(newRow).find('.poids').val(line.poids);
                            $(newRow).find('.volume').val(line.volume);

                            // Remove the id attributes to avoid duplicates
                            $(newRow).find('input, select').removeAttr('id');

                            // Add a minus button to remove the row
                            var removeButton = $('<button>').addClass('btn btn-danger removeRow')
                                .text('-')
                                .click(function () {
                                    $(this).closest('.inputFormRow').remove();
                                });

                            $(newRow).find('.col-md-12').append(removeButton);
                            // Append the newRow to the form
                            $(newRow).appendTo('#arrivalFormModal .modal-body #arrivalForm #step2');

                            // If it's not the first item, show the remove button
                            if (index !== 0) {
                                $(newRow).find('.removeRow').show();
                            }

                            // Remove the first row if it's a placeholder or if you do not need it
                            /*if (index !== 0) {
                                $('.inputFormRow:first').remove();
                            }*/
                        });

                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '#taxation-btn', function () {
                var arrivalId = $(this).data('arrival-id');
                $('#taxation_arrival_id').val(arrivalId)
                $('#taxationModal').modal('show');
            });

            $(document).on('click', '#validerTaxation', function () {
                $.ajax({
                    type: 'POST',
                    url: '/valider-taxation',
                    data: {
                        'arrival_id': $('#taxation_arrival_id').val(),
                        'date_taxation': $('#date_taxation').val(),
                        'taxation_agent': $('#taxation_agent').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire(
                            false,
                            data.message,
                            'success'
                        );
                        $('#arrivalModal').modal('hide');
                        $('#taxationModal').modal('hide');
                        table.row(('#arrivalRow-' + $('#taxation_arrival_id').val())).remove().draw();
                    },
                    error: function (response) {
                        console.log(response.error);
                    }
                });
            })
        });
    </script>
@endsection
