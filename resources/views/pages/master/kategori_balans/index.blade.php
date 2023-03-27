@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Kategori Balans</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('kategori_balans','create'))
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
                    <div class="card-title">Kategori Balans</div>
                </div>
                <div class="card-body">
                    <div class="mb-5 row">
                        @if (auth()->user()->mapping_akses('kategori_balans')->company_code == 'all')
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
            @include('pages.master.kategori_balans.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_kategori_balans" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="kategori_produk" class="text-center">KATEGORI BALANS</th>' +
            '<th data-type="text" data-name="deskripsi" class="text-center">DESKRIPSI</th>' +
            '<th data-type="text" data-name="tipe" class="text-center">TIPE</th>' +
            '<th data-type="text" data-name="urutan" class="text-center">URUTAN</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {
            get_data()

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

            $('#urutan').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Urutan',
                width: '100%'
            })

            $('#type_kategori_balans').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Tipe Kategori',
                width: '100%'
            })
        })

        function get_data(){

            $('#table_main').html(table_main_dt)

            $('#dt_kategori_balans thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_kategori_balans thead');

            // $('#dt_kategori_balans').DataTable().clear().destroy();
            $("#dt_kategori_balans").DataTable({
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
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1]
                        }, title: '',
                        filename: 'Kategori Balans'
                    }
                ],
                ajax: {
                    url : '{{route("kategori_balans")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company').val(),
                    }
                },
                columns: [
                    { data: 'kategori_balans', name: 'filter_kategori_balans', orderable:true},
                    { data: 'kategori_balans_desc', name: 'filter_kategori_balans_desc', orderable:true},
                    { data: 'type_kategori', name: 'filter_type_kategori', orderable:true},
                    { data: 'urutan', name: 'filter_urutan', orderable:true},
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
                url: '{{route('insert_kategori_balans')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    kategori_balans: $('#kategori_balans').val(),
                    kategori_balans_desc: $('#kategori_balans_desc').val(),
                    type_kategori_balans: $('#type_kategori_balans').val(),
                    urutan: $('#urutan').val(),
                },
                success:function (response) {
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
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            // $("#tanggal").attr("disabled", false);
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_kategori_balans').DataTable().ajax.reload();
                        }
                    })
                },
                error:function (response) {
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    handleError(response)
                }
            })
        })



        function update_kategori_balans(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_kategori_balans')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    kategori_balans: $('#edit_kategori_balans'+id).val(),
                    kategori_balans_desc: $('#edit_deskripsi'+id).val(),
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
                                $('#dt_kategori_balans').DataTable().ajax.reload();
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

        function delete_kategori_balans(id) {
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
                        url: '{{route('delete_kategori_balans')}}',
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
                                        $('#dt_kategori_balans').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_kategori_balans').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
@endsection
