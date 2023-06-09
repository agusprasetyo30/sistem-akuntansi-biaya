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
            <h4 class="page-title mb-0 text-primary">Data Salr</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('salrs','create'))
                    <button data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
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
                                        @if (auth()->user()->mapping_akses('salrs')->company_code == 'all')
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
                                        <div class="table-responsive" id="table_main">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="horizontal">
                                    <div class="mb-3 row">
                                        @if (auth()->user()->mapping_akses('salrs')->company_code == 'all')
                                            <div class="form-group">
                                                <label class="form-label">PERUSAHAAN</label>
                                                <select id="filter_company_ver" class="form-control custom-select select2">
                                                    <option value="all" selected>Semua Perusahaan</option>
                                                </select>
                                            </div>
                                        @endif
                                        <div class="form-group" id="cost_center_pick">
                                            <label class="form-label">COST CENTER <span class="text-red">*</span></label>
                                            <select id="cost_center_format" class="form-control custom-select select2">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                            <select name="data_main_version_horizontal" id="data_main_version_horizontal" class="form-control custom-select select2">
                                                <option value="" disabled selected>Pilih Versi</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="format_pick" style="display:none;">
                                            <label class="form-label">PERIODE <span class="text-red">*</span></label>
                                            <select id="filter_format" class="form-control custom-select select2">
                                                <option selected disabled value="">Pilih Format</option>
                                                @foreach (format_salr() as $key => $value)
                                                    options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                                @endforeach
                                            </select>
                                        </div>

{{--                                        <div class="form-group" id="month_pick_range_versi" style="display:none;">--}}
{{--                                            <label class="form-label">BULAN <span class="text-red">*</span></label>--}}
{{--                                            <div class="input-group input-daterange">--}}
{{--                                                <input readonly type="text" id="bulan_filter1_versi" class="form-control" placeholder="Month" autocomplete="off">--}}
{{--                                                <div class="input-group-addon">to</div>--}}
{{--                                                <input readonly type="text" id="bulan_filter2_versi" class="form-control" placeholder="Month" autocomplete="off">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-group" id="month_pick_range" style="display:none;">
                                            <label class="form-label">BULAN <span class="text-red">*</span></label>
                                            <div class="input-group input-daterange">
                                                <input type="text" id="bulan_filter1" class="form-control" placeholder="Month" autocomplete="off">
                                                <div class="input-group-addon">to</div>
                                                <input type="text" id="bulan_filter2" class="form-control" placeholder="Month" autocomplete="off">
                                            </div>
                                        </div>
{{--                                        <div class="form-group" id="month_pick" style="display:none;">--}}
{{--                                            <label class="form-label">Bulan <span class="text-red">*</span></label>--}}
{{--                                            <select name="data_detail_version_horizontal" id="data_detail_version_horizontal" class="form-control custom-select select2">--}}
{{--                                                <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                        <div class="form-group" id="month_pick" style="display:none;">
                                            <label for="tanggal_awal">Bulan <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="data_detail_version_horizontal" id="data_detail_version_horizontal" placeholder="Bulan" autocomplete="off" required>
                                        </div>

                                        <div class="form-group" id="inflasi_pick" style="display:none;">
                                            <label class="form-label">INFLASI <span class="text-red">*</span></label>
                                            <select id="filter_inflasi" class="form-control custom-select select2">
                                                <option selected disabled value="">Pilih Format</option>
                                                <option value="1">Ya</option>
                                                <option value="0">Tidak</option>
                                            </select>
                                        </div>
{{--                                        <div class="form-group" id="versi_pick" style="display:none;">--}}
{{--                                            <label class="form-label">VERSI INFLASI <span class="text-red">*</span></label>--}}
{{--                                            <select id="versi_format" class="form-control custom-select select2">--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
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
                @include('pages.buku_besar.salr.add')
                @include('pages.buku_besar.salr.import')
            </div>
        </div>
    </div>
    <!-- /Row -->
@endsection

@section('scripts')

    <script src="{{asset('assets/plugins/datatables/Buttons/js/dataTables.buttons.js?v=1.0.1')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.js?v=1.0.2')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.styles.js?v=1.0.0')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.styles.templates.js?v=1.0.1')}}"></script>

    <script>
        var table_main_dt = '<table id="dt_salr" class="table table-bordered text-wrap wrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="select" data-name="group_account" class="text-center">GROUP ACCOUNT</th>' +
            '<th data-type="select" data-name="gl_account" class="text-center"> GL ACCOUNT</th>' +
            '<th data-type="select" data-name="cost_center" class="text-center">COST CENTER</th>' +
            '<th data-type="text" data-name="periode" class="text-center">PERIODE</th>' +
            '<th data-type="text" data-name="value" class="text-center">VALUE</th>' +
            '<th data-type="text" data-name="aksi" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {

            get_data()

            $('#bulan_periode').bootstrapdatepicker({
                format: "MM",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true,
                showOnFocus: false,
            }).on('click', function () {
                $('#bulan_periode').bootstrapdatepicker("show");
                $('.datepicker-switch').css('display', 'none');
                $('.prev').css('display', 'none');
                $('.next').css('display', 'none');
            }).on('change', function () {
                $("#template").css("display", "block");
            });

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
                $("#table_main").empty();
                get_data()
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
                $("#table_main").empty();
                get_data()
            })

            $('#data_main_version_add').select2({
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
            })

            $('#data_detail_version_add').bootstrapdatepicker({
                format: "MM",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true,
                showOnFocus: false,
            }).on('click', function () {
                $('#data_detail_version_add').bootstrapdatepicker("show");
                $('.datepicker-switch').css('display', 'none');
                $('.prev').css('display', 'none');
                $('.next').css('display', 'none');
            }).on('change', function () {
                $("#template").css("display", "block");
            });

            $('#data_detail_version_horizontal').bootstrapdatepicker({
                format: "MM",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true,
                showOnFocus: false,
            }).on('click', function () {
                $('#data_detail_version_horizontal').bootstrapdatepicker("show");
                $('.datepicker-switch').css('display', 'none');
                $('.prev').css('display', 'none');
                $('.next').css('display', 'none');
            });

            $('#data_main_version_import').select2({
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
            })

            $('#data_main_version_horizontal').select2({
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
                $("#format_pick").css("display", "block");
            })

            $('#bulan_satuan_filter1').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            }).on('change', function () {
                $("#inflasi_pick").css("display", "block");
                list_inflasi()
            });

            $('#value').on('keyup', function(){
                let rupiah = formatRupiah($(this).val(), "Rp ")
                $(this).val(rupiah)
            });

            $('#tabs_vertical').on('click', function () {
                $('#dt_salr').DataTable().ajax.reload();
            })

            $('#data_main_ga_account').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Group Account',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('group_account_fc_select') }}",
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
                var group_account = $('#data_main_ga_account').val();
                $('#data_main_gl_account').append('<option selected disabled value="">Pilih General Ledger Account</option>').select2({
                    dropdownParent: $('#modal_add'),
                    placeholder: 'Pilih General Ledger Account',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('general_ledger_fc_select') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                group_account:group_account

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

            $('#data_main_partner_cost_center').select2({
                dropdownParent: $('#modal_add'),
                tags : true,
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

            $("#template").on('click', function () {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhrFields:{
                        responseType: 'blob'
                    },
                    url: '{{route('export_salr')}}',
                    success: function(result, status, xhr) {

                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'SALR.xlsx');

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

            $('#submit_import').on('click', function () {
                $("#submit_import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
                $("#back_import").attr("disabled", true);
                var version = $('#data_main_version_import').val();
                var date = $('#bulan_periode').val();

                if (version !== null && date !== '' && $('#file').val() !== ''){
                    let file = new FormData($("#form_input_salr")[0]);
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('check_salr')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version: version,
                            periode: date
                        },
                        success:function (response) {
                            if (response.code === 200){
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
                                        $('#local_loader').show();
                                        // $('#salrs_local_loader').css('display', 'block');
                                        // $('#count_load').val('123456789');
                                        importStore(file)
                                    }else {
                                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                                        $("#back_import").attr("disabled", true);
                                    }
                                })
                            }else if (response.code === 201){
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
                                        $('#local_loader').show();
                                        // $('#salrs_local_loader').css('display', 'block');
                                        // $('#count_load').val('123456789');
                                        importStore(file)
                                    }else {
                                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                                        $("#back_import").attr("disabled", false);
                                    }
                                })
                            }
                        },
                        error: function (response) {
                            $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                            $("#back_import").attr("disabled", false);
                            handleError(response)
                        }
                    })
                }else {
                    Swal.fire({
                        title: 'PERINGATAN',
                        text: "Terdapat Data Bulan dan file yang kosong. Silakan Isi data tersebut",
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

            function importStore(file) {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    url: '{{route('import_salr')}}',
                    data: file,
                    success:function (response) {
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                        // $('#salrs_local_loader').css('display', 'none');
                        // $('#count_load').val('123456789');
                        // $('#count_load').css('display', 'none');
                        $('#local_loader').hide();
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
                                    $("#data_main_version").val('').trigger('change')
                                    $("#data_detal_version").val('').trigger('change')
                                    $('#dt_salr').DataTable().ajax.reload();
                                }
                            })
                    },
                    error: function (response) {
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                        // $('#salrs_local_loader').css('display', 'none');
                        // $('#count_load').val('123456789');
                        $('#local_loader').hide();
                        handleError(response)

                    }
                })
            }

            $('#filter_inflasi').select2({
                placeholder: 'Pilih Format',
                width: '100%',
                allowClear: false,
            }).on('change', function () {
                if ($('#filter_inflasi').val() === '1'){
                    $("#versi_pick").css("display", "block");
                    list_inflasi()
                }else {
                    $("#versi_pick").css("display", "none");
                }
            })

            $('#filter_format').select2({
                placeholder: 'Pilih Format',
                width: '100%',
                allowClear: false,
            }).on('change', function () {
                if ($('#filter_format').val() === '0'){
                    $("#month_pick_range").css("display", "none");
                    $("#month_pick").css("display", "none");
                    $("#inflasi_pick").css("display", "none");

                    // $("#month_pick_range_versi").css("display", "block");

                    // set_versi_date()
                }else if ($('#filter_format').val() === '1'){
                    $("#month_pick_range").css("display", "none");
                    $("#month_pick_range_versi").css("display", "none");

                    $("#month_pick").css("display", "block");
                    $("#inflasi_pick").css("display", "block");
                    // set_month_date()

                }else if($('#filter_format').val() === '2'){
                    $("#month_pick").css("display", "none");
                    $("#month_pick_range_versi").css("display", "none");
                    $("#inflasi_pick").css("display", "none");

                    $("#month_pick_range").css("display", "block");
                    set_month_date_custom()
                }
            })

            function set_versi_date() {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('check_version_salrs')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        version: $('#data_main_version_horizontal').val(),
                    },
                    success:function (response) {
                        $('#bulan_filter1_versi').val(helpDateFormat(response.data['awal_periode'],'se'))
                        $('#bulan_filter2_versi').val(helpDateFormat(response.data['akhir_periode'],'se'))
                    },
                    error: function (response) {
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                        handleError(response)
                    }
                })
            }

            function set_month_date() {
                var data_version = $('#data_main_version_horizontal').val();
                $('#data_detail_version_horizontal').append('<option selected disabled value="">Pilih Bulan</option>').select2({
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
            }

            function set_month_date_custom() {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('check_version_salrs')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        version: $('#data_main_version_horizontal').val(),
                    },
                    success:function (response) {
                        // var awal_periode = moment(response.data['awal_periode'])
                        // var akhir_periode = moment(response.data['akhir_periode'])
                        // console.log(response.data['akhir_periode'], awal_periode, akhir_periode)
                        $('#bulan_filter1').bootstrapdatepicker({
                            format: "MM",
                            viewMode: "months",
                            minViewMode: "months",
                            autoclose:true,
                            // startDate: response.data['awal_periode'],
                            // endDate: response.data['akhir_periode'],
                        });

                        $('#bulan_filter2').bootstrapdatepicker({
                            format: "MM",
                            viewMode: "months",
                            minViewMode: "months",
                            autoclose:true,
                            // startDate: response.data['awal_periode'],
                            // endDate: response.data['akhir_periode'],
                        });
                    },
                    error: function (response) {
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                        handleError(response)
                    }
                })
            }

            $('#cost_center_format').select2({
                placeholder: 'Pilih Cost Center',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('cost_center_salr_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            company:$('#filter_company_ver').val()
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            })

            $('#btn_tampilkan').on('click', function () {
                let cek = true;
                if ($('#filter_format').val() !== null && $('#cost_center_format').val() !== null && $('#data_main_version_horizontal').val() !== null){
                    if ($('#filter_format').val() === '1'){

                        if ($('#filter_inflasi').val() === '1'){
                            if ($('#data_detail_version_horizontal').val() === null || $('#filter_inflasi').val() === null){
                                cek = false
                            }
                        }
                    }else if ($('#filter_format').val() === '2'){
                        if ($('#bulan_filter1').val() === '' || $('#bulan_filter2').val() === ''){
                            cek = false
                        }
                    }

                    if (cek){
                        $("#dinamic_table").empty();
                        get_data_horiz()
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
                                //
                            }
                        })
                    }
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
                            //
                        }
                    })
                }
            })

        })

        function get_data(){
            $('#table_main').html(table_main_dt)

            $('#dt_salr thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_salr thead');

            $("#dt_salr").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth: true,
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
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";

                                if(iName == 'price_rendaan_value'){
                                    input.id = 'price_rendaan_value_search'
                                }else if (iName == 'periode'){
                                    input.id = "periode_search";
                                }
                                $(input).
                                appendTo(cell.empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });

                                $('#periode_search').bootstrapdatepicker({
                                    format: "MM",
                                    viewMode: "months",
                                    minViewMode: "months",
                                    autoclose:true,
                                    showOnFocus: false,
                                }).on('click', function () {
                                    $('#periode_search').bootstrapdatepicker("show");
                                    $('.datepicker-switch').css('display', 'none');
                                    $('.prev').css('display', 'none');
                                    $('.next').css('display', 'none');
                                });

                                $('#price_rendaan_value_search').on('keyup', function(){
                                    let rupiah = formatRupiah($(this).val(), "Rp ")
                                    $(this).val(rupiah)
                                });
                            }
                            else if (data_type == 'select'){
                                var input = document.createElement("select");
                                var options = "";
                                if (iName == 'group_account'){
                                    input.className = "group_account_search form-control custom-select select2";
                                }else if (iName == 'gl_account'){
                                    input.className = "gl_account_search form-control custom-select select2";
                                }else if (iName == 'cost_center'){
                                    input.className = "cost_center_search form-control custom-select select2";
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


                        $('.group_account_search').select2({
                            placeholder: 'Pilih Group Account',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('group_account_fc_dt') }}",
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

                        $('.gl_account_search').select2({
                            placeholder: 'Pilih GL Account',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('gl_account_fc_dt') }}",
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

                    });
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    // {
                    //     extend: 'excel',
                    //     className: 'mb-5',
                    //     footer: true,
                    //     exportOptions:{
                    //     columns:[0,1,2,3,4]
                    //     },
                    //     title: '',
                    //     filename: 'Salr Tabel Vertikal',
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
                    //
                    //         mergeCells[0].appendChild(
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'B1:B2' }
                    //             })
                    //         );
                    //
                    //         mergeCells[0].appendChild(
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'C1:C2' }
                    //             })
                    //         );
                    //
                    //         mergeCells[0].appendChild(
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'D1:D2' }
                    //             })
                    //         );
                    //
                    //         mergeCells[0].appendChild(
                    //             _createNode( sheet, 'mergeCell', {
                    //                 attr: { ref: 'E1:E2' }
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
                    url : '{{route("salr")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company_ver').val(),
                        filter_version:$('#filter_version_ver').val()
                    }
                },
                columns: [
                    { data: 'group_account', name: 'filter_group_account', orderable:true},
                    { data: 'gl_account', name: 'filter_gl_account', orderable:true},
                    { data: 'cost_center', name: 'filter_cost_center', orderable:true},
                    { data: 'periode', name: 'filter_periode', orderable:true},
                    { data: 'value', name: 'filter_value', orderable:true, searchable: false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3,4,5]}
                ]

            })
        }


        function get_data_horiz(){
            var table = '<table id="h_dt_salr" class="table table-bordered text-nowrap key-buttons text-center" style="width: auto;">' +
                '<thead>' +
                '<tr id="primary">' +
                '<th class="align-middle" style="width: 5%;" rowspan="2">Group Account</th>' +
                '<th class="align-middle" style="width: 20%;"  rowspan="2">Group Account Desc</th>' +
                '</tr>' +
                '<tr id="secondary">' +
                '</tr>' +
                '</thead>' +
                '<tfoot>' +
                '<tr id="total_foot">' +
                '<th> Total </th>' +
                '<th> Perhitungan </th>' +
                '</tr>' +
                '</tfoot>' +
                '</table>'
            var kolom;
            var kolom1;
            var kolom_tfoot;
            var column = [
                {data: 'group_account_fc', orderable:false},
                {data: 'group_account_fc_desc', orderable:false}
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route("get_data_salr")}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    data:'dynamic',
                    format_data:$('#filter_format').val(),
                    cost_center:$('#cost_center_format').val(),
                    version:$('#data_main_version_horizontal').val(),

                    start_month_versi:$('#bulan_filter1_versi').val(),
                    end_month_versi:$('#bulan_filter2_versi').val(),

                    start_month:$('#bulan_filter1').val(),
                    end_month:$('#bulan_filter2').val(),

                    month:$('#data_detail_version_horizontal').val(),

                    inflasi:$('#filter_inflasi').val(),
                },
                success:function (response) {
                    for (let i = 0; i < response.cost_center.length;i++){
                        column.push({ data: i.toString(), orderable:false})
                        kolom += '<th class="text-center">'+response.cost_center[i].cost_center+'</th>';
                        kolom1 += '<th class="text-center">'+response.cost_center[i].cost_center_desc+'</th>';
                        kolom_tfoot += '<th class="text-center"></th>';
                    }

                    $("#primary").append(kolom);
                    $("#secondary").append(kolom1);
                    $("#total_foot").append(kolom_tfoot);
                    $('#h_dt_salr').DataTable().clear().destroy();
                    $("#h_dt_salr").DataTable({
                        scrollX: true,
                        dom: 'Bfrtip',
                        orderCellsTop: true,
                        processing: true,
                        serverSide: true,
                        scrollCollapse: true,
                        order:false,
                        fixedHeader: {
                            header: true,
                            headerOffset: $('#main_header').height()
                        },
                        fixedColumns:   {
                            left: 2
                        },
                        buttons: [
                            { extend: 'pageLength'},
                            {
                                text: 'Excel',
                                classname: 'mb-5',
                                action: function ( e, dt, node, config ) {
                                    var format = $('#filter_format').val();
                                    var cost_center = $('#cost_center_format').val();
                                    var version = $('#data_main_version_horizontal').val();

                                    var start_month = $('#bulan_filter1').val();
                                    var end_month = $('#bulan_filter2').val();
                                    var month = $('#data_main_version_horizontal').val();
                                    var inflasi = $('#filter_inflasi').val();

                                    let route_default = '{{ route("export_horizontal_salr") }}'
                                    let route_complete = route_default + "?cost_center="+ cost_center +
                                        "&start_month="+ start_month +"&end_month="+ end_month +"&format="+ format +"&inflasi="+ inflasi +"&month="+ month + "&version=" + version

                                    window.location = route_complete
                                }
                            }
                            // {
                            //     extend: 'excel',
                            //     className: 'mb-5',
                            //     title: '',
                            //     filename: 'Salr Tabel Horizontal',
                            //     footer: true,
                            //     exportOptions: {
                            //         format: {
                            //             body: function ( data, row, kolom, node ) {
                            //                 if (typeof data === 'undefined') {
                            //                     return;
                            //                 }
                            //                 if (data == null) {
                            //                     return data;
                            //                 }
                            //                 if ( kolom >= 2) {
                            //                     var arr = data.split(',');
                            //                     arr[0] = arr[0].toString().replace( /[\.]/g, "" );
                            //                     if (arr[0] > ','  || arr[1] > ',') {
                            //                         data = arr[0] + ',' + arr[1] + ',';
                            //                     } else {
                            //                         return '';
                            //                     }
                            //                     return data.toString().replace( /[^\d.,]/g, "," );
                            //                 }
                            //                 return data;
                            //             }
                            //         }
                            //     },
                            //     customize: function (file) {
                            //         var sheet = file.xl.worksheets['sheet1.xml'];
                            //         var style = file.xl['styles.xml'];
                            //
                            //         $('xf', style).find("alignment[horizontal='center']").attr("wrapText", "1");
                            //
                            //         var col = $('col', sheet);
                            //         $(col[0]).attr("width", 8.5);
                            //
                            //         for(let i = 0; i < response.cost_center.length;i++) {
                            //             const idx = i + 2
                            //             $(col[idx]).attr("width", 25).attr('customWidth', '1');
                            //         }
                            //
                            //         var mergeCells = $('mergeCells', sheet);
                            //
                            //         mergeCells[0].appendChild(
                            //             _createNode( sheet, 'mergeCell', {
                            //                 attr: { ref: 'A1:A2' }
                            //             })
                            //         );
                            //
                            //         mergeCells[0].appendChild(
                            //             _createNode( sheet, 'mergeCell', {
                            //                 attr: { ref: 'B1:B2' }
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
                            // },
                        ],
                        ajax: {
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url : '{{route("get_data_salr")}}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                data:'horizontal',
                                format_data:$('#filter_format').val(),
                                cost_center:$('#cost_center_format').val(),
                                version:$('#data_main_version_horizontal').val(),

                                start_month_versi:$('#bulan_filter1_versi').val(),
                                end_month_versi:$('#bulan_filter2_versi').val(),

                                start_month:$('#bulan_filter1').val(),
                                end_month:$('#bulan_filter2').val(),

                                month:$('#data_detail_version_horizontal').val(),

                                inflasi:$('#filter_inflasi').val(),
                            }
                        },
                        columns: column,
                        columnDefs: [
                            { targets: [0, 1], className: 'fs-6'},
                        ],
                        initComplete:function () {
                            let api = this.api();
                            api.columns.adjust().draw();
                        },
                        footerCallback: function () {
                            var response = this.api().ajax.json();
                            this.api().eq(0).columns().every(function (index) {
                                var api = this
                                if (index > 1){
                                    var count = parseInt(index) - 2
                                    var variable = 'total'+ count;
                                    $( api.column(index).footer() ).html(response[variable]);
                                }
                            })
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

        function list_inflasi() {
            var periode = $('#bulan_satuan_filter1').val();
            if (periode !== ''){
                $('#versi_format').append('<option selected disabled value="">Pilih Inflasi</option>').select2({
                    placeholder: 'Pilih Inflasi',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('version_inflasi_select') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                periode:periode
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    }
                })
            }else {
                $('#versi_format').select2({
                    placeholder: 'Pilih Inflasi',
                    width: '100%',
                    allowClear: false,
                })
            }

        }

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_salr')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    ga_account:$('#data_main_ga_account').val(),
                    gl_account:$('#data_main_gl_account').val(),
                    cost_center:$('#data_main_cost_center').val(),
                    version:$('#data_main_version_add').val(),
                    date:$('#data_detail_version_add').val(),
                    value:$('#value').val(),
                    nama:$('#nama').val(),
                    partner_cost_center:$('#data_main_partner_cost_center').val(),
                    username:$('#username').val(),
                    material:$('#data_main_material').val(),
                    document_num:$('#document_num').val(),
                    document_num_desc:$('#document_num_desc').val(),
                    purchase_order:$('#purchase_order').val(),
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
                            $('#data_main_ga_account').val('').trigger("change");
                            $('#data_main_gl_account').val('').trigger("change");
                            $('#data_main_cost_center').val('').trigger("change");
                            $('#data_main_partner_cost_center').val('').trigger("change");
                            $('#data_main_material').val('').trigger("change");
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            // update_dt_horizontal()
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_salr').DataTable().ajax.reload();
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    // $('#dt_salr').DataTable().ajax.reload();
                }
            })
        })

        function update_salr(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_salr')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    ga_account:$('#edit_data_main_ga_account'+id).val(),
                    gl_account:$('#edit_data_main_gl_account'+id).val(),
                    cost_center:$('#edit_data_main_cost_center'+id).val(),
                    version:$('#data_main_version_update'+id).val(),
                    date:$('#data_detail_version_update'+id).val(),
                    value:$('#edit_value'+id).val(),
                    nama:$('#edit_nama'+id).val(),
                    partner_cost_center:$('#edit_data_main_partner_cost_center'+id).val(),
                    username:$('#edit_username'+id).val(),
                    material:$('#edit_data_main_material'+id).val(),
                    document_num:$('#edit_document_num'+id).val(),
                    document_num_desc:$('#edit_document_num_desc'+id).val(),
                    purchase_order:$('#edit_purchase_order'+id).val(),
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
                                // update_dt_horizontal()
                                // $("#table_main").empty();
                                // get_data()
                                $('#dt_salr').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    // $('#dt_salr').DataTable().ajax.reload();
                }
            })
        }

        function delete_salr(id) {
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
                        url: '{{route('delete_salr')}}',
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
                                        $('#dt_salr').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_salr').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>

@endsection
