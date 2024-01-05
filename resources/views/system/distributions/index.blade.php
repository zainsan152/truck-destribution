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
                            <form action="{{ route('distributions.import') }}" method="POST"
                                  enctype="multipart/form-data" class="form-inline">
                                @csrf
                                <div class="form-group">
                                    <label for="file">Ajouter un fichier</label>
                                    <input type="file" name="file" id="file" class="form-control-file">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"
                                        id="fileBtn">Importer des distributions
                                </button>
                            </form>
                        </div>
                        @if(session('missing_clients'))
                                <ul style="color: #8b2512; font-weight: 400; font-size: 1.1rem; padding: 0.75rem">
                                    @foreach(session('missing_clients') as $missingClient)
                                        <p>* Le client {{ $missingClient }} n'existe pas dans la base des clients!</p>
                                    @endforeach
                                </ul>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">Liste des distributions créées</h3>
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
{{--                                <th>Details</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($distributions as $distribution)
                                <tr id="distributionRow-{{$distribution->id_distribution_header }}"
                                    class="distribution-row">
                                    <td><i class="fas fa-info-circle show-distribution-details"
                                           data-distribution-id="{{$distribution->id_distribution_header}}"></i><span class="ml-2">{{ str_pad($distribution->code_distribution, 5, '0', STR_PAD_LEFT) }}</span></td>
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
                                    {{--<td><i class="fas fa-info-circle show-distribution-details"
                                           data-distribution-id="{{$distribution->id_distribution_header}}"></i></td>--}}
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
                    <i type="button" class="fas fa-trash-alt btn btn-secondary" id="delete-distribution-btn"> Solder
                        Distribution</i>
                    <a type="button" class="fas fa-edit btn btn-secondary" id="edit-lines-btn"> Modifier
                        Distribution</a>
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
                            <label for="execution-date">Date d'exécution*</label>
                            <input type="date" class="form-control" id="execution_date" name="execution_date"
                                   placeholder="17/12/2022">
                        </div>
                        <div class="form-group">
                            <label for="driver-info">Chauffeur *</label>
                            <select name="driver_id" id="driver_id" class="form-control">
                                <option value="">Sélectionner une valeur</option>
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id_driver}}">
                                        {{$driver->firstname}} {{$driver->lastname}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="truck-info">Véhicule *</label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control">
                                <option value="">Sélectionner une valeur</option>
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

    <div class="modal fade" id="lineModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lineModalTitle">Edit Distribution line</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="lineForm" action="#" method="POST">
                        @csrf
                        <input type="hidden" name="line_id" id="line_id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NBL</label>
                                    <input type="text" name="nbl" class="form-control" id="nbl">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom Livraison l</label>
                                    <input type="text" name="livrasion" class="form-control" id="livrasion">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Qte</label>
                                    <input type="number" name="qte" class="form-control" id="qte">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Volume</label>
                                    <input type="number" name="volume" class="form-control" id="volume">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float: right;" id="lineActionButton">
                            Mettre à jour
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var canShowModal = false;
        var table = $('#distributions-table').DataTable({
responsive: true,
language: {
"emptyTable": "Aucune donnée disponible",
"info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
"infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
"infoFiltered": "(filtrées depuis un total de _MAX_ entrées)",
"lengthMenu": "Afficher _MENU_ entrées",
"paginate": {
        "first": "Première",
        "last": "Dernière",
        "next": "Suivante",
        "previous": "Précédente"
    },
}
});

        // Get the CSRF token value from the meta tag in your HTML
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include the CSRF token in your Ajax request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        $("#fileBtn").click(function (event) {
            // Get the value of the file input field
            var fileInput = $("#file")[0];

            // Check if a file has been selected
            if (fileInput.files.length === 0) {
                // No file selected, display an error message or perform any desired action
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Fichier obligatoire',
                    subtitle: false,
                    body: 'Merci de sélectionner un fichier'
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
                    modalTitle.text('Details Distribution Ship00' + distributionDetails.code_distribution);

                    // Create an HTML string for additional details
                    var additionalDetailsHtml = '<p name="client_id_ajax"><b>Client: </b>' + distributionDetails.is_mutual + '</p>';
                    additionalDetailsHtml += '<p name="id_type_ot_ajax"><b>Type: </b>' + distributionDetails.distribution_type.type_distribution + '</p>';
                    additionalDetailsHtml += '<p name="axe_distribution_ajax"><b>AXE: </b>' + distributionDetails.axe_distribution + '</p>';
                    additionalDetailsHtml += '<p name="qty_ajax"><b>Quantite: </b>' + distributionDetails.qty + '</p>';
                    additionalDetailsHtml += '<p name="volume_ajax"><b>Volume: </b>' + distributionDetails.volume + '</p>';
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
                        '<th>Modifier</th>' +
                        '<th>Supprimer</th>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody>';
                    $.each(distributionLines, function (index, line) {
                        tableHtml += '<tr id="lineRow-' + line.id_distribution_line + '">';
                        tableHtml += '<td>' + line.id_distribution_line + '</td>';
                        tableHtml += '<td>' + line.num_bl + '</td>';
                        tableHtml += '<td>' + line.name_delivery + '</td>';
                        tableHtml += '<td>' + line.qty_line + '</td>';
                        tableHtml += '<td>' + line.volume_line + '</td>';
                        tableHtml += '<td>' + line.line_order + '</td>';
                        tableHtml += '<td><i class="fas fa-edit edit-line-button" data-line-id="' + line.id_distribution_line + '"></i></td>';
                        tableHtml += '<td><i class="fas fa-trash-alt delete-line-button" data-line-id="' + line.id_distribution_line + '"></i></td>';
                        tableHtml += '</tr>';
                    });

                    tableHtml += '</tbody></table>';

                    // Populate the modal body with the table
                    var modalBody = $('#distributionModal .modal-body');
                    modalBody.empty(); // Clear previous content
                    modalBody.append(additionalDetailsHtml);
                    modalBody.append(tableHtml);
                    var lineTable = $('#lines-table').DataTable({
                        responsive: true,
                        language: {
"emptyTable": "Aucune donnée disponible",
"info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
"infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
"infoFiltered": "(filtrées depuis un total de _MAX_ entrées)",
"lengthMenu": "Afficher _MENU_ entrées",
"paginate": {
        "first": "Première",
        "last": "Dernière",
        "next": "Suivante",
        "previous": "Précédente"
    },
}   
                    });

                    // Show the modal
                    $('#distributionModal').modal('show');

                    $('#planifier-btn').attr('data-distribution-id', distributionId);
                    $('#delete-distribution-btn').attr('data-distribution-id', distributionId);
                   /* var url = "{{ route('lines.edit', ['id' => ':id']) }}";
                    url = url.replace(':id', distributionId);*/

                    // Assign the URL as a data attribute to the button
                    // $("#edit-lines-btn").attr("href", url);
                    $('#distribution_id').val(distributionId);
                    var planifierModalTitle = $('#planifier-modal .modal-title');
                    planifierModalTitle.text('Details Distribution ' + (distributionDetails.code_distribution).padStart(5, '0') + ' - Planifier la distribution');
                },
                error: function (xhr, status, error) {
                    console.error('Ajax request error:', error);
                }
            });
        });

        $('#edit-lines-btn').click(function (){
            canShowModal = true;
        })

        $('#planifier-btn').click(function () {
            var distributionId = $(this).data('distribution-id');
            $('#distributionModal').modal('hide');
            $('#plainify_form')[0].reset();
            $('#planifier-modal').modal('show');
        });

        $("#planifier-btn-submit").click(function (e) {
            e.preventDefault(); // Prevent the default form submission

            /*var arr_volume = $("p[name='volume_ajax']").text().split(':');
            var arr_qty = $("p[name='qty_ajax']").text().split(':');
            var arr_client = $("p[name='client_id_ajax']").text().split(':');
            var arr_ottype = $("p[name='id_type_ot_ajax']").text().split(':');
            var arr_axe = $("p[name='axe_distribution_ajax']").text().split(':');*/

            // Get form data
            var formData = {
                'execution_date': $('#execution_date').val(),
                'driver_id': $('#driver_id').val(),
                'vehicle_id': $('#vehicle_id').val(),
                'distribution_id': $('#distribution_id').val(),
                /*'client_id': arr_client[1],
                'id_type_ot': arr_ottype[1],
                'axe_distribution': arr_axe[1],
                'qty': parseFloat(arr_qty[1]),
                'volume': parseFloat(arr_volume[1]),*/

                // Add other form fields here if needed
            };

            /*Swal.fire({
                title: 'volume',
                text: parseFloat(arr_volume[1]),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
            });*/

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
                            title: 'Champs obligatoires!',
                            subtitle: false,
                            body: xhr.responseJSON.message
                            //body: 'Merci de remplir les champs obligatoires'
                        })
                    }
                }
            });
            setTimeout(function () {
                $('.toast').toast('hide');
            }, 3000);
        });

        $(document).on('click', '#delete-distribution-btn', function () {
            var distributionId = $(this).data('distribution-id');

            // Confirm the deletion with the user (optional)
            // Send a DELETE request to delete the client
            Swal.fire({
                title: 'Etes-vous sûr?',
                text: 'Voulez-vous supprimer cette distribution ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('distribution.delete') }}', // Use the correct route URL
                        data: {id: distributionId}, // Pass the client ID as a parameter
                        success: function (data) {
                            // Handle the success response here
                            Swal.fire(
                                false,
                                data.message,
                                'success'
                            );
                            $('#distributionModal').modal('hide');
                            // Remove the row from table
                            table.row('#distributionRow-' + distributionId).remove().draw();
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                            // Handle the error response here
                        }
                    });
                }
            })
        });

        $(document).on('click', '.delete-line-button', function () {
            var lineId = $(this).data('line-id');

            // Confirm the deletion with the user (optional)
            Swal.fire({
                title: 'Etes vous sûr?',
                text: 'Vous ne pourrez pas revenir en arrière!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('lines.delete') }}',
                        data: {id: lineId},
                        success: function (data) {
                            // Handle the success response here
                            Swal.fire(
                                false,
                                data.message,
                                'success'
                            );
                            // Remove the row from table
                            $('#lines-table').DataTable().row('#lineRow-' + lineId).remove().draw();
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                            // Handle the error response here
                        }
                    });
                }
            })
        });

        $(document).on('click', '.edit-line-button', function () {
            var lineId = $(this).data('line-id');
            if(canShowModal){
                $('#lineModal').modal('show');
            }
            $.ajax({
                type: 'GET',
                url: '{{ route('line.get') }}',
                data: {id: lineId},
                success: function (data) {
                    $('#nbl').val(data.data.num_bl)
                    $('#livrasion').val(data.data.name_delivery)
                    $('#qte').val(data.data.qty_line)
                    $('#volume').val(data.data.volume_line)
                    $('#line_id').val(lineId)
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    // Handle the error response here
                }
            });
        });

        $('#lineForm').submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var line_id = $('#line_id').val();
            $.ajax({
                type: 'POST',
                url: '{{route('line.update')}}',
                data: formData,
                success: function (data) {
                    // Handle the success response here
                    Swal.fire(
                        false,
                        data.message,
                        'success'
                    );
                    $('#lineModal').modal('hide'); // Hide the modal after add/update

                    var row = $('#lines-table').DataTable().row('#lineRow-' + line_id).data([
                        data.line.id_distribution_line,
                        data.line.num_bl,
                        data.line.name_delivery,
                        data.line.qty_line,
                        data.line.volume_line,
                        data.line.line_order,
                        '<i class="fas fa-edit edit-line-button data-line-id="' + data.line.id_client + '""></i>',
                        '<i class="fas fa-trash-alt delete-line-button" data-line-id="' + data.line.id_client + '"></i>'
                    ]).draw();

                },
                error: function (xhr, status, error) {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Champs obligatoires!',
                        subtitle: false,
                        body: 'Merci de remplir les champs obligatoires'
                    })
                }
            });

            setTimeout(function () {
                $('.toast').toast('hide');
            }, 3000); // 3000 milliseconds (3 seconds)
        });

    </script>
@endsection
