@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Role</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('role','create'))
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
                @endif
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- <div class="card-title">Management Role</div> --}}
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table-wrapper">
                            <table id="dt_role" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th data-type='text' data-name='nomor' class="text-center">NO</th>
                                    <th data-type='text' data-name='role' class="text-center">ROLE</th>
                                    <th data-type='select' data-name='status' class="text-center">STATUS</th>
                                    <th data-type='text' data-name='action' class="text-center">ACTION</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.role.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#dt_role thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_role thead');

            get_data()

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

        })

        function get_data(){
            $('#dt_role').DataTable().clear().destroy();
            $("#dt_role").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                fixedHeader: {
                    header: true,
                    headerOffset: $('#main_header').height()
                },
                initComplete: function () {

                    $('.dataTables_scrollHead').css('overflow', 'auto');
                    $('.dataTables_scrollHead').on('scroll', function () {
                        // console.log('data')
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });

                    $(document).on('scroll', function () {
                        $('.dtfh-floatingparenthead').on('scroll', function () {
                            $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                        });
                    })

                    this.api().eq(0).columns().every(function (index) {
                        var column = this;
                        var cell = $('.filters th').eq($(column.column(index).header()).index());
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo(cell.empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            }else if (data_type == 'select'){
                                var input = document.createElement("select");
                                var options = "";
                                if (iName == 'status'){
                                    input.className = "status_search form-control custom-select select2";
                                    options += '<option value="">Semua</option>';
                                    @foreach (status_dt() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                }
                                input.innerHTML = options
                                $(input).appendTo(cell.empty())
                                    .on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });

                            }
                        }else {
                            cell.empty()
                        }
                        $('.status_search').select2({
                            placeholder: 'Pilih Status',
                            width: '100%',
                            allowClear: false,
                        })

                    });
                },
                buttons: [
                     { extend: 'pageLength', className: 'mb-5' },
                     { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2]
                        }, title: '',
                        filename: 'Role' }
                ],
                ajax: {
                    url : '{{route("role")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:false},
                    { data: 'nama_role', name: 'nama_role', orderable:true},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3]}
                ]

            })
        }

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_role')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    role: $('#role').val(),
                    status: $('#is_active').val(),
                },
                success:function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result)=>{
                        if (result.value) {
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
                            $('#is_active').val('').trigger("change");
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            get_data()
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                }
            })
        })

        function update_role(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_role')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    role: $('#edit_role'+id).val(),
                    status: $('#edit_is_active'+id).val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    })
                        .then((result) => {
                            if (result.value) {
                                $('#modal_edit'+id).modal('hide')
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                                $("#submit_edit").attr('class', 'btn btn-primary').attr("disabled", false);
                                get_data()
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                }
            })
        }

        function delete_role(id) {
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
                        url: '{{route('delete_role')}}',
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