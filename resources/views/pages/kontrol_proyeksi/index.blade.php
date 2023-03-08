@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Kontrol Proyeksi</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button> --}}
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Kontrol Proyeksi</div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="form-group" id="cost_center_pick">
                            <label class="form-label">Perusahaan <span class="text-red">*</span></label>
                            <select id="filter_company_code" class="form-control custom-select select2">
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                            <select name="main_version" id="data_main_version" class="form-control custom-select select2">
                                <option value="" disabled selected>Pilih Versi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bulan <span class="text-red">*</span></label>
                            <select name="detail_version" id="data_detal_version" class="form-control custom-select select2">
                                <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>
                            </select>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label class="form-label">Bulan </label>--}}
{{--                            <input type="text" class="form-control" name="filter_periode" id="filter_periode" placeholder="Bulan-Tahun" autocomplete="off" required>--}}
{{--                        </div>--}}
                        <div class="btn-list">
                            <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
                        </div>
                    </div>

                    <div class="panel panel-primary" id="main_tab" style="display: none;">
                        <div class=" tab-menu-heading p-0 bg-light">
                            <div class="tabs-menu1 ">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li id="tabs_simulasi"> <a href="#simulasi" class="active" data-bs-toggle="tab">Parameter Simulasi</a> </li>
                                    <li id="tabs_biaya_tetap"> <a href="#biaya_tetap" data-bs-toggle="tab">kelengkapan Biaya Tetap</a> </li>
                                    <li id="tabs_harga_material"> <a href="#harga_material" data-bs-toggle="tab">kelengkapan Harga Material</a> </li>
                                    <li id="tabs_bom"> <a href="#bom" data-bs-toggle="tab">Kelengkapan BOM</a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="simulasi">
                                    <div  class="mb-4">
                                        <div class="mb-4">
                                            <div class="table-responsive" id="parameter_simulasi"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="biaya_tetap">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <div class="table-responsive" id="kelengkapan_biaya_tetap"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="harga_material">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <div class="table-responsive" id="kelengkapan_harga_material"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="bom">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <div class="table-responsive" id="kelengkapan_bom"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_parameter_simulasi_dt = '<table id="dt_parameter_simulasi" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="no" class="text-center">NO</th>' +
            '<th data-type="text" data-name="parameter" class="text-center">PARAMETER PROYEKSI</th>' +
            '<th data-type="text" data-name="jumlah" class="text-center">JUMLAH DATA</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        var table_kelengkapan_biaya_tetap_dt = '<table id="dt_kelengkapan_biaya_tetap" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="company" class="text-center">COMPANY CODE</th>' +
            '<th data-type="text" data-name="cost_center" class="text-center">COST CENTER</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        var table_kelengkapan_harga_material_dt = '<table id="dt_kelengkapan_harga_material" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="company" class="text-center">COMPANY CODE</th>' +
            '<th data-type="text" data-name="region" class="text-center">REGION</th>' +
            '<th data-type="text" data-name="material" class="text-center">MATERIAL</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        var table_kelengkapan_bom_dt = '<table id="dt_kelengkapan_bom" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="company" class="text-center">COMPANY CODE</th>' +
            '<th data-type="text" data-name="plant" class="text-center">PLANT</th>' +
            '<th data-type="text" data-name="material" class="text-center">MATERIAL</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {

            $('#btn_tampilkan').on('click', function () {
                var company = $('#filter_company_code').val();
                var versi = $('#data_main_version').val();
                var asumsi = $('#data_detal_version').val();
                // var periode = $('#filter_periode').val();

                if (company !== null && versi !== null && asumsi !== null){
                    $('#main_tab').css('display', 'block')
                    // $('#tabs_simulasi').trigger('click')
                    parameter_simulasi(company, versi, asumsi)

                }else {
                    Swal.fire({
                        title: 'PERINGATAN',
                        text: "Terdapat Data Perusahaan dan Periode yang kosong. Silakan Isi data tersebut",
                        icon: 'warning',
                        confirmButtonColor: '#019267',
                        cancelButtonColor: '#EF4B4B',
                        confirmButtonText: 'Konfirmasi',
                    })
                }


            })

            $('#data_main_version').select2({
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


            $('#filter_periode').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true,
                showOnFocus: false,
            }).on('click', function () {
                $('#filter_periode').bootstrapdatepicker("show");
            }).on('change', function () {
                $('#main_tab').css('display', 'none')
            });

            $('#filter_company_code').select2({
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{route('company_select') }}",
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

            $('#tabs_simulasi').on('click', function () {
                var company = $('#filter_company_code').val();
                var versi = $('#data_main_version').val();
                var asumsi = $('#data_detal_version').val();
                parameter_simulasi(company, versi, asumsi)
            })

            $('#tabs_biaya_tetap').on('click', function () {
                var company = $('#filter_company_code').val();
                var versi = $('#data_main_version').val();
                var asumsi = $('#data_detal_version').val();
                kelengkapan_biaya_tetap(company, versi, asumsi)
            })

            $('#tabs_harga_material').on('click', function () {
                var company = $('#filter_company_code').val();
                var versi = $('#data_main_version').val();
                var asumsi = $('#data_detal_version').val();
                kelengkapan_harga_material(company, versi, asumsi)
            })

            $('#tabs_bom').on('click', function () {
                var company = $('#filter_company_code').val();
                var versi = $('#data_main_version').val();
                var asumsi = $('#data_detal_version').val();
                kelengkapan_bom(company, versi, asumsi)
            })

        })

        function parameter_simulasi(company, versi, asumsi) {
            $('#parameter_simulasi').html(table_parameter_simulasi_dt)

            $('#dt_parameter_simulasi thead tr')
                .clone(true)
                .addClass('filters_parameter_simulasi')
                .appendTo('#dt_parameter_simulasi thead');

            $('#dt_parameter_simulasi').DataTable().clear().destroy();
            $("#dt_parameter_simulasi").DataTable({
                // scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                scrollCollapse: true,
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
                deferRender:true,
                // fixedHeader: {
                //     header: true,
                //     headerOffset: $('#main_header').height()
                // },
                initComplete: function () {
                    $('.dataTables_scrollHead').css('overflow', 'scroll');
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
                        var cell = $('.filters_parameter_simulasi th').eq($(column.column(index).header()).index());
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
                    });

                    let api = this.api();
                    api.columns.adjust().draw();

                    {{--$('#dt_balans').DataTable().ajax.url('{{route('get_data_dasar_balans')}}').load();--}}
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        }, title: 'Balans' }
                ],
                ajax: {
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : '{{route("get_data_kontrol_proyeksi")}}',
                    data: {

                        _token: "{{ csrf_token() }}",
                        data:'index',
                        company: company,
                        versi: versi,
                        asumsi: asumsi,
                        },
                },
                columns: [
                    { data: 'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
                    { data: 'data_db', name:'filter_data_db', orderable:false, searchable:true},
                    { data: 'jumlah_feature', name:'jumlah_feature', orderable:false, searchable:false},
                ],
            });
        }

        function kelengkapan_biaya_tetap(company, versi, asumsi) {
            $('#kelengkapan_biaya_tetap').html(table_kelengkapan_biaya_tetap_dt)

            $('#dt_kelengkapan_biaya_tetap thead tr')
                .clone(true)
                .addClass('filters_kelengkapan_biaya_tetap')
                .appendTo('#dt_kelengkapan_biaya_tetap thead');

            $('#dt_kelengkapan_biaya_tetap').DataTable().clear().destroy();
            $("#dt_kelengkapan_biaya_tetap").DataTable({
                // scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                scrollCollapse: true,
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
                deferRender:true,
                // fixedHeader: {
                //     header: true,
                //     headerOffset: $('#main_header').height()
                // },
                initComplete: function () {
                    $('.dataTables_scrollHead').css('overflow', 'scroll');
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
                        var cell = $('.filters_kelengkapan_biaya_tetap th').eq($(column.column(index).header()).index());
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
                    });

                    let api = this.api();
                    api.columns.adjust().draw();

                    {{--$('#dt_balans').DataTable().ajax.url('{{route('get_data_dasar_balans')}}').load();--}}
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        }, title: 'Balans' }
                ],
                ajax: {
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : '{{route("get_data_kontrol_proyeksi")}}',
                    data: {

                        _token: "{{ csrf_token() }}",
                        data:'kelengkapan_biaya_tetap',
                        company: company,
                        versi: versi,
                        asumsi: asumsi,
                        },
                },
                columns: [
                    { data: 'company', name:'filter_company', orderable:false, searchable:true},
                    { data: 'cost_center_data', name:'filter_cost_center_data', orderable:false, searchable:true},
                ],
            });
        }

        function kelengkapan_harga_material(company, versi, asumsi) {
            $('#kelengkapan_harga_material').html(table_kelengkapan_harga_material_dt)

            $('#dt_kelengkapan_harga_material thead tr')
                .clone(true)
                .addClass('filters_kelengkapan_harga_material')
                .appendTo('#dt_kelengkapan_harga_material thead');

            $('#dt_kelengkapan_harga_material').DataTable().clear().destroy();
            $("#dt_kelengkapan_harga_material").DataTable({
                // scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                scrollCollapse: true,
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
                deferRender:true,
                // fixedHeader: {
                //     header: true,
                //     headerOffset: $('#main_header').height()
                // },
                initComplete: function () {
                    $('.dataTables_scrollHead').css('overflow', 'scroll');
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
                        var cell = $('.filters_kelengkapan_harga_material th').eq($(column.column(index).header()).index());
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
                    });

                    let api = this.api();
                    api.columns.adjust().draw();

                    {{--$('#dt_balans').DataTable().ajax.url('{{route('get_data_dasar_balans')}}').load();--}}
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        }, title: 'Balans' }
                ],
                ajax: {
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : '{{route("get_data_kontrol_proyeksi")}}',
                    data: {

                        _token: "{{ csrf_token() }}",
                        data:'kelengkapan_harga_material',
                        company: company,
                        versi: versi,
                        asumsi: asumsi,
                    },
                },
                columns: [
                    { data: 'company', name:'filter_company', orderable:false, searchable:true},
                    { data: 'region', name:'filter_region', orderable:false, searchable:true},
                    { data: 'material_data', name:'filter_material_data', orderable:false, searchable:true},

                ],
            });
        }

        function kelengkapan_bom(company, versi, asumsi) {
            $('#kelengkapan_bom').html(table_kelengkapan_bom_dt)

            $('#dt_kelengkapan_bom thead tr')
                .clone(true)
                .addClass('filters_kelengkapan_bom')
                .appendTo('#dt_kelengkapan_bom thead');

            $('#dt_kelengkapan_bom').DataTable().clear().destroy();
            $("#dt_kelengkapan_bom").DataTable({
                // scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                scrollCollapse: true,
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
                deferRender:true,
                // fixedHeader: {
                //     header: true,
                //     headerOffset: $('#main_header').height()
                // },
                initComplete: function () {
                    $('.dataTables_scrollHead').css('overflow', 'scroll');
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
                        var cell = $('.filters_kelengkapan_bom th').eq($(column.column(index).header()).index());
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
                    });

                    let api = this.api();
                    api.columns.adjust().draw();

                    {{--$('#dt_balans').DataTable().ajax.url('{{route('get_data_dasar_balans')}}').load();--}}
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        }, title: 'Balans' }
                ],
                ajax: {
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : '{{route("get_data_kontrol_proyeksi")}}',
                    data: {

                        _token: "{{ csrf_token() }}",
                        data:'kelengkapan_bom',
                        company: company,
                        versi: versi,
                        asumsi: asumsi,
                    },
                },
                columns: [
                    { data: 'company', name:'filter_company', orderable:false, searchable:true},
                    { data: 'plant_data', name:'filter_plant_data', orderable:false, searchable:true},
                    { data: 'material_data', name:'filter_material_data', orderable:false, searchable:true},

                ],
            });
        }
    </script>
@endsection
