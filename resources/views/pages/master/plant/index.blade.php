@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Plant</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            {{-- <button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button> --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import"  class="btn btn-outline-primary" id="btn-import"><i class="fe fe-download me-2"></i> Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Plant</div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                        <table id="dt_plant" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='nama' class="border-bottom-0 text-center">KODE PLANT</th>
                                <th data-type='text' data-name='deskripsi' class="border-bottom-0 text-center">DESKRIPSI</th>
                                <th data-type='select' data-name='status' class="border-bottom-0 text-center">STATUS</th>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                                <th data-type='text' data-name='nama' class="text-center"></th>
                                <th data-type='text' data-name='deskripsi' class="text-center"></th>
                                <th data-type='select' data-name='status' class="text-center"></th>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.master.plant.add')
        @include('pages.master.plant.import')
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            get_data()

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#code_plant').keyup(function(){
                this.value = this.value.toUpperCase();
            });
        })

        function get_data(){
            $('#dt_plant').DataTable().clear().destroy();
            $("#dt_plant").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
                fixedHeader: {
                    header: true,
                    headerOffset: $('#main_header').height()
                },
                initComplete: function () {
                        $('.dataTables_scrollHead').css('overflow', 'auto');
                        $('.dataTables_scrollHead').on('scroll', function () {
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });

                    $(document).on('scroll', function () {
                        $('.dtfh-floatingparenthead').on('scroll', function () {
                            $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                        });
                    })

                    this.api().columns().every(function (index) {
                        var column = this;
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo($(column.header()).empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            }else if (data_type == 'select'){
                                var input = document.createElement("select");
                                input.className = "form-control form-control-sm custom-select select2";
                                var options = "";
                                if (iName == 'status'){
                                    options += '<option value="">Semua</option>';
                                    @foreach (status_is_active() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                }
                                input.innerHTML = options
                                $(input).appendTo($(column.header()).empty())
                                    .on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });

                            }
                        }

                    });
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5' }
                ],
                ajax: {
                    url : '{{route("plant")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'plant_code', searchable: false, orderable:true},
                    { data: 'plant_code', name: 'plant_code', orderable:false},
                    { data: 'plant_desc', name: 'plant_desc', orderable:false},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,4,3]}
                ],

            })
        }

        $('#submit').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_plant')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    code: $('#code_plant').val(),
                    deskripsi: $('#deskripsi_plant').val(),
                    is_active: $('#is_active').val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false
                    })
                    .then((result) => {
                        if (result.value) {
                            get_data()
                            $('#modal_add').modal('hide')
                            $("#modal_add input").val("")
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })

        })

        $('#submit-import').on('click', function () {
            $("#submit-import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back-import").attr("disabled", true);
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_plant')}}',
                data: file,
                success:function (response) {
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.type,
                        allowOutsideClick: false
                    })
                    .then((result) => {
                        if (result.value) {
                            get_data()
                            $('#modal_import').modal('hide')
                            $("#modal_import input").val("")
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                }
            })

        })

        function update_plant(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_plant')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    code: $('#edit_code_plant'+id).val(),
                    deskripsi: $('#edit_deskripsi_plant'+id).val(),
                    is_active: $('#edit_is_active'+id).val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false
                    })
                    .then((result) => {
                        if (result.value) {
                            get_data()
                            $('#modal_edit'+id).modal('hide')
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        }

        function delete_plant(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan segera dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#019267',
                cancelButtonColor: '#EF4B4B',
                confirmButtonText: 'Konfirmasi',
                cancelButtonText: 'Kembali'
            }).then((result) =>{
                if (result.value){

                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('delete_plant')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false
                            })
                            .then((result) => {
                                if (result.value) {
                                    get_data()
                                }
                            })
                        },
                        error: function (response) {
                            handleError(response)
                        }
                    })

                }

            })
        }
    </script>
@endsection
