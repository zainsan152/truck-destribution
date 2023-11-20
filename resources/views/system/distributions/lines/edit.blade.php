@extends('adminlte::page')
@section('title', 'Edit-Distributions-Line')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Toasts', true)
<style>
    .distribution-line-row td {
        text-align: center;
    }

    #distributions-lines-table {
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
</style>
@vite(['resources/js/app.js'])
@section('content')
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; align-items: center;">
                        <h3 class="card-title">List of distributions lines</h3>
                    </div>
                    <div class="card-body">
                        <table id="distributions-lines-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>NBL</th>
                                <th>Nom Livraison l</th>
                                <th>Qte</th>
                                <th>Volume</th>
                                <th>Order de l</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lines as $line)
                                <tr id="distributionRow-{{$line->id_distribution_line}}" class="distribution-line-row">
                                    <td>{{$line->id_distribution_line}}</td>
                                    <td>{{$line->num_bl}}</td>
                                    <td>{{$line->name_delivery}}</td>
                                    <td>{{$line->qty_line}}</td>
                                    <td>{{$line->volume_line}}</td>
                                    <td>{{$line->line_order}}</td>
                                    <td><i class="fas fa-edit edit-line-button"
                                           data-line-id="{{$line->id_distribution_line}}"></i></td>
                                    <td><i class="fas fa-trash-alt delete-line-button"
                                           data-line-id="{{$line->id_distribution_line}}"></i></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
                            Edit
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var table = $('#distributions-lines-table').DataTable({
            responsive: true,
        });

        // Get the CSRF token value from the meta tag in your HTML
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Include the CSRF token in your Ajax request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        $(document).on('click', '.delete-line-button', function () {
            var lineId = $(this).data('line-id');

            // Confirm the deletion with the user (optional)
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
                            table.row('#distributionRow-' + lineId).remove().draw();
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
            $('#lineModal').modal('show');
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
        })

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

                    var row = table.row('#distributionRow-' + line_id).data([
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
    </script>
@endsection
