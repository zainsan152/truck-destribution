@extends('adminlte::page')
@section('title', 'Clients')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Toasts', true)
<style>
    .edit-client-button {
        color: dodgerblue;
        cursor: pointer;
    }

    .delete-client-button {
        color: red;
        cursor: pointer;
    }

    .client-row td {
        text-align: center;
    }
    #clients-table{
        width: 100% !important;
    }
</style>
@vite(['resources/js/app.js'])
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">Liste des clients</h3>
                        <button type="button" class="btn btn-primary" id="openModalBtn" style="margin-left: auto;">Add
                            Client
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="clients-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom Client</th>
                                <th>Ville</th>
                                <th>Adresse</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr id="clientRow-{{$client->id_client}}" class="client-row">
                                    <td>{{$client->code_client}}</td>
                                    <td>{{$client->name_client}}</td>
                                    <td>{{$client->city->city}}</td>
                                    <td>{{$client->adresse}}</td>
                                    <td>
                                        <i class="fas fa-edit edit-client-button"
                                           data-client-id="{{ $client->id_client }}"
                                           data-client-name="{{ $client->name }}"
                                           data-city-id="{{ $client->city_id }}"
                                           data-client-address="{{ $client->address }}"></i>
                                    </td>
                                    <td>
                                        <i class="fas fa-trash-alt delete-client-button"
                                           data-client-id="{{ $client->id_client }}"></i>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding and updating client -->
    <div class="modal fade" id="clientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalTitle">Add Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="clientForm" action="{{ route('client.store') }}" method="POST">
                        @csrf
                        <!-- Add a hidden field to store client ID for updates -->
                        <input type="hidden" name="client_id" id="client_id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nom Client *</label>
                                    <input type="text" name="client_name" class="form-control" id="client_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_id">Ville *</label>
                                    <select name="city_id" id="city_id" class="form-control">
                                        <option value="">Select value</option>
                                        @foreach($cities as $city)
                                            <option value="{{$city->id_city}}">{{$city->city}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Adresse</label>
                                    <textarea name="address" class="form-control" id="address"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float: right;" id="clientActionButton">
                            Submit
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        var table = $('#clients-table').DataTable({
            responsive: true,
            info: false
        });
        // Get the CSRF token value from the meta tag in your HTML
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include the CSRF token in your Ajax request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        $('#openModalBtn').click(function () {
            resetClientModalForm();
            $('#clientModal').modal('show');
        });

        // Function to open the modal for adding and updating clients
        function openClientModal(action, clientData = {}) {
            $('#clientModalTitle').text(action + ' Client');
            $('#clientActionButton').text(action);

            // Populate the form fields with existing data for updates
            $('#client_id').val(clientData.data.id_client || ''); // Assuming "id" is the field name for client ID
            $('#client_name').val(clientData.data.name_client || '');
            $('#city_id option[value="' + clientData.data.id_city + '"]').attr('selected', 'selected');
            $('#address').val(clientData.data.adresse || '');

            $('#clientModal').modal('show');
        }

        // Open the modal for adding a client when the "Add Client" button is clicked
        $('#openClientModal').click(function () {
            openClientModal('Add');
        });

        // Open the modal for updating a client when the "Update Client" button is clicked
        $(document).on('click', '.edit-client-button', function () {
            var clientId = $(this).data('client-id');
            // Make an Ajax request to fetch the client data
            $.ajax({
                type: 'GET',
                url: '{{ route('client.get') }}', // Use the route() helper to generate the URL
                data: {id: clientId}, // Pass the client ID as a parameter
                success: function (data) {
                    openClientModal('Update', data) // Open the modal with client data
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Handle form submission for adding and updating clients
        $('#clientForm').submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var client_id = $('#client_id').val();
            $.ajax({
                type: 'POST',
                url: client_id ? '{{ route('client.update') }}' : '{{ route('client.store') }}',
                data: formData,
                success: function (data) {
                    // Handle the success response here
                    Swal.fire(
                        false,
                        data.message,
                        'success'
                    );
                    $('#clientModal').modal('hide'); // Hide the modal after add/update

                    if (client_id) {
                        // Update the existing row if client ID exists
                        var row = table.row('#clientRow-' + client_id).data([
                            data.client.code_client,
                            data.client.name_client,
                            data.client.city.city,
                            data.client.adresse,
                            '<i class="fas fa-edit edit-client-button" data-client-id="' + data.client.id_client + '" data-client-name="' + data.client.name_client + '" data-city-id="' + data.client.id_city + '" data-client-address="' + data.client.adresse + '"></i>',
                            '<i class="fas fa-trash-alt delete-client-button" data-client-id="' + data.client.id_client + '"></i>'
                        ]).draw();
                    } else {
                        // Add a new row if client ID does not exist
                        var newRow = table.row.add([
                            data.client.code_client,
                            data.client.name_client,
                            data.client.adresse,
                            data.client.city.city,
                            '<i class="fas fa-edit edit-client-button" data-client-id="' + data.client.id_client + '" data-client-name="' + data.client.name_client + '" data-city-id="' + data.client.id_city + '" data-client-address="' + data.client.adresse + '"></i>',
                            '<i class="fas fa-trash-alt delete-client-button" data-client-id="' + data.client.id_client + '"></i>'
                        ]).draw().node();

                        // Assign the ID to the new row
                        $(newRow).attr('id', 'clientRow-' + data.client.id_client);
                        $(newRow).find('td').css('text-align', 'center');
                    }

                },
                error: function (xhr, status, error) {
                    $('#clientModal').modal('hide');
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Required fields',
                        subtitle: false,
                        body: 'Please choose some data for required fields'
                    })
                }
            });

            setTimeout(function () {
                $('.toast').toast('hide');
            }, 3000); // 3000 milliseconds (3 seconds)
        });

        function resetClientModalForm() {
            $('#clientForm')[0].reset(); // This resets the form fields
            $('#client_id').val(''); // Set the hidden input value to an empty string
            $('#city_id option').removeAttr('selected'); // Deselect any selected option
        }

        // Handle the click event for the delete icon
        $(document).on('click', '.delete-client-button', function () {
            var clientId = $(this).data('client-id');

            // Confirm the deletion with the user (optional)
            // Send a DELETE request to delete the client
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('client.destroy') }}', // Use the correct route URL
                        data: {id: clientId}, // Pass the client ID as a parameter
                        success: function (data) {
                            // Handle the success response here
                            Swal.fire(
                                false,
                                data.message,
                                'success'
                            );
                            // Remove the row from table
                            table.row('#clientRow-' + clientId).remove().draw();
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                            // Handle the error response here
                        }
                    });
                }
            })
        });
    </script>
@endsection
