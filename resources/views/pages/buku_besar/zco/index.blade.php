@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">ZCO</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
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
                                <div class="">
                                    <div class="table-responsive" id="table-wrapper">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="horizontal">
                                <div class="mb-2 row">
                                    <div class="form-group">
                                        <label class="form-label">PRODUK</label>
                                        <select id="filter_material" class="form-control custom-select select2">
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
                                        <label class="form-label">BULAN </label>
                                        <input type="text" class="form-control form-control-sm" name="bulan_satuan_filter1" id="bulan_satuan_filter1" placeholder="Month" autocomplete="off" required>
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
                                    <div class="form-group">
                                        <label class="form-label">PRODUK</label>
                                        <select id="filter_material_group_account" class="form-control custom-select select2">
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
                                        <label class="form-label">BULAN </label>
                                        <input type="text" class="form-control form-control-sm" name="bulan_satuan_filter1_group_account" id="bulan_satuan_filter1_group_account" placeholder="Month" autocomplete="off" required>
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
    <script>
        $(document).ready(function () {
            table()

            // $("#dinamic_table").empty();
            // get_data_horiz()

            // $("#dinamic_group_account_table").empty();
            // get_data_group_account_horiz()

            $('#tabs_vertical').on('click', function () {
                // table()
                $('#dt_zco').DataTable().ajax.reload();
            })

            $('#tabs_horizontal').on('click', function () {
                $("#dinamic_table").empty();
                get_data_horiz()
                // $('#h_dt_zco').DataTable().ajax.reload();
            })

            $('#tabs_group_account').on('click', function () {
                $("#dinamic_group_account_table").empty();
                get_data_group_account_horiz()
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

            $('#periode_import').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                showOnFocus: false,
                autoclose:true,
            }).on('change', function () {
                $("#submit-export").css("display", "block");
            });

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

            // $('#filter_format').select2({
            //     placeholder: 'Pilih Format',
            //     width: '100%',
            //     allowClear: false,
            // }).on('change', function () {
            //     if ($('#filter_format').val() == 0) {
            //         // $('#month_pick_range').css('display','none')
            //         // $('#month_pick').css('display','block')

            //         $('#month_pick_range_group_account').slideUp('slow')
            //         $('#month_pick_group_account').slideDown('slow')
            //     } else if ($('#filter_format').val() == 1) {
            //         // $('#month_pick_range').css('display','block')
            //         // $('#month_pick').css('display','none')

            //         $('#month_pick_range_group_account').slideDown('slow')
            //         $('#month_pick_group_account').slideUp('slow')
            //     }
            // })

            // $('#bulan_filter1').bootstrapdatepicker({
            //     format: "mm-yyyy",
            //     viewMode: "months",
            //     minViewMode: "months",
            //     autoclose:true
            // });

            // $('#bulan_filter2').bootstrapdatepicker({
            //     format: "mm-yyyy",
            //     viewMode: "months",
            //     minViewMode: "months",
            //     autoclose:true
            // });

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

            $('#bulan_satuan_filter1').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            });

            // $('#filter_format_group_account').select2({
            //     placeholder: 'Pilih Format',
            //     width: '100%',
            //     allowClear: false,
            // }).on('change', function () {
            //     if ($('#filter_format_group_account').val() == 0) {
            //         // $('#month_pick_range_group_account').css('display','none')
            //         // $('#month_pick_group_account').css('display','block')
            //         $('#month_pick_range_group_account').slideUp('slow')
            //         $('#month_pick_group_account').slideDown('slow')
            //         // if($(this).val() != 'all'){
            //         // $('#format_plant').slideDown('slow')
            //         // } else {
            //         //     $('#format_plant').slideUp('slow')
            //         // }
            //     } else {
            //         // $('#month_pick_range_group_account').css('display','block')
            //         // $('#month_pick_group_account').css('display','none')

            //         $('#month_pick_range_group_account').slideDown('slow')
            //         $('#month_pick_group_account').slideUp('slow')
            //     }
            // })

            // $('#bulan_filter1_group_account').bootstrapdatepicker({
            //     format: "mm-yyyy",
            //     viewMode: "months",
            //     minViewMode: "months",
            //     autoclose:true
            // });

            // $('#bulan_filter2_group_account').bootstrapdatepicker({
            //     format: "mm-yyyy",
            //     viewMode: "months",
            //     minViewMode: "months",
            //     autoclose:true
            // });

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

            $('#bulan_satuan_filter1_group_account').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            });
        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_zco" class="table table-bordered text-nowrap key-buttons" style="width: 200%;">
                <thead>
                <tr>
                    <th data-type='select' data-name='plant' class="text-center">PLANT</th>
                    <th data-type='text' data-name='periode' class="text-center">PERIODE</th>
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
                // sortable: false,
                // searching: false,
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

                    });
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns: 'th:not(:last-child)'
                    }, title: 'ZCO' }
                ],
                ajax: {
                    url : '{{route("zco")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'plant_code', name: 'filter_plant', orderable:true},
                    { data: 'periode', name: 'periode', orderable:true},
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
            </table>`
            // var kolom = '<th class="text-center">BIAYA </th>'
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">BIAYA</th><th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">MATERIAL</th>'
            var kolom = ''
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
                },
                success:function (response) {
                    // if (response.material.length == 0) {
                    //     Swal.fire({
                    //         title: 'Oopss...',
                    //         html: 'Data yang anda cari tidak dapat ditemukan',
                    //         icon: 'warning',
                    //         allowOutsideClick: false,
                    //         confirmButtonColor: "#019267",
                    //         confirmButtonText: 'Konfirmasi',
                    //     })
                    // }

                    for (let i = 0; i < response.material.length;i++){
                        kolom_top += '<th colspan="4" class="text-center">'+ response.material[i].product_code+'  '+ response.material[i].material_name+'<br>'+ response.material[i].plant_code +' '+ response.material[i].plant_desc +'</th>';
                        kolom += '<th class="text-center">Harga Satuan</th><th class="text-center">CR</th><th class="text-center">Biaya Per Ton</th></th><th class="text-center">Total Biaya</th>';
                    }

                    for (let j = 0; j < response.material.length * 4 ; j++) {
                        column.push({ data: j.toString(), orderable:false})
                    }

                    $("#dinamic_tr_top").append(kolom_top);
                    $("#dinamic_tr").append(kolom);
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
                            { extend: 'pageLength', className: 'mb-5' },
                            { extend: 'excel', className: 'mb-5' }
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
                            }
                        },
                        columns: column,

                    })
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
            </table>`
            // var kolom = '<th class="text-center">BIAYA </th>'
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">BIAYA</th><th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">GROUP ACCOUNT</th>'
            var kolom = ''
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
                        column.push({ data: j.toString(), orderable:false})
                    }

                    $("#dinamic_group_account_tr_top").append(kolom_top);
                    $("#dinamic_group_account_tr").append(kolom);
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
                            { extend: 'pageLength', className: 'mb-5' },
                            { extend: 'excel', className: 'mb-5' }
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
                            }
                        },
                        columns: column,

                    })
                }
            })
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
                    periode:$('#periode_import').val()
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
            $('#bulan_satuan_filter1').val("")
        }

        function reset_form_group_account(){
            $("#filter_material_group_account").val('all').trigger('change')
            $('#filter_plant_group_account').val('all').trigger('change')
            $('#filter_format_group_account').val('all').trigger('change')
            $('#bulan_filter1_group_account').val("")
            $('#bulan_filter2_group_account').val("")
            $('#bulan_satuan_filter1_group_account').val("")
        }
    </script>
@endsection
