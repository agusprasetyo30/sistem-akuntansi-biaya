@extends('layouts.app')

@section('styles')
    <style>
        .dt-buttons {
            z-index: 10;
        }
    </style>
@endsection

@section('content')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">ZCO</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('zco','create'))
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
                                    <li id="tabs_group_account"> <a href="#group_account" data-bs-toggle="tab">Group Account</a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="vertical">
                                    <div class="mb-5 row">
                                        @if (auth()->user()->mapping_akses('zco')->company_code == 'all')
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
                                    <div class="mb-2 row">
                                        @if (auth()->user()->mapping_akses('zco')->company_code == 'all')
                                            <div class="form-group">
                                                <label class="form-label">PERUSAHAAN</label>
                                                <select id="filter_company_hor" class="form-control custom-select select2">
                                                </select>
                                            </div>
                                        @endif
                    
                                        <div class="form-group">
                                            <label class="form-label">VERSI</label>
                                            <select id="filter_version_hor" class="form-control custom-select select2">
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">PRODUK</label>
                                            <select id="filter_material" class="form-control custom-select select2">
                                                {{-- <option value="" disabled selected>Pilih Produk</option> --}}
                                                <option value="all" selected>Semua</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="format_plant">
                                            <label class="form-label">PLANT</label>
                                            <select id="filter_plant" class="form-control custom-select select2">
                                                {{-- <option value="all" selected>Semua</option> --}}
                                                <option value="all" selected>Pilih Produk Terlebih Dahulu</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">PERIODE </label>
                                            <select id="filter_format" class="form-control custom-select select2">
                                                {{-- <option value="" disabled selected>Pilih Periode</option> --}}
                                                {{-- <option selected disabled value="">Pilih Format</option> --}}
                                                @foreach (format_zco() as $key => $value)
                                                    options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id="month_pick_range">
                                            <label class="form-label">BULAN </label>
                                            <div class="input-group input-daterange">
                                                <input type="text" id="bulan_filter1" class="form-control" placeholder="Month" autocomplete="off">
                                                <div class="input-group-addon">to</div>
                                                <input disabled type="text" id="bulan_filter2" class="form-control" placeholder="Month" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group" id="month_pick">
                                            <label class="form-label">Bulan</label>
                                            <select name="bulan_satuan_filter1" id="bulan_satuan_filter1" class="form-control custom-select select2">
                                                <option value="" selected>Pilih Version Terlebih Dahulu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="btn-list mb-5">
                                        <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
                                        {{-- <button type="button" class="btn btn-danger btn-pill" id="btn_reset"><i class="fa fa-trash me-2 fs-14"></i> Reset Filter</button> --}}
                                    </div>
                                    <div class="mt-auto">
                                        <div class="table-responsive" id="dinamic_table">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="group_account">
                                    <div class="mb-2 row">
                                        @if (auth()->user()->mapping_akses('zco')->company_code == 'all')
                                            <div class="form-group">
                                                <label class="form-label">PERUSAHAAN</label>
                                                <select id="filter_company_group_account" class="form-control custom-select select2">
                                                </select>
                                            </div>
                                        @endif
                    
                                        <div class="form-group">
                                            <label class="form-label">VERSI</label>
                                            <select id="filter_version_group_account" class="form-control custom-select select2">
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">PRODUK</label>
                                            <select id="filter_material_group_account" class="form-control custom-select select2">
                                                {{-- <option value="" disabled selected>Pilih Produk</option> --}}
                                                <option value="all" selected>Semua</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="format_plant_group_account">
                                            <label class="form-label">PLANT</label>
                                            <select id="filter_plant_group_account" class="form-control custom-select select2">
                                                <option value="all" selected>Pilih Produk Terlebih Dahulu</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">PERIODE </label>
                                            <select id="filter_format_group_account" class="form-control custom-select select2">
                                                {{-- <option value="" disabled selected>Pilih Periode</option> --}}
                                                {{-- <option selected disabled value="">Pilih Format</option> --}}
                                                @foreach (format_zco() as $key => $value)
                                                    options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id="month_pick_range_group_account">
                                            <label class="form-label">BULAN </label>
                                            <div class="input-group input-daterange">
                                                <input type="text" id="bulan_filter1_group_account" class="form-control" placeholder="Month" autocomplete="off">
                                                <div class="input-group-addon">to</div>
                                                <input disabled type="text" id="bulan_filter2_group_account" class="form-control" placeholder="Month" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group" id="month_pick_group_account">
                                            <label class="form-label">Bulan</label>
                                            <select name="bulan_satuan_filter1_group_account" id="bulan_satuan_filter1_group_account" class="form-control custom-select select2">
                                                <option value="" selected>Pilih Version Terlebih Dahulu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="btn-list mb-5">
                                        <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan_group_account"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
                                        {{-- <button type="button" class="btn btn-danger btn-pill" id="btn_reset_group_account"><i class="fa fa-trash me-2 fs-14"></i> Reset Filter</button> --}}
                                    </div>
                                    <div class="mt-auto">
                                        <div class="table-responsive" id="dinamic_group_account_table">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @include('pages.buku_besar.zco.add')
                @include('pages.buku_besar.zco.import')
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

            // $("#dinamic_table").empty();
            // get_data_horiz()

            // $("#dinamic_group_account_table").empty();
            // get_data_group_account_horiz()

            $('#tabs_vertical').on('click', function () {
                // table()
                $('#dt_zco').DataTable().ajax.reload();
            })

            $('#tabs_horizontal').on('click', function () {
                // $("#dinamic_table").empty();
                // get_data_horiz()
                // $('#h_dt_zco').DataTable().ajax.reload();
            })

            $('#tabs_group_account').on('click', function () {
                // $("#dinamic_group_account_table").empty();
                // get_data_group_account_horiz()
                // $('#h_dt_zco_group_account').DataTable().ajax.reload();
            })

            $('#btn_tampilkan').on('click', function () {
                if ($('#filter_format').val() == '0') {
                    if ($('#bulan_satuan_filter1').val() == '') {
                        Swal.fire({
                            title: 'Oopss...',
                            html: 'Data bulan tidak boleh kosong',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: "#019267",
                            confirmButtonText: 'Konfirmasi',
                        })
                    } else {
                        $("#dinamic_table").empty();
                        get_data_horiz()
                    }
                } else if ($('#filter_format').val() == '1') {
                    if ($('#bulan_filter1').val() == '' && $('#bulan_filter2').val() == '') {
                        Swal.fire({
                            title: 'Oopss...',
                            html: 'Data bulan tidak boleh kosong',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: "#019267",
                            confirmButtonText: 'Konfirmasi',
                        })
                    } else {
                        $("#dinamic_table").empty();
                        get_data_horiz()
                    }
                } else {
                    $("#dinamic_table").empty();
                    get_data_horiz()
                }
            })

            $('#btn_reset').on('click', function () {
                reset_form()
            })

            $('#btn_reset_group_account').on('click', function () {
                reset_form_group_account()
            })

            $('#btn_tampilkan_group_account').on('click', function () {
                if ($('#filter_format_group_account').val() == '0') {
                    if ($('#bulan_satuan_filter1_group_account').val() == '') {
                        Swal.fire({
                            title: 'Oopss...',
                            html: 'Data bulan tidak boleh kosong',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: "#019267",
                            confirmButtonText: 'Konfirmasi',
                        })
                    } else {
                        $("#dinamic_group_account_table").empty();
                        get_data_group_account_horiz()
                    }
                } else if ($('#filter_format_group_account').val() == '1') {
                    if ($('#bulan_filter1_group_account').val() == '' && $('#bulan_filter2_group_account').val() == '') {
                        Swal.fire({
                            title: 'Oopss...',
                            html: 'Data bulan tidak boleh kosong',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: "#019267",
                            confirmButtonText: 'Konfirmasi',
                        })
                    } else {
                        $("#dinamic_group_account_table").empty();
                        get_data_group_account_horiz()
                    }
                } else {
                    $("#dinamic_group_account_table").empty();
                    get_data_group_account_horiz()
                }
            })

            $('#filter_material').change(function(){
                if($(this).val() != 'all'){
                    $('#format_plant').slideDown('slow')
                } else {
                    $('#format_plant').slideUp('slow')
                }
            })

            $('#filter_material_group_account').change(function(){
                if($(this).val() != 'all'){
                    $('#format_plant_group_account').slideDown('slow')
                } else {
                    $('#format_plant_group_account').slideUp('slow')
                }
            })

            $('#filter_format').change(function(){
                if($(this).val() == 'all'){
                    $('#month_pick').slideUp('slow')
                    $('#month_pick_range').slideUp('slow')
                } else if ($(this).val() == '0') {
                    $('#month_pick').slideDown('slow')
                    $('#month_pick_range').slideUp('slow')
                } else if ($(this).val() == '1'){
                    $('#month_pick').slideUp('slow')
                    $('#month_pick_range').slideDown('slow')
                }
            })

            $('#filter_format_group_account').change(function(){
                if($(this).val() == 'all'){
                    $('#month_pick_group_account').slideUp('slow')
                    $('#month_pick_range_group_account').slideUp('slow')
                } else if ($(this).val() == '0') {
                    $('#month_pick_group_account').slideDown('slow')
                    $('#month_pick_range_group_account').slideUp('slow')
                } else if ($(this).val() == '1'){
                    $('#month_pick_group_account').slideUp('slow')
                    $('#month_pick_range_group_account').slideDown('slow')
                }
            })

            $('#format_plant').hide()
            $('#format_plant_group_account').hide()
            $('#month_pick').hide()
            $('#month_pick_range').hide()
            $('#month_pick_group_account').hide()
            $('#month_pick_range_group_account').hide()

            $('#periode').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            });

            $('#version_import').select2({
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
                var d_version = $('#version_import').val();
                $('#detail_version_import').append('<option selected disabled value="">Pilih Bulan</option>').select2({
                    dropdownParent: $('#modal_import'),
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
                                version:d_version

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
                });
            })

            $('#filter_version_hor').select2({
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
                var _ver = $('#filter_version_hor').val();
                $('#bulan_satuan_filter1').append('<option selected disabled value="">Pilih Bulan</option>').select2({
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
                                version:_ver

                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    }
                })
            })

            $('#filter_version_group_account').select2({
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
                var _ver = $('#filter_version_group_account').val();
                $('#bulan_satuan_filter1_group_account').append('<option selected disabled value="">Pilih Bulan</option>').select2({
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
                                version:_ver

                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    }
                })
            })

            $('#periode_import').on("click", function() {
                $('#periode_import').bootstrapdatepicker("show");
            })

            $('#data_main_plant').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Plant',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('plant_select') }}",
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

            $('#data_main_produk').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Produk',
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

            $('#data_main_cost_element').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Cost Element',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('cost_element_select') }}",
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

            $('#filter_material').select2({
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('zco_product_dt') }}",
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
                var data_product = $('#filter_material').val();

                $('#filter_plant').append('<option selected value="all">Semua</option>').select2({
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('zco_plant_dt') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                product:data_product
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

            $('#filter_material_group_account').select2({
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('zco_product_dt') }}",
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
                var data_product = $('#filter_material_group_account').val();

                $('#filter_plant_group_account').append('<option selected value="all">Semua</option>').select2({
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('zco_plant_dt') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                product:data_product
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

            $('#bulan_filter1').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            }).on('change', function () {
                var periode = moment($('#bulan_filter1').val(), "MM-YYYY").add(1, 'months').format('MM-YYYY');
                $('#bulan_filter2').attr('disabled', false)
                    .bootstrapdatepicker({
                    format: "mm-yyyy",
                    viewMode: "months",
                    minViewMode: "months",
                    autoclose:true,
                    startDate: periode
                });
            });

            $('#bulan_filter1_group_account').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            }).on('change', function () {
                var periode_group_account = moment($('#bulan_filter1_group_account').val(), "MM-YYYY").add(1, 'months').format('MM-YYYY');
                $('#bulan_filter2_group_account').attr('disabled', false)
                    .bootstrapdatepicker({
                    format: "mm-yyyy",
                    viewMode: "months",
                    minViewMode: "months",
                    autoclose:true,
                    startDate: periode_group_account
                });
            });

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

            $('#filter_company_hor').select2({
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{route('company_filter_select') }}",
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

            $('#filter_company_group_account').select2({
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{route('company_filter_select') }}",
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

            // $('#filter_version_hor').select2({
            //     placeholder: 'Pilih Versi',
            //     width: '100%',
            //     allowClear: false,
            //     ajax: {
            //         url: "{{ route('version_select') }}",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 search: params.term
            //             };
            //         },
            //         processResults: function(response) {
            //             return {
            //                 results: response
            //             };
            //         }
            //     }
            // })
        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_zco" class="table table-bordered text-nowrap key-buttons" style="width: 240%;">
                <thead>
                <tr>
                    <th data-type='select' data-name='version' class="text-center">VERSI</th>
                    <th data-type='text' data-name='periode' class="text-center">PERIODE</th>
                    <th data-type='select' data-name='plant' class="text-center">PLANT</th>
                    <th data-type='select' data-name='product' class="text-center">PRODUK</th>
                    <th data-type='text' data-name='product_qty' class="text-center">PRODUK QTY</th>
                    <th data-type='select' data-name='cost_element' class="text-center">COST ELEMENT</th>
                    <th data-type='select' data-name='material' class="text-center">MATERIAL</th>
                    <th data-type='text' data-name='total_qty' class="text-center">TOTAL QTY</th>
                    <th data-type='text' data-name='currency' class="text-center">CURRENCY</th>
                    <th data-type='text' data-name='total_amount' class="text-center">TOTAL AMOUNT</th>
                    <th data-type='text' data-name='unit_price_product' class="text-center">UNIT PRICE PRODUCT</th>
                    <th data-type='text' data-name='nomor' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_zco thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_zco thead');

            $('#dt_zco').DataTable().clear().destroy();
            $("#dt_zco").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                order:[[1, 'desc']],
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
                                if (iName == 'plant'){
                                    input.className = "plant_search form-control custom-select select2";
                                } else if(iName == 'product'){
                                    input.className = "product_search form-control custom-select select2";
                                } else if(iName == 'cost_element'){
                                    input.className = "cost_element_search form-control custom-select select2";
                                } else if(iName == 'material'){
                                    input.className = "material_search form-control custom-select select2";
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

                        $('.plant_search').select2({
                            placeholder: 'Pilih Plant',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('plant_dt') }}",
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
                        $('.product_search').select2({
                            placeholder: 'Pilih Produk',
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

                        $('.cost_element_search').select2({
                            placeholder: 'Pilih Cost Element',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('cost_element_dt') }}",
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
                        })

                    });

                    let api = this.api();
                    api.columns.adjust().draw();
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    // { 
                    //     extend: 'excel', 
                    //     className: 'mb-5', 
                    //     exportOptions:{
                    //     columns: 'th:not(:last-child)'
                    //     }, 
                    //     title: '',
                    //     filename: 'ZCO - Vertikal',
                    //     customize: function (file) {
                    //         var sheet = file.xl.worksheets['sheet1.xml'];
                    //         var style = file.xl['styles.xml'];
                    //         var row = $('row', sheet);
                    //         var mergeCells = $('mergeCells', sheet);
                    //         // console.log('row', row);
                    //         $(row[1]).remove()
                    //
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'A1:A2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'B1:B2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'C1:C2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'D1:D2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'E1:E2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'F1:F2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'G1:G2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'H1:H2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'I1:I2' }
                    //             }) 
                    //         );
                    //         mergeCells[0].appendChild( 
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'J1:J2' }
                    //             }) 
                    //         );
                    //
                    //         mergeCells.attr( 'count', mergeCells.attr( 'count' )+1 );
                    //
                    //         function _createNode( doc, nodeName, opts ) {
                    //             var tempNode = doc.createElement( nodeName );
                    //      
                    //             if ( opts ) {
                    //                 if ( opts.attr ) {
                    //                     $(tempNode).attr( opts.attr );
                    //                 }
                    //
                    //                 if ( opts.children ) {
                    //                     $.each( opts.children, function ( key, value ) {
                    //                         tempNode.appendChild( value );
                    //                     } );
                    //                 }
                    //
                    //                 if ( opts.text !== null && opts.text !== undefined ) {
                    //                     tempNode.appendChild( doc.createTextNode( opts.text ) );
                    //                 }
                    //             }
                    //
                    //             return tempNode;
                    //         }
                    //     }
                    // }
                ],
                ajax: {
                    url : '{{route("zco")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company_ver').val(),
                        filter_version:$('#filter_version_ver').val()
                    }
                },
                columns: [
                    { data: 'version', name: 'filter_version', orderable:true},
                    { data: 'periode', name: 'periode', orderable:true},
                    { data: 'plant_code', name: 'filter_plant', orderable:true},
                    { data: 'product', name: 'filter_product', orderable:true},
                    { data: 'product_qty', name: 'product_qty', orderable:true},
                    { data: 'cost_element', name: 'filter_cost_element', orderable:true},
                    { data: 'material', name: 'filter_material', orderable:true},
                    { data: 'total_qty', name: 'total_qty', orderable:true},
                    { data: 'currency', name: 'currency', orderable:true},
                    { data: 'total_amount', name: 'total_amount', orderable:true},
                    { data: 'unit_price_product', name: 'unit_price_product', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0]}
                ],
            })
        }

        function get_data_horiz(){
            var table = `
            <table id="h_dt_zco" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                    <tr id="dinamic_tr_top">
                    </tr>
                    <tr id="dinamic_tr">
                    </tr>
                </thead>
                <tfoot>
                    <tr id="dinamic_footer">
                    </tr>
                </tfoot>
            </table>`
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">BIAYA</th><th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">MATERIAL</th>'
            var kolom = ''
            var kolom_footer = '<th> Total </th><th> Perhitungan </th>'
            var column = [
                { data: 'material_code', orderable:false},
                { data: 'material_name', orderable:false},
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "GET",
                url : '{{route("zco")}}',
                data: {
                    data:'material',
                    material:$('#filter_material').val(),
                    plant:$('#filter_plant').val(),
                    format_data:$('#filter_format').val(),
                    start_month:$('#bulan_filter1').val(),
                    end_month:$('#bulan_filter2').val(),
                    moth:$('#bulan_satuan_filter1').val(),
                    version:$('#filter_version_hor').val(),
                    company:$('#filter_company_hor').val(),
                },
                success:function (response) {
                    for (let i = 0; i < response.material.length;i++){
                        
                        column.push(
                            { 
                                data: i.toString()+'harga_satuan', 
                                orderable:false,
                                render: function ( data, type, row ) {
                                    if (data != 0) {
                                        rs = Math.round(data);
                                        result = formatRupiah(rs.toString(), 'Rp ')
                                        return result
                                    } else {
                                        return "-"
                                    }
                                }
                            }
                        );
                        column.push(
                            { 
                                data: i.toString()+'cr', 
                                orderable:false,
                                // render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
                                render: function ( data, type, row ) {
                                    if (data != 0) {
                                        result = data.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        return result
                                    } else {
                                        return "-"
                                    }
                                }
                            }
                        );
                        column.push(
                            { 
                                data: i.toString()+'biaya_perton', 
                                orderable:false,
                                render: function ( data, type, row ) {
                                    if (data != 0) {
                                        rs = Math.round(data);
                                        result = formatRupiah(rs.toString(), 'Rp ')
                                        return result
                                    } else {
                                        return "-"
                                    }
                                }
                            }
                        );
                        column.push(
                            { 
                                data: i.toString()+'total_biaya', 
                                orderable:false,
                                render: function ( data, type, row ) {
                                    if (data != 0) {
                                        rs = Math.round(data);
                                        result = formatRupiah(rs.toString(), 'Rp ')
                                        return result
                                    } else {
                                        return "-"
                                    }
                                }
                            }
                        );

                        kolom_top += '<th colspan="4" class="text-center"><strong>'+ response.material[i].product_code+'  '+ response.material[i].material_name+'<br>'+ response.material[i].plant_code +' '+ response.material[i].plant_desc +'<strong></th>';
                        kolom += '<th class="text-center">Harga Satuan</th><th class="text-center">CR</th><th class="text-center">Biaya Per Ton</th></th><th class="text-center">Total Biaya</th>';
                    }

                    for (let j = 0; j < response.material.length * 4 ; j++) {
                        kolom_footer += '<th></th>'
                    }

                    $("#dinamic_tr_top").append(kolom_top);
                    $("#dinamic_tr").append(kolom);
                    $("#dinamic_footer").append(kolom_footer);
                    $('#h_dt_zco').DataTable().clear().destroy();
                    $("#h_dt_zco").DataTable({
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
                        fixedColumns:   {
                            left: 2
                        },
                        buttons: [
                            { extend: 'pageLength'},
                            {{--{--}}
                            {{--    text: 'Excel',--}}
                            {{--    classname: 'mb-5',--}}
                            {{--    action: function ( e, dt, node, config ) {--}}
                            {{--        var material = $('#filter_material').val();--}}
                            {{--        var plant = $('#filter_plant').val();--}}
                            {{--        var format_data = $('#filter_format').val();--}}
                            {{--        var start_month = $('#bulan_filter1').val();--}}
                            {{--        var end_month = $('#bulan_filter2').val();--}}
                            {{--        var moth = $('#bulan_satuan_filter1').val();--}}

                            {{--        let route_default = '{{ route("export_zco_horizontal") }}'--}}
                            {{--        let route_complete = route_default +--}}
                            {{--            "?material=" + material + "&plant=" + plant + "&format_data=" + format_data +"&start_month=" + start_month + "&end_month=" + end_month + "&moth=" + moth--}}

                            {{--        window.location = route_complete--}}
                            {{--    }--}}
                            {{--}--}}
                        ],

                        ajax: {
                            url : '{{route("zco")}}',
                            data: {
                                data:'horizontal',
                                material:$('#filter_material').val(),
                                plant:$('#filter_plant').val(),
                                format_data:$('#filter_format').val(),
                                start_month:$('#bulan_filter1').val(),
                                end_month:$('#bulan_filter2').val(),
                                moth:$('#bulan_satuan_filter1').val(),
                                version:$('#filter_version_hor').val(),
                                company:$('#filter_company_hor').val(),
                            }
                        },
                        columns: column,
                        initComplete: function( settings ) {
                            let api = this.api();
                            api.columns.adjust().draw();
                        },
                        // footerCallback: function () {
                        //     var response = this.api().ajax.json();
                        //     this.api().eq(0).columns().every(function (index) {
                        //         var api = this
                        //         if (index > 1){
                        //             var count = parseInt(index) - 2
                        //             console.log(index, count)
                        //             var variable = 'total'+ count;
                        //             $( api.column(index).footer() ).html(response[variable]);
                        //         }
                        //     })
                        // },
                        footerCallback: function (row, data, start, end, display) {
                            this.api().eq(0).columns().every(function (index) {
                                if (index > 1){
                                    var api = this;
                                    var intVal = function (i) {
                                        // return typeof i === 'string' ? parseFloat(i.replace(/[\Rp.]/g, '')) : typeof i === 'number' ? i : 0;
                                        if (i === '-') {
                                            return 0
                                        } else {
                                            return typeof i === 'string' ? parseFloat(i.replace(/[\Rp.]/g, '')) : typeof i === 'number' ? i : 0;
                                        }
                                    };

                                    // Total over all pages
                                    total = api
                                        .column(index)
                                        .data()
                                        .reduce(function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0);

                                    if (total == 0) {
                                        total_perhitungan = '-'
                                    } else {
                                        if (index == 3 || index == 7 || index == 11 || index == 15 || index == 19 || index == 23 || index == 27 || index == 31 || index == 35 || index == 39 || index == 43 || index == 47 || index == 51 || index == 55 || index == 59) {
                                            total_perhitungan = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        } else {
                                            res = Math.round(total);
                                            total_perhitungan = formatRupiah(res.toString(), 'Rp ')
                                        }
                                    }

                                    // Update footer
                                    $(api.column(index).footer()).html(total_perhitungan);
                                }
                            })
                        },
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        }

        function get_data_group_account_horiz(){
            var table = `
            <table id="h_dt_zco_group_account" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                    <tr id="dinamic_group_account_tr_top">
                    </tr>
                    <tr id="dinamic_group_account_tr">
                    </tr>
                </thead>
                <tfoot>
                    <tr id="dinamic_group_account_footer">
                    </tr>
                </tfoot>
            </table>`
            // var kolom = '<th class="text-center">BIAYA </th>'
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">BIAYA</th><th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">GROUP ACCOUNT</th>'
            var kolom = ''
            var kolom_footer = '<th> Total </th><th> Perhitungan </th>'
            var column = [
                { data: 'group_account_code', orderable:false},
                { data: 'group_account_desc', orderable:false},
            ]
            $("#dinamic_group_account_table").append(table);
            $.ajax({
                type: "GET",
                url : '{{route("zco")}}',
                data: {
                    data:'group_account',
                    material:$('#filter_material_group_account').val(),
                    plant:$('#filter_plant_group_account').val(),
                    format_data:$('#filter_format_group_account').val(),
                    start_month:$('#bulan_filter1_group_account').val(),
                    end_month:$('#bulan_filter2_group_account').val(),
                    moth:$('#bulan_satuan_filter1_group_account').val(),
                    version:$('#filter_version_group_account').val(),
                    company:$('#filter_company_group_account').val(),
                },
                success:function (response) {
                    // if (response.group_account.length == 0) {
                    //     Swal.fire({
                    //         title: 'Oopss...',
                    //         html: 'Data yang anda cari tidak dapat ditemukan',
                    //         icon: 'warning',
                    //         allowOutsideClick: false,
                    //         confirmButtonColor: "#019267",
                    //         confirmButtonText: 'Konfirmasi',
                    //     })
                    // }
                    
                    for (let i = 0; i < response.group_account.length;i++){
                        kolom_top += '<th colspan="4" class="text-center">'+ response.group_account[i].product_code+'  '+ response.group_account[i].material_name+'<br>'+ response.group_account[i].plant_code + ' ' + response.group_account[i].plant_desc +'</th>';
                        kolom += '<th class="text-center">Harga Satuan</th><th class="text-center">CR</th><th class="text-center">Biaya Per Ton</th></th><th class="text-center">Total Biaya</th>';
                    }

                    for (let j = 0; j < response.group_account.length * 4 ; j++) {
                        column.push(
                            { 
                                data: j.toString(), 
                                orderable:false, 
                                render: function ( data, type, row ) {
                                    if (data != 0) {
                                        rs = Math.round(data);
                                        result = formatRupiah(rs.toString(), 'Rp ')
                                        return result
                                    } else {
                                        return "-"
                                    }
                                }
                            }
                        )
                        kolom_footer += '<th></th>'
                    }

                    $("#dinamic_group_account_tr_top").append(kolom_top);
                    $("#dinamic_group_account_tr").append(kolom);
                    $("#dinamic_group_account_footer").append(kolom_footer);
                    $('#h_dt_zco_group_account').DataTable().clear().destroy();
                    $("#h_dt_zco_group_account").DataTable({
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
                        fixedColumns:   {
                            left: 2
                        },
                        buttons: [
                            { extend: 'pageLength'},
                            {{--{--}}
                            {{--    text: 'Excel',--}}
                            {{--    classname: 'mb-5',--}}
                            {{--    action: function ( e, dt, node, config ) {--}}
                            {{--        let material_group_account = $('#filter_material_group_account').val();--}}
                            {{--        let plant_group_account = $('#filter_plant_group_account').val();--}}
                            {{--        let format_data_group_account = $('#filter_format_group_account').val();--}}
                            {{--        let start_month_group_account = $('#bulan_filter1_group_account').val();--}}
                            {{--        let end_month_group_account = $('#bulan_filter2_group_account').val();--}}
                            {{--        let moth_group_account = $('#btn_tampilkan_group_account').val();--}}

                            {{--        let route_default_group_account = '{{ route("export_zco_account") }}'--}}
                            {{--        let route_complete_group_account = route_default_group_account +--}}
                            {{--            "?material=" + material_group_account + "&plant=" + plant_group_account + "&format_data=" + format_data_group_account +"&start_month=" + start_month_group_account + "&end_month=" + end_month_group_account + "&moth=" + moth_group_account--}}

                            {{--        window.location = route_complete_group_account--}}
                            {{--    }--}}
                            {{--}--}}
                        ],
                        ajax: {
                            url : '{{route("zco")}}',
                            data: {
                                data:'horizontal_group_account',
                                material:$('#filter_material_group_account').val(),
                                plant:$('#filter_plant_group_account').val(),
                                format_data:$('#filter_format_group_account').val(),
                                start_month:$('#bulan_filter1_group_account').val(),
                                end_month:$('#bulan_filter2_group_account').val(),
                                moth:$('#bulan_satuan_filter1_group_account').val(),
                                version:$('#filter_version_group_account').val(),
                                company:$('#filter_company_group_account').val(),
                            }
                        },
                        columns: column,
                        initComplete: function( settings ) {
                            let api = this.api();
                            api.columns.adjust().draw();
                        },
                        footerCallback: function (row, data, start, end, display) {
                            this.api().eq(0).columns().every(function (index) {
                                if (index > 1){
                                    var api = this;
                                    var intVal = function (i) {
                                        // return typeof i === 'string' ? parseFloat(i.replace(/[\Rp.]/g, '')) : typeof i === 'number' ? i : 0;
                                        if (i === '-') {
                                            return 0
                                        } else {
                                            return typeof i === 'string' ? parseFloat(i.replace(/[\Rp.]/g, '')) : typeof i === 'number' ? i : 0;
                                        }
                                    };

                                    // Total over all pages
                                    total = api
                                        .column(index)
                                        .data()
                                        .reduce(function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0);
                                    
                                    if (total == 0) {
                                        total_perhitungan = '-'
                                    } else {
                                        res = Math.round(total);
                                        total_perhitungan = formatRupiah(res.toString(), 'Rp ')
                                    }

                                    // Update footer
                                    $(api.column(index).footer()).html(total_perhitungan);
                                }
                            })
                        },
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        }

        // Function Generate Abjad
        function generateAbjad(idx) {            
            const multiple = 4
            const start = (multiple * idx) + 2
            const end = start + 3
            let rangeColumn = '';
            
            let firstAlp1st = 0;
            let firstAlp2nd = start;
            let secondAlp1st = 0;
            let secondAlp2nd = end;

            if(start > 25) {
                firstAlp1st = parseInt(start / 26)
                firstAlp2nd = start % 26
            }
            if(end > 25) {
                secondAlp1st = parseInt(end / 26)
                secondAlp2nd = end % 26
            }

            let col1st = alphabet[firstAlp1st]+alphabet2nd[firstAlp2nd]
            let col2nd = alphabet[secondAlp1st]+alphabet2nd[secondAlp2nd]
            rangeColumn = col1st+'1'+':'+col2nd+'1'

            return rangeColumn
        }

        function update_dt_horizontal() {
            if ($('#filter_material').val() != null){
                $("#dinamic_table").empty();
                get_data_horiz()
            }
        }

        function update_dt_group_account_horizontal() {
            if ($('#filter_material_group_account').val() != null){
                $("#dinamic_group_account_table").empty();
                get_data_group_account_horiz()
            }
        }

        $('#submit').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_zco')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    plant_code: $('#data_main_plant').val(),
                    periode: $('#periode').val(),
                    product_code: $('#data_main_produk').val(),
                    product_qty: $('#product_qty').val(),
                    cost_element: $('#data_main_cost_element').val(),
                    material_code: $('#data_main_material').val(),
                    total_qty: $('#total_qty').val(),
                    currency: $('#currency').val(),
                    total_amount: $('#total_amount').val(),
                    unit_price_product: $('#unit_price_product').val(),
                    version: $('#data_main_version').val(),
                    id_asumsi: $('#data_detal_version').val(),
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
                            $('#data_main_plant').val('').trigger("change");
                            $('#data_main_produk').val('').trigger("change");
                            $('#data_main_cost_element').val('').trigger("change");
                            $('#data_main_material').val('').trigger("change");

                            update_dt_horizontal()
                            update_dt_group_account_horizontal()
                            // table()
                            $('#dt_zco').DataTable().ajax.reload();
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
                url: '{{route('check_zco')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#version_import').val(),
                    periode:$('#detail_version_import').val()
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

        function importStore() {
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_zco')}}',
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
                            $("#version_import").val('').trigger('change')
                            $("#detail_version_import").val('').trigger('change')
                            // table()
                            update_dt_horizontal()
                            update_dt_group_account_horizontal()
                            $('#dt_zco').DataTable().ajax.reload();
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
                url: '{{route('export_zco')}}',
                data: {
                    version: $('#version').val(),
                },
                success:function (result, status, xhr) {
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : 'zco.xlsx');

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

        function update_zco(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_zco')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    plant_code: $('#edit_data_main_plant'+id).val(),
                    periode: $('#edit_periode'+id).val(),
                    product_code: $('#edit_data_main_produk'+id).val(),
                    product_qty:$('#edit_product_qty'+id).val(),
                    cost_element: $('#edit_data_main_cost_element'+id).val(),
                    material_code:$('#edit_data_main_material'+id).val(),
                    total_qty: $('#edit_total_qty'+id).val(),
                    currency:$('#edit_currency'+id).val(),
                    total_amount: $('#edit_total_amount'+id).val(),
                    unit_price_product:$('#edit_unit_price_product'+id).val(),
                    version: $('#edit_data_main_version'+id).val(),
                    id_asumsi: $('#edit_data_detal_version'+id).val(),

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
                            $('#modal_edit'+id).modal('hide')
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            
                            update_dt_horizontal()
                            update_dt_group_account_horizontal()
                            // table()
                            $('#dt_zco').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })

        }

        function delete_zco(id) {
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
                        url: '{{route('delete_zco')}}',
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
                                    update_dt_group_account_horizontal()
                                    // table()
                                    $('#dt_zco').DataTable().ajax.reload();
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
        
        function reset_form(){
            $("#filter_material").val('all').trigger('change')
            $('#filter_plant').val('all').trigger('change')
            $('#filter_format').val('all').trigger('change')
            $('#bulan_filter1').val("")
            $('#bulan_filter2').val("")
            $('#bulan_satuan_filter1').val("").trigger('change')
        }

        function reset_form_group_account(){
            $("#filter_material_group_account").val('all').trigger('change')
            $('#filter_plant_group_account').val('all').trigger('change')
            $('#filter_format_group_account').val('all').trigger('change')
            $('#bulan_filter1_group_account').val("")
            $('#bulan_filter2_group_account').val("")
            $('#bulan_satuan_filter1_group_account').val("").trigger('change')
        }
    </script>
@endsection
