@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Price Rencana Pengadaan</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            {{-- <div class="card-header">
                <div class="card-title">Price Pengadaan</div>
            </div> --}}
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
                                <div class="">
                                    <div class="table-responsive" id="table_main">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="horizontal">
                                <div class="mb-2 row">
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
            @include('pages.buku_besar.price_rendaan.add')
            @include('pages.buku_besar.price_rendaan.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_price_rendaan" class="table table-bordered text-wrap wrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="select" data-name="version" class="text-center">VERSI</th>' +
            '<th data-type="text" data-name="periode" class="text-center">PERIODE</th>' +
            '<th data-type="select" data-name="material" class="text-center">MATERIAL</th>' +
            '<th data-type="text" data-name="region" class="text-center">REGION</th>' +
            '<th data-type="text" data-name="price_rendaan_value" class="text-center">VALUE</th>' +
            '<th data-type="text" data-name="aksi" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {

            get_data()

            $('#tabs_vertical').on('click', function () {
                // $("#table_main").empty();
                // get_data()
                $('#dt_price_rendaan').DataTable().ajax.reload();
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
                $('#data_detal_version').append('<option selected disabled value="">Pilih Bulan</option>').select2({
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
                $("#template").css("display", "block");
            })

            $("#template").on('click', function () {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhrFields:{
                        responseType: 'blob'
                    },
                    url: '{{route('export_price_rendaan')}}',
                    data: {
                        temp:$('#version').val()
                    },
                    success: function(result, status, xhr) {

                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'price_rendaan.xlsx');

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
                    }
                })
            })

            $('#data_main_material').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Material',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('material_select') }}",
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

            $('#data_main_region').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih region',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('region_select') }}",
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

            $('#submit_import').on('click', function () {
                $("#submit_import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
                $("#back_import").attr("disabled", true);

                if ($('#version').val() !== null && $('#file').val() !== ''){
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('check_price_rendaan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version:$('#version').val()
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                Swal.fire({
                                    title: 'Apakah anda yakin?',
                                    text: "Data akan segera dikirim",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#019267',
                                    cancelButtonColor: '#EF4B4B',
                                    confirmButtonText: 'Konfirmasi',
                                    cancelButtonText: 'Kembali'
                                }).then((result) =>{
                                    if (result.value){
                                        importStore()
                                    }else {
                                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                                        $("#back_import").attr("disabled", true);
                                    }
                                })
                            }else if (response.Code === 201){
                                Swal.fire({
                                    title: 'Apakah anda yakin?',
                                    text: "Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#019267',
                                    cancelButtonColor: '#EF4B4B',
                                    confirmButtonText: 'Konfirmasi',
                                    cancelButtonText: 'Kembali'
                                }).then((result) =>{
                                    if (result.value){
                                        importStore()
                                    }else {
                                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                                        $("#back_import").attr("disabled", false);
                                    }
                                })
                            }
                        },
                        error: function (response) {
                            handleError(response)
                            $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                            $("#back_import").attr("disabled", false);
                        }
                    })
                }else {
                    Swal.fire({
                        title: 'PERINGATAN',
                        text: "Silakan Isi Data Tersebut",
                        icon: 'warning',
                        confirmButtonColor: '#019267',
                        cancelButtonColor: '#EF4B4B',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result)=>{
                        if (result.value){
                            $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                            $("#back_import").attr("disabled", false);
                        }
                    })
                }

            })

            function importStore() {
                let file = new FormData($("#form_input_price_daan")[0]);
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    url: '{{route('import_price_rendaan')}}',
                    data: file,
                    success:function (response) {
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
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
                                    $('#modal_import').modal('hide')
                                    $("#modal_import input").val("")
                                    update_dt_horizontal()
                                    // $("#table_main").empty();
                                    // get_data()
                                    $('#dt_price_rendaan').DataTable().ajax.reload();
                                }
                            })
                    },
                    error: function (response) {
                        handleError(response)
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                        // $('#dt_price_rendaan').DataTable().ajax.reload();
                    }
                })
            }

            $('#price_rendaan_value').on('keyup', function(){
                let rupiah = formatRupiah($(this).val(), "Rp ")
                $(this).val(rupiah)
            });

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
        })

        function get_data(){
            $('#table_main').html(table_main_dt)

            $('#dt_price_rendaan thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_price_rendaan thead');

            // $('#dt_price_rendaan').DataTable().clear().destroy();
            $("#dt_price_rendaan").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth: true,
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

                                if(iName == 'price_rendaan_value'){
                                    input.id = 'price_rendaan_value_search'
                                }

                                $(input).
                                appendTo(cell.empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });

                                $('#price_rendaan_value_search').on('keyup', function(){
                                    let rupiah = formatRupiah($(this).val(), "Rp ")
                                    $(this).val(rupiah)
                                });
                            }
                            else if (data_type == 'select'){
                                var input = document.createElement("select");
                                var options = "";
                                if (iName == 'status'){
                                    input.className = "status_search form-control custom-select select2";
                                    @foreach (status_dt() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                }else if (iName == 'material'){
                                    input.className = "material_search form-control custom-select select2";

                                }else if (iName == 'version'){
                                    input.className = "version_search form-control custom-select select2";

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

                        $('.material_search').select2({
                            placeholder: 'Pilih Material',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('material_dt') }}",
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
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    {
                        extend: 'collection',
                        className: 'mb-5',
                        text:'Mata Uang',
                        buttons:[
                            {
                                text:'Rupiah',
                                action: function () {
                                    $('#dt_price_rendaan').DataTable().ajax.url('{{route('price_rendaan', ['currency' => 'Rupiah'])}}').load();
                                }
                            },
                            {
                                text:'Dollar',
                                action: function () {
                                    $('#dt_price_rendaan').DataTable().ajax.url('{{route('price_rendaan', ['currency' => 'Dollar'])}}').load();
                                }
                            }
                        ]

                    },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2,3,4]
                    }, title: 'Price Rencana Pengadaan'  }
                ],
                ajax: {
                    url : '{{route("price_rendaan")}}',
                    data: {data:'index'}
                },
                columns: [
                    { width: "15%", data: 'version', name: 'filter_version', orderable:true},
                    { width: "10%", data: 'periode', name: 'filter_periode', orderable:true},
                    { width: "20%", data: 'material', name: 'filter_material', orderable:true},
                    { width: "15%", data: 'region_desc', name: 'filter_region', orderable:true},
                    { width: "20%", data: 'value', name: 'price_rendaan_value', orderable:true, searchable: false},
                    { width: "20%", data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3,4,5]}
                ]

            }).columns.adjust().draw();
        }

        function get_data_horiz(){
            var table = '<table id="h_dt_price_rendaan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;"><thead><tr id="dinamic_tr"></tr></thead></table>'
            var kolom = '<th class="text-center">MATERIAL</th><th class="text-center">REGION</th>'
            var column = [
                { data: 'material', orderable:false},
                { data: 'region_desc', orderable:false}
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "GET",
                url : '{{route("qty_rendaan")}}',
                data: {
                    data:'version',
                    version:$('#filter_version').val()
                },
                success:function (response) {
                    for (let i = 0; i < response.asumsi.length;i++){
                        column.push({ data: i.toString(), orderable:false})
                        kolom += '<th class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+'</th>';
                    }
                    $("#dinamic_tr").append(kolom);
                    $('#h_dt_price_rendaan').DataTable().clear().destroy();
                    $("#h_dt_price_rendaan").DataTable({
                        scrollX: true,
                        dom: 'Bfrtip',
                        orderCellsTop: true,
                        processing: true,
                        serverSide: true,
                        fixedHeader: {
                            header: true,
                            headerOffset: $('#main_header').height()
                        },
                        buttons: [
                            { extend: 'pageLength', className: 'mb-5' },
                            {
                                extend: 'collection',
                                className: 'mb-5',
                                text:'Mata Uang',
                                buttons:[
                                    {
                                        text:'Rupiah',
                                        action: function () {
                                            $('#h_dt_price_rendaan').DataTable().ajax.url('{{route('price_rendaan', ['currency' => 'Rupiah'])}}').load();
                                        }
                                    },
                                    {
                                        text:'Dollar',
                                        action: function () {
                                            $('#h_dt_price_rendaan').DataTable().ajax.url('{{route('price_rendaan', ['currency' => 'Dollar'])}}').load();
                                        }
                                    }
                                ]

                            },
                            { extend: 'excel', className: 'mb-5' }
                        ],
                        ajax: {
                            url : '{{route("price_rendaan")}}',
                            data: {
                                data:'horizontal',
                                version:$('#filter_version').val()
                            }
                        },
                        columns: column,

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
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_price_rendaan')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version_asumsi:$('#data_main_version').val(),
                    bulan:$('#data_detal_version').val(),
                    material_id: $('#data_main_material').val(),
                    region_id: $('#data_main_region').val(),
                    price_rendaan_value: $('#price_rendaan_value').val(),
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
                            $('#data_main_plant').val('').trigger("change");
                            $('#data_main_version').val('').trigger("change");
                            $('#data_detal_version').val('').trigger("change");
                            $('#data_main_produk').val('').trigger("change");
                            $('#data_main_material').val('').trigger("change");
                            $('#is_active').val('').trigger("change");
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            update_dt_horizontal()
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_price_rendaan').DataTable().ajax.reload();
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    // $('#dt_price_rendaan').DataTable().ajax.reload();
                }
            })
        })

        function update_price_rendaan(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_price_rendaan')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    version_asumsi:$('#edit_data_main_version'+id).val(),
                    bulan:$('#edit_data_detal_version'+id).val(),
                    material_id: $('#edit_data_main_material'+id).val(),
                    region_id: $('#edit_data_main_region'+id).val(),
                    price_rendaan_value: $('#edit_price_rendaan_value'+id).val(),
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
                                update_dt_horizontal()
                                // $("#table_main").empty();
                                // get_data()
                                $('#dt_price_rendaan').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    // $('#dt_price_rendaan').DataTable().ajax.reload();
                }
            })
        }

        function delete_price_rendaan(id) {
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
                        url: '{{route('delete_price_rendaan')}}',
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
                                        update_dt_horizontal()
                                        // $("#table_main").empty();
                                        // get_data()
                                        $('#dt_price_rendaan').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_price_rendaan').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
@endsection
