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
        <h4 class="page-title mb-0 text-primary">Balans</h4>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">PERHITUNGAN BALANS</div>
            </div>
            <div class="card-body">

                <div class="panel panel-primary">
                    <div class=" tab-menu-heading p-0 bg-light">
                        <div class="tabs-menu1 ">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="" id="tabs_vertical"> <a href="#generate" class="active" data-bs-toggle="tab">Generate</a> </li>
                                <li id="tabs_horizontal"> <a href="#laporan" data-bs-toggle="tab">Laporan</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active " id="generate">
                                <div class="mb-4">
                                    <div class="mb-4">
                                        <div class="form-group" id="cost_center_pick">
                                            <label class="form-label">Versi <span class="text-red">*</span></label>
                                            <select id="filter_version_generate" class="form-control custom-select select2">
                                            </select>
                                        </div>
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-primary btn-pill" id="btn_generate"><i class="fa fa-search me-2 fs-14"></i> Generate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="laporan">
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label">Versi <span class="text-red">*</span></label>
                                        <select id="filter_version_laporan" class="form-control custom-select select2">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">PRODUK</label>
                                        <select id="filter_material" class="form-control custom-select select2">
                                            <option value="all" selected>Semua</option>
                                        </select>
                                    </div>
                                    <div class="btn-list">
                                        <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="table-responsive" id="table_main">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.buku_besar.balans.add')
            @include('pages.buku_besar.balans.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_balans" class="table table-bordered text-nowrap key-buttons text-center" style="width: 100%;">' +
            '<thead>' +
            '<tr id="primary">' +
            '<th class="align-middle" style="width: 5%;" rowspan="3">Material</th>' +
            '<th class="align-middle" style="width: 20%;"  rowspan="3">Plant Code</th>' +
            '<th class="align-middle" style="width: 20%;"  rowspan="3">Keterangan</th>' +
            '</tr>' +
            '<tr id="secondary">' +
            '</tr>' +
            '<tr id="third">' +
            '</tr>' +
            '</thead>' +
            '</table>'

        $(document).ready(function () {

            $('#filter_version_generate').select2({
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

            $('#filter_version_laporan').select2({
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

            $('#filter_material').select2({
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('material_balans_dt') }}",
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

            $('#btn_tampilkan').on('click', function () {
                var versi = $('#filter_version_laporan').val();
                var material = $('#filter_material').val();
                if (versi !== null && material !== null){
                    get_data()
                }else {
                    Swal.fire({
                        title: 'PERINGATAN',
                        text: "Data Versi Kosong, Silahkan Isi Data Tersebut",
                        icon: 'warning',
                        confirmButtonColor: '#019267',
                        cancelButtonColor: '#EF4B4B',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result)=>{
                        // if (result.value){
                        //     $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                        // }
                    })
                }
            })

            $('#btn_generate').on('click', function () {
                var versi = $('#filter_version_generate').val();
                if (versi !== null){
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url : '{{route("check_dasar_balans")}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version:versi,
                        },
                        success:function (response) {
                            if (response.code === 201){
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
                                        generate_data()
                                    }
                                })
                            }else {
                                $('#local_loader').show();
                                generate_data()
                            }
                        }, error:function () {
                            Swal.fire({
                                title: 'PERINGATAN',
                                text: "Data Versi Kosong, Silahkan Isi Data Tersebut",
                                icon: 'warning',
                                confirmButtonColor: '#019267',
                                cancelButtonColor: '#EF4B4B',
                                confirmButtonText: 'Konfirmasi',
                            })
                        }
                    })
                }else {
                    Swal.fire({
                        title: 'PERINGATAN',
                        text: "Data Versi Kosong, Silahkan Isi Data Tersebut",
                        icon: 'warning',
                        confirmButtonColor: '#019267',
                        cancelButtonColor: '#EF4B4B',
                        confirmButtonText: 'Konfirmasi',
                    })
                }
            })
        })

        function get_data(){
            var kolom;
            var kolom1;
            var kolom2;
            var column = [
                {data: 'material', orderable:false},
                {data: 'plant', orderable:false},
                {data: 'keterangan', orderable:false},
            ]

            $('#table_main').html(table_main_dt)

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route("header_dasar_balans")}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#filter_version_laporan').val(),
                },
                success:function (response){
                    for (let i = 0; i < response.asumsi.length;i++){
                        column.push({ width:'5%', data: 'q'+i.toString(), orderable:false});
                        column.push({ width:'20%', data: 'p'+i.toString(), orderable:false});
                        column.push({ width:'20%', data: 'nilai'+i.toString(), orderable:false});

                        kolom += '<th class="text-center" colspan="3">'+helpDateFormat(response.asumsi[i].month_year)+'</th>';

                        kolom1 += '<th class="text-center">Q</th>' +
                            '<th class="text-center">P</th>' +
                            '<th class="text-center">Nilai = Q x P</th>';

                        kolom2 += '<th class="text-center">Ton</th>' +
                            '<th class="text-center">Rp/Ton</th>' +
                            '<th class="text-center">Nilai (Rp)</th>';
                    }

                    $("#primary").append(kolom);
                    $("#secondary").append(kolom1);
                    $("#third").append(kolom2);
                    // $('#dt_balans').DataTable().clear().destroy();
                    $("#dt_balans").DataTable({
                        scrollX: true,
                        dom: 'Bfrtip',
                        orderCellsTop: true,
                        processing: true,
                        serverSide: true,
                        deferRender:true,
                        // fixedHeader: {
                        //     header: true,
                        //     headerOffset: $('#main_header').height()
                        // },
                        fixedColumns:   {
                            left: 3
                        },
                        lengthMenu: [
                            [-1, 10, 25, 50],
                            ['All', 10, 25, 50],
                        ],
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

                            $('#dt_balans').DataTable().ajax.url('{{route('dasar_balans', ['save' => 'not_save'])}}').load();
                        },
                        buttons: [
                            { extend: 'pageLength', className: 'mb-5' },
                            { extend: 'excel', className: 'mb-5', exportOptions:{
                                }, title: 'Balans' }
                        ],
                        ajax: {
                            url : '{{route("dasar_balans")}}',
                            data: {
                                data:'index',
                                version:$('#filter_version_laporan').val(),
                                material:$('#filter_material').val(),
                            }
                        },
                        columns: column,
                        rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                            // if (aData.kategori_balans_id === 6){
                            //     // $('td', nRow).css('background-color', 'Red').css('color', 'white');
                            //     console.log(iDisplayIndex)
                            // }
                        }
                    });

                }
            })

        }

        function generate_data() {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route("store_dasar_balans")}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#filter_version_generate').val(),
                },success:function (response) {
                    $('#local_loader').hide();
                    Swal.fire({
                        title: 'Data berhasil diproyeksikan',
                        icon: 'success',
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',

                    })

                }
            })
        }
    </script>
@endsection
