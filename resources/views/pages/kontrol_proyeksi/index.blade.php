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
                            <label class="form-label">Bulan </label>
                            <input type="text" class="form-control" name="filter_periode" id="filter_periode" placeholder="Bulan-Tahun" autocomplete="off" required>
                        </div>
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
                                    <div class="mb-4">
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
        $(document).ready(function () {

            $('#btn_tampilkan').on('click', function () {
                var company = $('#filter_company_code').val();
                var periode = $('#filter_periode').val();

                if (company !== null && periode !== ''){
                    $('#main_tab').css('display', 'block')
                    $("#parameter_simulasi").DataTable({
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
                            },
                            columns: [
                                {data: 'material', orderable:false},
                                {data: 'plant', orderable:false},
                                {data: 'keterangan', orderable:false},
                            ],
                        },
                        rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                            // if (aData.kategori_balans_id === 6){
                            //     // $('td', nRow).css('background-color', 'Red').css('color', 'white');
                            //     console.log(iDisplayIndex)
                            // }
                        }
                    });
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

            $('#filter_periode').bootstrapdatepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true,
                showOnFocus: false,
            }).on('click', function () {
                $('#filter_periode').bootstrapdatepicker("show");
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

        })
    </script>
@endsection
