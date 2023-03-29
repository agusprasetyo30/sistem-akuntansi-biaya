@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Kurs</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('kurs','create'))
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
                    <div class="card-title">Management Kurs</div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table_main">
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.kurs.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_kurs" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="tahun" class="text-center">PERIODE</th>' +
            '<th data-type="text" data-name="usd" class="text-center">KURS</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {
            get_data()

            $('#tanggal').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true,
                showOnFocus: false,
            }).on('click', function () {
                $('#tanggal').bootstrapdatepicker("show");
            });

            $('#currency').on('keyup', function(){
                let rupiah = formatRupiah($(this).val(), "Rp ")
                $(this).val(rupiah)
            });

        })

        function get_data(){

            $('#table_main').html(table_main_dt)

            $('#dt_kurs thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_kurs thead');

            // $('#dt_kurs').DataTable().clear().destroy();
            $("#dt_kurs").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
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

                                if(iName == 'usd'){
                                    input.id = 'usd_search'
                                }

                                $(input).
                                appendTo(cell.empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });

                                $('#usd_search').on('keyup', function(){
                                    let rupiah = formatRupiah($(this).val(), "Rp ")
                                    $(this).val(rupiah)
                                });

                            }else if (data_type == 'select'){
                                var input = document.createElement("select");
                                input.className = "form-control custom-select select2";
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
                        }else {
                            cell.empty()
                        }

                    });
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    // { extend: 'excel', className: 'mb-5', exportOptions:{
                    //     columns:[0,1]
                    //     }, title: '',
                    //     filename: 'Kurs'
                    // },
                    {
                        text: 'Excel',
                        action: function(e, dt, node, config) {
                            let route_default = '{{ route("export_kurs") }}'
                            let route_complete = route_default 
                                // + "?version=" + '3' + "&produk=" + '2000002' + "&plant=" + 'B030' +
                                // "&cost_center=" +
                                // 'undefined';

                            // ?version=3&produk=2000002&plant=B029&    cost_center=
                            window.location = route_complete
                        },
                        className: 'mb-5'
                    }
                ],
                ajax: {
                    url : '{{route("kurs")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'periode', name: 'filter_periode', orderable:true},
                    { data: 'mata_uang', name: 'filter_mata_uang', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

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
                url: '{{route('insert_kurs')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: $('#tanggal').val(),
                    kurs: $('#currency').val(),
                },
                success:function (response) {
                    // $("#tanggal").attr("disabled", true);
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result) => {
                        if (result.value) {
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
                            $('#currency').removeClass('is-invalid');
                            $('#currency').removeClass('is-valid');
                            $('#tanggal').removeClass('is-invalid');
                            $('#tanggal').removeClass('is-valid');
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            // $("#tanggal").attr("disabled", false);
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_kurs').DataTable().ajax.reload();
                        }
                    })
                },
                error:function (response) {
                    console.log(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    handleError(response)
                }
            })
        })



        function update_kurs(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_kurs')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    tanggal: $('#edit_tanggal'+id).val(),
                    kurs: $('#edit_currency'+id).val(),
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
                                $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit"+id).attr("disabled", false);
                                $('#dt_kurs').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    handleError(response)
                }
            })
        }

        function delete_kurs(id) {
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
                        url: '{{route('delete_kurs')}}',
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
                            })
                                .then((result) => {
                                    if (result.value) {
                                        // $("#table_main").empty();
                                        // get_data()
                                        $('#dt_kurs').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_kurs').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
@endsection
