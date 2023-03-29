@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Kuantiti Rencana Produksi</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            @if (mapping_akses('qty_renprod','create'))
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import"  class="btn btn-outline-primary" id="btn-import"><i class="fe fe-download me-2"></i> Import</button>
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
            <div class="card-body">
                <div class="panel panel-primary">
                    <div class=" tab-menu-heading p-0 bg-light">
                        <div class="tabs-menu1 ">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="" id="tabs_vertical"> <a href="#vertical" class="active" data-bs-toggle="tab">Vertikal</a> </li>
                                <li id="tabs_horizontal"> <a href="#horizontal" data-bs-toggle="tab">Horizontal</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active " id="vertical">
                                <div class="mb-5 row">
                                    @if (auth()->user()->mapping_akses('qty_renprod')->company_code == 'all')
                                        <div class="form-group">
                                            <label class="form-label">PERUSAHAAN</label>
                                            <select id="filter_company_ver" class="form-control custom-select select2">
                                                <option value="all" selected>Semua Perusahaan</option>
                                            </select>
                                        </div>
                                    @endif
                
                                    <div class="form-group">
                                        <label class="form-label">VERSI</label>
                                        <select id="filter_version_ver" class="form-control custom-select select2">
                                            <option value="all" selected>Semua</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="table-responsive" id="table-wrapper">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="horizontal">
                                <div class="mb-5 row">
                                    @if (auth()->user()->mapping_akses('qty_renprod')->company_code == 'all')
                                        <div class="form-group">
                                            <label class="form-label">PERUSAHAAN</label>
                                            <select id="filter_company" class="form-control custom-select select2">
                                            </select>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label class="form-label">VERSI</label>
                                        <select id="filter_version" class="form-control custom-select select2">
                                        </select>
                                    </div>

                                </div>
                                <div class="mt-auto">
                                    <div class="table-responsive" id="dinamic_table">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @include('pages.buku_besar.qty_renprod.add')
            @include('pages.buku_besar.qty_renprod.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')

   <!-- Custom Script -->
   <script src="{{asset('assets/plugins/datatables/Buttons/js/dataTables.buttons.js?v=1.0.1')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.js?v=1.0.2')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.styles.js?v=1.0.0')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.styles.templates.js?v=1.0.1')}}"></script>

    <script>
        const alphabet = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        const alphabet2nd = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        $(document).ready(function () {
            table()

            $('#tabs_vertical').on('click', function () {
                // table()
                $('#dt_qty_renprod').DataTable().ajax.reload();
            })

            $('#filter_company_ver').select2({
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
                $("#table-wrapper").empty();
                table()
            })

            $('#filter_version_ver').select2({
                placeholder: 'Pilih Versi',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('version_dt') }}",
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
                $("#table-wrapper").empty();
                table()
            })

            $('#data_main_cost_center').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Cost Center',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('cost_center_select') }}",
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
            })

            $('#data_main_version').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Versi',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('version_select') }}",
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
                var data_version = $('#data_main_version').val();
                $('#data_detail_version').append('<option selected disabled value="">Pilih Bulan</option>').select2({
                    dropdownParent: $('#modal_add'),
                    placeholder: 'Pilih Bulan',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('version_detail_select') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                version:data_version

                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    }
                });
            })

            $('#version').select2({
                dropdownParent: $('#modal_import'),
                placeholder: 'Pilih Versi',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('version_select') }}",
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
                $("#submit-export").css("display", "block");
            })

            // $('#qty_renprod_value').on('keyup', function(){
            //     let rupiah = formatRupiah($(this).val(), "Rp ")
            //     $(this).val(rupiah)
            // });

            $('#filter_version').select2({
                placeholder: 'Pilih Versi',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('version_select') }}",
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
                $("#dinamic_table").empty();
                get_data_horiz()
            })

            
            $('#filter_company').select2({
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('company_filter_select') }}",
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
                $("#dinamic_table").empty();
                get_data_horiz()
            })

        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_qty_renprod" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                <tr>
                    <th data-type='select' data-name='version' class="text-center">VERSI</th>
                    <th data-type='text' data-name='month_year' class="text-center">PERIODE</th>
                    <th data-type='select' data-name='cost_center' class="text-center">COST CENTER</th>
                    <th data-type='text' data-name='qty_renprod_value' class="text-center">VALUE</th>
                    <th data-type='text' data-name='nomor' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_qty_renprod thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_qty_renprod thead');

            $('#dt_qty_renprod').DataTable().clear().destroy();
            $("#dt_qty_renprod").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
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
                            } else if (data_type == 'select'){
                                var input = document.createElement("select");
                                var options = "";
                                if (iName == 'cost_center'){
                                    input.className = "cost_center_search form-control custom-select select2";

                                } else if(iName == 'version'){
                                    input.className = "version_search form-control custom-select select2";

                                }

                                input.innerHTML = options
                                $(input).appendTo(cell.empty())
                                    .on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                            }
                        } else {
                            cell.empty()
                        }

                        $('.cost_center_search').select2({
                            placeholder: 'Pilih Cost Center',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('cost_center_dt') }}",
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
                        })

                        $('.version_search').select2({
                            placeholder: 'Pilih Versi',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('version_dt') }}",
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
                        })

                    });

                    let api = this.api();
                    api.columns.adjust().draw();
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2,3]
                    }, title: '',
                        filename: 'Kuantiti Rencana Produksi - Vertikal' 
                }
                ],
                ajax: {
                    url : '{{route("qty_renprod")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company_ver').val(),
                        filter_version:$('#filter_version_ver').val()
                    }
                },
                columns: [
                    // { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:false},
                    { data: 'version', name: 'filter_version', orderable:true},
                    { data: 'month_year', name: 'filter_month_year', orderable:true},
                    { data: 'cost_center', name: 'filter_cost_center', orderable:true},
                    { data: 'qty_renprod_value', name: 'filter_qty_renprod_value', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,3,4]}
                ],

            })
        }

        function get_data_horiz(){
            var table = '<table id="h_dt_qty_renprod" class="table table-bordered text-nowrap key-buttons" style="width: 100%;"><thead><tr id="dinamic_tr"></tr></thead></table>'
            var kolom = '<th class="text-center">COST CENTER</th>'
            var column = [
                { data: 'cost_center', orderable:false},
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "GET",
                url : '{{route("qty_renprod")}}',
                data: {
                    data:'version',
                    version:$('#filter_version').val(),
                    company:$('#filter_company').val()
                },
                success:function (response) {
                    for (let i = 0; i < response.asumsi.length;i++){
                        column.push({ data: i.toString(), orderable:false})
                        kolom += '<th class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+'</th>';
                    }
                    $("#dinamic_tr").append(kolom);
                    $('#h_dt_qty_renprod').DataTable().clear().destroy();
                    $("#h_dt_qty_renprod").DataTable({
                        scrollX: true,
                        dom: 'Bfrtip',
                        orderCellsTop: true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        fixedHeader: {
                            header: true,
                            headerOffset: $('#main_header').height()
                        },
                        buttons: [
                            { extend: 'pageLength', className: 'mb-5' },
                            {
                                text: 'Excel',
                                action: function(e, dt, node, config) {
                                    
                                    let version_search = $('#filter_version').val()

                                    window.location = '{{ route("export_h_qty_renprod") }}' + "?version=" + version_search
                                },
                                className: 'mb-5'
                            }
                            // { 
                            //     extend: 'excel', 
                            //     className: 'mb-5',
                            //     title: '',
                            //     filename: 'Kuantiti Rencana Produksi - Horizontal',
                            //     exportOptions: {
                            //         format: {
                            //             body: function ( data, row, kolom, node ) {
                            //                 if (typeof data === 'undefined') {
                            //                     return;
                            //                 }
                            //                 if (data == null) {
                            //                     return data;
                            //                 }
                            //                 if ( kolom !== 0) {                      
                            //                     var arr = data.split(',');
                            //                     arr[0] = arr[0].toString().replace( /[\.]/g, "" );
                            //                     if (arr[0] > ''  || arr[1] > '') {
                            //                         data = arr[0] + '' + arr[1];
                            //                     } else {
                            //                         return '';
                            //                     }
                            //                     return data.toString().replace( /[^\d.-]/g, "" );    
                            //                 }
                            //                 return data;
                            //             }
                            //         }
                            //     }
                            //  }
                        ],
                        ajax: {
                            url : '{{route("qty_renprod")}}',
                            data: {
                                data:'horizontal',
                                version:$('#filter_version').val(),
                                company:$('#filter_company').val()
                            }
                        },
                        columns: column,
                        initComplete: function( settings ) {
                            let api = this.api();
                            api.columns.adjust().draw();
                        }
                    })
                }
            })
        }

        function update_dt_horizontal() {
            if ($('#filter_version').val() != null){
                $("#dinamic_table").empty();
                get_data_horiz()
            }
        }

        $('#submit').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_qty_renprod')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    cost_center: $('#data_main_cost_center').val(),
                    qty_renprod_value: $('#qty_renprod_value').val(),
                    version_id: $('#data_main_version').val(),
                    month_year: $('#data_detail_version').val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        if (result.value) {
                            $('#modal_add').modal('hide')
                            $("#modal_add input").val("")
                            $('#data_main_version').val('').trigger("change");
                            $('#data_detail_version').val('').trigger("change");
                            $('#data_main_cost_center').val('').trigger("change");
                            update_dt_horizontal()
                            // table()
                            $('#dt_qty_renprod').DataTable().ajax.reload();
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
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('check_qty_renprod')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#version').val()
                },
                success: function (response) {
                    if (response.code == 201) 
                    {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: 'warning',
                            allowOutsideClick: false,
                            showDenyButton: true,
                            confirmButtonColor: "#019267",
                            confirmButtonText: 'Konfirmasi',
                            denyButtonText: 'Kembali',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                importStore()
                            } else {
                                $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back-import").attr("disabled", false);
                            }
                        })
                    } else {
                        importStore()
                        // $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    }
                },
                error: function (response) {
                    handleError(response)
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                }
            })
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
                url: '{{route('import_qty_renprod')}}',
                data: file,
                success:function (response) {
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        if (result.value) {
                            $('#modal_import').modal('hide')
                            $("#modal_import input").val("")

                            update_dt_horizontal()
                            // table()
                            $('#dt_qty_renprod').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                }
            })
        }

        $('#submit-export').on('click', function () {
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('export_qty_renprod')}}',
                data: {
                    version: $('#version').val(),
                },
                success:function (result, status, xhr) {
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : 'qty_renprod.xlsx');

                    // The actual download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                },
                error: function(){
                    $('#modal_import').modal('hide');
                    $("#modal_import input").val("")
                    toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                }
            })
        })

        function update_qty_renprod(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_qty_renprod')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    cost_center: $('#edit_data_main_cost_center'+id).val(),
                    qty_renprod_value: $('#edit_qty_renprod_value'+id).val(),
                    version_id: $('#edit_data_main_version'+id).val(),
                    month_year:$('#edit_data_detail_version'+id).val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        console.log(result)
                        if (result.value) {
                            $('#modal_edit'+id).modal('hide')
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            
                            update_dt_horizontal()
                            // table()
                            $('#dt_qty_renprod').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })

        }

        function delete_qty_renprod(id) {
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
                        url: '{{route('delete_qty_renprod')}}',
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
                                confirmButtonColor: "#019267",
                                confirmButtonText: 'Konfirmasi',
                            })
                            .then((result) => {
                                if (result.value) {
                                    update_dt_horizontal()
                                    // table()
                                    $('#dt_qty_renprod').DataTable().ajax.reload();
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
