@extends('layouts.app')
@section('title', 'Clients')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true);
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des clients</h3>
                        <button type="button" class="btn btn-primary" id="openModalBtn">Add Client</button>

                    </div>
                    <div class="card-body">
                        <table id="clients-table" class="table table-bordered">
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
                                <tr>
                                    <td>{{$client->code_client}}</td>
                                    <td>{{$client->name_client}}</td>
                                    <td>{{$client->adresse}}</td>
                                    <td>{{$client->city->city}}</td>
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
    <div class="modal" id="clientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
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
                        <div class="form-group">
                            <label for="name">Nom Client:</label>
                            <input type="text" name="client_name" class="form-control" id="client_name" required>
                        </div>
                        <div class="form-group">
                            <label for="city_id">Ville:</label>
                            <select name="city_id" id="city_id" class="form-control" required>
                                <option value="">Select value</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->id_city}}">{{$city->city}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse:</label>
                            <textarea name="address" class="form-control" id="address" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="clientActionButton">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $('#clients-table').DataTable();
        // Get the CSRF token value from the meta tag in your HTML
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include the CSRF token in your Ajax request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        $(document).ready(function () {
            $('#openModalBtn').click(function () {
                $('#clientModal').modal('show');
            });
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
                    openClientModal('Edit', data) // Open the modal with client data
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

            $.ajax({
                type: 'POST',
                url: $('#client_id').val() ? '{{ route('client.update') }}' : '{{ route('client.store') }}',
                data: formData,
                success: function (data) {
                    // Handle the success response here
                    Swal.fire(
                        'Good job!',
                       data.message,
                        'success'
                    );
                    $('#clientModal').modal('hide'); // Hide the modal after add/update
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Handle the click event for the delete icon
        $('.delete-client-button').click(function () {
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
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ route('client.destroy') }}', // Use the correct route URL
                            data: {id: clientId}, // Pass the client ID as a parameter
                            success: function (data) {
                                // Handle the success response here
                                Swal.fire(
                                    'Good job!',
                                    data.message,
                                    'success'
                                );
                                // You can also update the UI or remove the client row if needed
                            },
                            error: function (xhr, status, error) {
                                console.log(xhr.responseText);
                                // Handle the error response here
                            }
                        });
                    }})
            });

    </script>
@endsection
