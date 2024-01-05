@extends('adminlte::page')
@section('title', 'Distributions Planifiées')
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
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">Liste des distributions planifiées</h3>
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
                            @foreach($distributionsplanifiees as $distribution)
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
                <!-- <div class="modal-footer">
                    <i type="button" class="fas fa-trash-alt btn btn-secondary" id="delete-distribution-btn"> Solder
                        Distribution</i>
                    <a type="button" class="fas fa-edit btn btn-secondary" id="edit-lines-btn"> Modifier
                        Distribution</a>
                    <i type="button" class="fas fa-file btn btn-secondary" id="planifier-btn"> Planifier
                        Distribution</i>
                </div> -->
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
}});

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
                    modalTitle.text('Details Distribution Ship00' + distributionDetails.code_distribution);

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
                        tableHtml += '<tr id="lineRow-' + line.id_distribution_line + '">';
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
                            title: 'Champs obligatoires!',
                            subtitle: false,
                            body: 'Merci de remplir les champs obligatoires'
                        })
                    }
                }
            });
            setTimeout(function () {
                $('.toast').toast('hide');
            }, 3000);
        });

    </script>
@endsection
