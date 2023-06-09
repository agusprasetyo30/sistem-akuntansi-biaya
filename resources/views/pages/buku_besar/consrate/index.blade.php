@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Consumption Ratio</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('cons_rate','create'))
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
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
                    <div class="card-title">CONSUMPTION RATIO</div>
                </div>
                <div class="card-body">
                    <div class="mb-5 row">
                        @if (auth()->user()->mapping_akses('cons_rate')->company_code == 'all')
{{--                            <div class="form-group">--}}
{{--                                <label class="form-label">PERUSAHAAN</label>--}}
{{--                                <select id="filter_company" class="form-control custom-select select2">--}}
{{--                                    <option value="all" selected>Semua Perusahaan</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label class="form-label">PERUSAHAAN</label>
                                <select id="filter_company1" class="form-control custom-select select2">
                                </select>
                            </div>
                        @endif
                            <div class="form-group">
                                <label class="form-label">VERSI</label>
                                <select id="filter_version1" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Log Aktifitas</label>
                                <input type="text" id="log_aktifitas" class="form-control form-control-sm">
                            </div>

                            <div class="mb-5 row" style="display: none;" id="pick_submit" >
                                <div class="col-12">
                                    <button class="btn btn-info" type="button" id="btn_submit_data" name="btn_submit_data">Submit</button>
                                </div>
                            </div>

                            <div class="mb-5 row" style="display: none;" id="pick_approve">
                                <div class="col-12">
                                    <button class="btn btn-warning" type="button" id="btn_approve_data" name="btn_approve_data">Approve</button>
                                    <button class="btn btn-danger" type="button" id="btn_reject_data" name="btn_reject_data">Reject</button>
                                </div>
                            </div>


{{--                        <div class="mb-5 row">--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-label">VERSI</label>--}}
{{--                                <select id="filter_version" class="form-control custom-select select2">--}}
{{--                                    <option value="all" selected>Semua</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}



{{--                            @if (mapping_akses('cons_rate','submit') || mapping_akses('cons_rate','approve'))--}}
{{--                                <div class="col-12">--}}
{{--                                    @if (mapping_akses('cons_rate','submit') && $cons_rate)--}}
{{--                                        @if (!$cons_rate->submited_at)--}}
{{--                                        <button class="btn btn-info" type="button" id="btn_submit_data" name="btn_submit_data">Submit</button>--}}
{{--                                        @endif--}}
{{--                                    @endif--}}

{{--                                    @if (mapping_akses('cons_rate','approve') && $cons_rate)--}}
{{--                                        @if ($cons_rate->submited_at && !$cons_rate->approved_at && !$cons_rate->rejected_at)--}}
{{--                                        <button class="btn btn-warning" type="button" id="btn_approve_data" name="btn_approve_data">Approve</button>--}}
{{--                                        <button class="btn btn-danger" type="button" id="btn_reject_data" name="btn_reject_data">Reject</button>--}}
{{--                                        @endif--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>

                    <div class="">
                        <div class="table-responsive" id="table_main">
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.buku_besar.consrate.add')
            @include('pages.buku_besar.consrate.import')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>

        var table_main_dt = '<table id="dt_consrate" class="table table-bordered text-nowrap key-buttons">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="select" data-name="version" class="text-center">VERSI</th>' +
            '<th data-type="text" data-name="periode" class="text-center">PERIODE</th>' +
            '<th data-type="select" data-name="code" class="text-center">PLANT</th>' +
            '<th data-type="select" data-name="produk" class="text-center">PRODUK</th>' +
            '<th data-type="select" data-name="material" class="text-center">MATERIAL</th>' +
            '<th data-type="text" data-name="uom" class="text-center">UOM</th>' +
            '<th data-type="text" data-name="consrate" class="text-center">CONSRATE</th>' +
            '<th data-type="select" data-name="status" class="text-center">STATUS</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'
        $(document).ready(function () {
            // get_data()

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

            $('#filter_version').select2({
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

            $('#filter_company1').select2({
                placeholder: 'Pilih Versi',
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
                $("#table_main").empty();
                $('#pick_submit').css("display", "none")
                $('#pick_approve').css("display", "none")

                var company = $('#filter_company1').val();
                $('#filter_version1').append('<option selected disabled value="">Pilih Versi</option>').select2({
                    placeholder: 'Pilih Versi',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('version_company_select') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                company:company
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    }
                }).on('change', function () {
                    manajemen_akses_load()
                });
            })

            function manajemen_akses_load(){
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('general_mapping_akses')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        version:$('#filter_version1').val(),
                        company:$('#filter_company1').val(),
                        db:'cons_rate'
                    },
                    success:function (response_status) {
                        console.log(response_status)
                        if (response_status.code === 100){
                            if(response_status.akses['submit'] === true){
                                $('#pick_submit').css("display", "block")
                                $('#pick_approve').css("display", "none")

                                $('#log_aktifitas').val('Draft')
                            }
                        }else if (response_status.code === 101){
                            if (response_status.akses['approve'] === true){
                                $('#pick_submit').css("display", "none")
                                $('#pick_approve').css("display", "block")

                                $('#log_aktifitas').val('Submit')
                            }
                        }else if (response_status.code === 102){
                            $('#pick_submit').css("display", "none")
                            $('#pick_approve').css("display", "none")

                            $('#log_aktifitas').val('Approve')
                        }

                        get_data()
                    },
                    error: function (response) {
                        handleError(response)
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                    }
                })
            }

            $('#data_main_plant').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
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

            $('#submit_import').on('click', function () {
                $("#submit_import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
                $("#back_import").attr("disabled", true);
                if ($('#version').val() !== null && $('#file').val() !== ''){
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('check_consrate')}}',
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
                        cancelButtonColor: '#EF4B4B',
                        confirmButtonColor: '#019267',
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
                let file = new FormData($("#form-input-consrate")[0]);
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    url: '{{route('import_consrate')}}',
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
                                    // $("#table_main").empty();
                                    // get_data()
                                    $('#dt_consrate').DataTable().ajax.reload();
                                }
                            })
                    },
                    error: function (response) {
                        handleError(response)
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                        // $('#dt_consrate').DataTable().ajax.reload();
                    }
                })
            }

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
                    url: '{{route('export_consrate')}}',
                    data: {
                        temp:$('#version').val()
                    },
                    success: function(result, status, xhr) {

                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'cons_rate.xlsx');

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

            $('#data_main_produk').select2({
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
            }).on('change', function () {
                $('#data_main_material').append('<option selected disabled value="">Pilih Material</option>').select2({
                    dropdownParent: $('#modal_add'),
                    placeholder: 'Pilih Material',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('material_keyword_select') }}",
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
                });
            })

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#btn_submit_data').on('click', function () {
                Swal.fire({
                    title: 'PERINGATAN',
                    text: "Data Ini Akan Disubmit, Apakah Anda Yakin ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#019267',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonText: 'Konfirmasi',
                    cancelButtonText: 'Kembali'
                }).then((result)=>{
                    if (result.value){
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{route('submit_consrate')}}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                filter_version:$('#filter_version1').val()
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.msg,
                                    icon: response.type,
                                    allowOutsideClick: false,
                                    confirmButtonColor: "#019267",
                                    confirmButtonText: 'Konfirmasi',
                                }).then((result) => {
                                    if (result.value) {
                                        manajemen_akses_load()
                                    }
                                })
                            },
                            error: function (response) {
                                handleError(response)
                            }
                        })
                    }
                })
            })

            $('#btn_approve_data').on('click', function () {
                Swal.fire({
                    title: 'PERINGATAN',
                    text: "Data Ini Akan Diapprove, Apakah Anda Yakin ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#019267',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonText: 'Konfirmasi',
                    cancelButtonText: 'Kembali'
                }).then((result)=>{
                    if (result.value){
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{route('approve_consrate')}}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                filter_version:$('#filter_version1').val()
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.msg,
                                    icon: response.type,
                                    allowOutsideClick: false,
                                    confirmButtonColor: "#019267",
                                    confirmButtonText: 'Konfirmasi',
                                }).then((result) => {
                                    if (result.value) {
                                        manajemen_akses_load()
                                    }
                                })
                            },
                            error: function (response) {
                                handleError(response)
                            }
                        })
                    }
                })
            })

            $('#btn_reject_data').on('click', function () {
                Swal.fire({
                    title: 'PERINGATAN',
                    text: "Data Ini Akan Direject, Apakah Anda Yakin ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#019267',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonText: 'Konfirmasi',
                    cancelButtonText: 'Kembali'
                }).then((result)=>{
                    if (result.value){
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{route('reject_consrate')}}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                filter_version:$('#filter_version1').val()
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.msg,
                                    icon: response.type,
                                    allowOutsideClick: false,
                                    confirmButtonColor: "#019267",
                                    confirmButtonText: 'Konfirmasi',
                                }).then((result) => {
                                    if (result.value) {
                                        manajemen_akses_load()
                                    }
                                })
                            },
                            error: function (response) {
                                handleError(response)
                            }
                        })
                    }
                })
            })
        })

        function get_data(){
            $('#table_main').html(table_main_dt)

            $('#dt_consrate thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_consrate thead');

            $("#dt_consrate").DataTable({
                dom: 'Bfrtip',
                orderCellsTop: true,
                sortable: false,
                processing: true,
                serverSide: true,
                scrollX: true,
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

                                }else if (iName == 'version'){
                                    input.className = "version_search form-control custom-select select2";

                                }else if(iName == 'produk'){
                                    input.className = "produk_search form-control custom-select select2";

                                }else if (iName == 'material'){
                                    input.className = "material_search form-control custom-select select2";

                                }else if (iName == 'code'){
                                    input.className = "plant_search form-control custom-select select2";

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

                        $('.produk_search').select2({
                            placeholder: 'Pilih Produk',
                            width: '100%',
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
                            width: '100%',
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

                        $('.plant_search').select2({
                            placeholder: 'Pilih Material',
                            width: '100%',
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

                    });
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2,3,4,5,6,7]
                        }, title: '',
                        filename: 'Consumption Ratio'
                    }
                ],
                ajax: {
                    url : '{{route("consrate")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company1').val(),
                        filter_version:$('#filter_version1').val()
                    }
                },
                columns: [
                    { data: 'version', name: 'filter_version', orderable:true},
                    { data: 'periode', name: 'filter_periode', orderable:true},
                    { data: 'plant_code', name: 'filter_plant', orderable:true},
                    { data: 'product', name: 'filter_product', orderable:true},
                    { data: 'material', name: 'filter_material', orderable:true},
                    { data: 'uom', name: 'filter_uom', orderable:true},
                    { data: 'cons_rate', name: 'cons_rate', orderable:true},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3,4,5,6,7,8]}
                ]

            });
        }

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('check_consrate_dublicate')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id_plant: $('#data_main_plant').val(),
                    version: $('#data_main_version').val(),
                    id_asumsi: $('#data_detal_version').val(),
                    produk: $('#data_main_produk').val(),
                    material: $('#data_main_material').val(),
                    consrate: $('#consrate').val(),
                    is_active: $('#is_active').val(),
                },
                success: function (response) {
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
                                $.ajax({
                                    type: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: '{{route('insert_consrate')}}',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id_plant: $('#data_main_plant').val(),
                                        version: $('#data_main_version').val(),
                                        id_asumsi: $('#data_detal_version').val(),
                                        produk: $('#data_main_produk').val(),
                                        material: $('#data_main_material').val(),
                                        consrate: $('#consrate').val(),
                                        is_active: $('#is_active').val(),
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
                                                // $("#table_main").empty();
                                                // get_data()
                                                $('#dt_consrate').DataTable().ajax.reload();
                                            }
                                        })
                                    },
                                    error:function (response) {
                                        handleError(response)
                                        $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                                    }
                                })
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
                                $.ajax({
                                    type: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: '{{route('insert_consrate')}}',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id_plant: $('#data_main_plant').val(),
                                        version: $('#data_main_version').val(),
                                        id_asumsi: $('#data_detal_version').val(),
                                        produk: $('#data_main_produk').val(),
                                        material: $('#data_main_material').val(),
                                        consrate: $('#consrate').val(),
                                        is_active: $('#is_active').val(),
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
                                                // $("#table_main").empty();
                                                // get_data()
                                                $('#dt_consrate').DataTable().ajax.reload();
                                            }
                                        })
                                    },
                                    error:function (response) {
                                        handleError(response)
                                        $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                                    }
                                })
                            }
                        })
                    }
                }, error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                }
            })

        })

        function update_consrate(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('check_consrate_dublicate')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    id_plant: $('#edit_data_main_plant'+id).val(),
                    version: $('#edit_data_main_version'+id).val(),
                    id_asumsi: $('#edit_data_detal_version'+id).val(),
                    produk: $('#edit_data_main_produk'+id).val(),
                    material: $('#edit_data_main_material'+id).val(),
                    consrate: $('#edit_consrate'+id).val(),
                    is_active: $('#edit_is_active'+id).val(),
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
                                $.ajax({
                                    type: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: '{{route('update_consrate')}}',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id: id,
                                        id_plant: $('#edit_data_main_plant'+id).val(),
                                        version: $('#edit_data_main_version'+id).val(),
                                        id_asumsi: $('#edit_data_detal_version'+id).val(),
                                        produk: $('#edit_data_main_produk'+id).val(),
                                        material: $('#edit_data_main_material'+id).val(),
                                        consrate: $('#edit_consrate'+id).val(),
                                        is_active: $('#edit_is_active'+id).val(),
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
                                                    // $("#table_main").empty();
                                                    // get_data()
                                                    $('#dt_consrate').DataTable().ajax.reload();
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
                                $.ajax({
                                    type: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: '{{route('update_consrate')}}',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id: id,
                                        id_plant: $('#edit_data_main_plant'+id).val(),
                                        version: $('#edit_data_main_version'+id).val(),
                                        id_asumsi: $('#edit_data_detal_version'+id).val(),
                                        produk: $('#edit_data_main_produk'+id).val(),
                                        material: $('#edit_data_main_material'+id).val(),
                                        consrate: $('#edit_consrate'+id).val(),
                                        is_active: $('#edit_is_active'+id).val(),
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
                                                    // $("#table_main").empty();
                                                    // get_data()
                                                    $('#dt_consrate').DataTable().ajax.reload();
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
                        })

                    }
                },
                error:function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                }
            })

        }

        function delete_consrate(id) {
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
                        url: '{{route('delete_consrate')}}',
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
                                        // $("#table_main").empty();
                                        // get_data()
                                        $('#dt_consrate').DataTable().ajax.reload();
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
