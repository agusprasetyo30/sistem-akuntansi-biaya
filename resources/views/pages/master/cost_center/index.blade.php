@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Cost Center</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('cost_center','create'))
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import"  class="btn btn-outline-primary" id="btn-import"><i class="fe fe-download me-2"></i> Import</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add" class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add
                @endif
                </button>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">COST CENTER</div>
                </div>
                <div class="card-body">
                    <div class="mb-5 row">
                        @if (auth()->user()->mapping_akses('cost_center')->company_code == 'all')
                            <div class="form-group">
                                <label class="form-label">PERUSAHAAN</label>
                                <select id="filter_company" class="form-control custom-select select2">
                                    <option value="all" selected>Semua Perusahaan</option>
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="">
                        <div class="table-responsive" id="table_main">

                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.cost_center.add')
            @include('pages.master.cost_center.import')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_cost_center" class="table table-bordered text-wrap key-buttons warp" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="cost_center" class="text-center">COST CENTER</th>' +
            '<th data-type="text" data-name="deskripsi" class="text-center">DESKRIPSI</th>' +
            '<th data-type="text" data-name="nomor" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '</table>'
        $(document).ready(function () {
            get_data()

            $('#code_cost_center').keyup(function(){
                this.value = this.value.toUpperCase();
            });

            $('#filter_company').select2({
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{route('main_company_filter_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            }).on('change', function () {
                $("#table_main").empty();
                get_data()
            })
        })


        function get_data() {
            $('#table_main').html(table_main_dt)

            $('#dt_cost_center thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_cost_center thead');

            $("#dt_cost_center").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                sortable: false,
                processing: true,
                serverSide: true,
                deferRender:true,
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

                    this.api().eq(0).columns().every(function (index) {
                        var column = this;
                        var cell = $('.filters th').eq($(column.column(index).header()).index());
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable) {
                            if (data_type == 'text') {
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo(cell.empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            } else if (data_type == 'select') {
                                var input = document.createElement("select");
                                input.className = "form-control custom-select select2";
                                var options = "";
                                if (iName == 'status') {
                                    options += '<option value="">Semua</option>';
                                    @foreach (status_is_active() as $key => $value)
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

                    });
                },
                buttons: [
                    {extend: 'pageLength', className: 'mb-5'},
                    {
                        extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1]
                        }, title: '',
                        filename: 'Consumption Center bagas'
                    }
                ],
                ajax: {
                    url: '{{route("cost_center")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company').val(),
                    }
                },
                columns: [
                    {data: 'cost_center', name: 'filter_cost_center', orderable: true},
                    {data: 'cost_center_desc', name: 'filter_cost_center_desc', orderable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2]}
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
                url: '{{route('insert_cost_center')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    code: $('#code_cost_center').val(),
                    deskripsi: $('#cost_center_desc').val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result) =>{
                        $('#modal_add').modal('hide');
                        $("#modal_add input").val("")
                        $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                        $('#dt_cost_center').DataTable().ajax.reload();
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    // $('#dt_cost_center').DataTable().ajax.reload();
                }
            })
        })

        $('#submit-import').on('click', function () {
            $("#submit-import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back-import").attr("disabled", true);
            if ($('#file').val() !== ''){
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan segera import",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#019267',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonText: 'Konfirmasi',
                    cancelButtonText: 'Kembali'
                }).then((result) =>{
                    if (result.value){
                        importStore()
                    }
                })

            }else {
                Swal.fire({
                    title: 'PERINGATAN',
                    text: "Data file tidak boleh kosong",
                    icon: 'warning',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonColor: '#019267',
                    confirmButtonText: 'Konfirmasi',
                }).then((result)=>{
                    if (result.value){
                        $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back-import").attr("disabled", false);
                    }
                })
            }

        })

        function importStore(){
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_cost_center')}}',
                data: file,
                success:function (response) {
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    })
                        .then((result) => {
                            if (result.value) {
                                $('#modal_import').modal('hide');
                                $("#modal_import input").val("")
                                $('#dt_cost_center').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                    // $('#dt_cost_center').DataTable().ajax.reload();
                }
            })
        }

        function update_cost_center(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_cost_center')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    code: $('#edit_code_cost_center' + id).val(),
                    deskripsi: $('#edit_cost_center_desc' + id).val(),
                },
                success: function (response) {
                    console.log('s')
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result) => {
                        if (result.value){
                            $('#modal_edit'+id).modal('hide')
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                            $("#back_edit"+id).attr("disabled", false);
                            $('#dt_cost_center').DataTable().ajax.reload();
                        }

                    })
                },
                error: function (response) {
                    console.log('f')
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    // $('#dt_cost_center').DataTable().ajax.reload();
                }
            })
        }

        function delete_cost_center(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan segera dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#019267',
                cancelButtonColor: '#EF4B4B',
                confirmButtonText: 'Konfirmasi',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('delete_cost_center')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false,
                                confirmButtonColor: '#019267',
                                confirmButtonText: 'Konfirmasi',
                            }).then((result) =>{
                                if (result.value){
                                    $('#dt_cost_center').DataTable().ajax.reload();
                                }
                            })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_cost_center').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
    {{--    <script src="{{asset('assets/js/pages/regions.js')}}"></script>--}}
@endsection
