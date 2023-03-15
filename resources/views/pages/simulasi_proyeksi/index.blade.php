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
            <h4 class="page-title mb-0 text-primary">Simulasi Proyeksi</h4>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
{{--                        --}}{{-- <div class="mb-5 row">--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-label">VERSI</label>--}}
{{--                                <select id="filter_version_generate" class="form-control custom-select select2">--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="btn-list mb-5">--}}
{{--                                <button type="button" class="btn btn-primary btn-pill" id="btn_generate"><i class="fa fa-search me-2 fs-14"></i> Generate</button>--}}
{{--                            </div>--}}
{{--                        </div> --}}
                        <div class="mb-5 row">
                            <div class="form-group">
                                <label class="form-label">VERSI</label>
                                <select id="filter_version" class="form-control custom-select select2">
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">PRODUK</label>
                                <select id="filter_material" class="form-control custom-select select2">
                                    {{-- <option value="all" selected>Semua</option> --}}
                                </select>
                            </div>
                            <div class="form-group" id="format_plant">
                                <label class="form-label">PLANT</label>
                                <select id="filter_plant" class="form-control custom-select select2">
                                    {{-- <option value="all" selected>Semua</option> --}}
                                </select>
                            </div>
                            {{-- <div class="form-group" id="format_plant">
                                <label class="form-label">COST CENTER</label>
                                <select id="filter_cost_center" class="form-control custom-select select2">
                                    <option value="all" selected>Semua</option>
                                </select>
                            </div> --}}
                            <div class="btn-list mb-5">
                                <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
                            </div>
                        </div>
                        <div class="table-responsive" id="dinamic_table">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    {{-- <script src="{{ asset('/assets/js/workers/ex.js') }}"></script>
    <script>
        if (!navigator.serviceWorker.controller) {
            navigator.serviceWorker.register("/assets/js/workers/ex.js").then(function (reg) {
                console.log("Service worker has been registered for scope: " + reg.scope);
            });
        }
    </script> --}}

    <!-- Custom Script -->
    <script src="{{asset('assets/plugins/datatables/Buttons/js/dataTables.buttons.js?v=1.0.1')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.js?v=1.0.2')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.styles.js?v=1.0.0')}}"></script>
    <script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.styles.templates.js?v=1.0.1')}}"></script>


    <script>
        const alphabet = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        const alphabet2nd = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        $(document).ready(function () {
            // get_data_horiz()

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
            })

            $('#filter_material').select2({
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

            $('#filter_plant').select2({
                placeholder: 'Pilih Plant',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('plant_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            });

            $('#filter_cost_center').select2({
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

            $('#btn_tampilkan').on('click', function () {
                $("#dinamic_table").empty();
                get_data_horiz()
            })

            $('#btn_generate').on('click', function () {
                $("#dinamic_table").empty();
                generate_data()
            })
        })

        function get_data_horiz(){
            var table = `
            <table id="h_dt_simulasi_proyeksi" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                    <tr id="dinamic_tr_top">
                    </tr>
                    <tr id="dinamic_tr">
                    </tr>
                </thead>
            </table>`
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">JENIS BIAYA</th>'
            var kolom = ''
            var column = [
                { data: 'name', orderable:false},
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "POST",
                url : '{{route("header_simulasi_proyeksi")}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#filter_version').val(),
                    produk:$('#filter_material').val(),
                    plant:$('#filter_plant').val(),
                    cost_center:$('#filter_cost_center').val(),
                },
                success:function (response) {
                    // console.log(response);
                    for (let i = 0; i < response.asumsi.length;i++){

                        column.push({ data: i.toString()+'harga_satuan', orderable:false});
                        column.push({ data: i.toString()+'cr', orderable:false});
                        column.push({ data: i.toString()+'biaya_perton', orderable:false});
                        column.push({ data: i.toString()+'total_biaya', orderable:false});

                        kolom_top += '<th colspan="4" class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+'<br>'+ response.produk[0].material_code + ' ' + response.produk[0].material_name +'<br>'+ response.plant[0].plant_code + ' ' + response.plant[0].plant_desc + '<br> Kuantum Produksi ' + helpRibuan(response.kuantum_produksi[i].kuantum_produksi) +'</th>';

                        // kolom_top += '<th colspan="4" class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+ '</th>';

                        kolom += '<th class="text-center">Harga Satuan</th><th class="text-center">CR</th><th class="text-center">Biaya Per Ton</th></th><th class="text-center">Total Biaya</th>';
                    }

                    // for (let j = 0; j < response.asumsi.length * 4 ; j++) {
                    //     column.push({ data: j.toString(), orderable:false})
                    // }

                    $("#dinamic_tr_top").append(kolom_top);
                    $("#dinamic_tr").append(kolom);
                    // $('#h_dt_simulasi_proyeksi').DataTable().clear().destroy();
                    $("#h_dt_simulasi_proyeksi").DataTable({
                        scrollX: true,
                        dom: 'Bfrtip',
                        orderCellsTop: true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        // fixedHeader: {
                        //     header: true,
                        //     headerOffset: $('#main_header').height()
                        // },
                        fixedColumns:   {
                            left: 1
                        },
                        buttons: [
                            { extend: 'pageLength', className: 'mb-5' },
                            {
                                extend: 'excel',
                                className: 'mb-5',
                                title: '',
                                filename: 'Simulasi Proyeksi',
                                customize: function (file) {
                                    var sheet = file.xl.worksheets['sheet1.xml'];
                                    var style = file.xl['styles.xml'];
                                    $('xf', style).find("alignment[horizontal='center']").attr("wrapText", "1");
                                    $('row', sheet).first().attr('ht', '60').attr('customHeight', "1");
                                    var mergeCells = $('mergeCells', sheet);

                                    for (let i = 0; i < response.asumsi.length;i++) {
                                        const columnDef = generateAbjad(i)

                                        mergeCells[0].appendChild(
                                            _createNode( sheet, 'mergeCell', {
                                                attr: { ref: columnDef }
                                            })
                                        )
                                    }
                                    mergeCells.attr( 'count', mergeCells.attr( 'count' )+1 );

                                    function _createNode( doc, nodeName, opts ) {
                                        var tempNode = doc.createElement( nodeName );

                                        if ( opts ) {
                                            if ( opts.attr ) {
                                                $(tempNode).attr( opts.attr );
                                            }

                                            if ( opts.children ) {
                                                $.each( opts.children, function ( key, value ) {
                                                    tempNode.appendChild( value );
                                                } );
                                            }

                                            if ( opts.text !== null && opts.text !== undefined ) {
                                                tempNode.appendChild( doc.createTextNode( opts.text ) );
                                            }
                                        }

                                        return tempNode;
                                    }
                                }
                             }
                        ],
                        ajax: {
                            url : '{{route("simulasi_proyeksi")}}',
                            data: {
                                data:'index',
                                version:$('#filter_version').val(),
                                produk:$('#filter_material').val(),
                                plant:$('#filter_plant').val(),
                                cost_center:$('#filter_cost_center').val(),
                            }
                        },
                        columns: column,
                        createdRow: function ( row, data, index ) {
                            if (data.kategori === 0) {
                                $('td', row).eq(0).css('font-weight', 'bold');
                            } else {
                                $('td', row).eq(0).css('text-indent', '20px');
                            }
                        },
                        initComplete: function( settings ) {
                            $('.dataTables_scrollHead').css('overflow', 'auto');
                                $('.dataTables_scrollHead').on('scroll', function () {
                                $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                            });

                            $(document).on('scroll', function () {
                                $('.dtfh-floatingparenthead').on('scroll', function () {
                                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                                });
                            })

                            $('#h_dt_simulasi_proyeksi').DataTable().ajax.url('{{route('simulasi_proyeksi', ['save' => 'not_save'])}}').load();

                            // let api = this.api();
                            // api.columns.adjust().draw();
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                }
            })
        }
            // Function Generate Abjad
            function generateAbjad(idx) {
            const multiple = 4
            const start = (multiple * idx) + 1
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


        function generate_data(){
            var table = `
            <table id="h_dt_simulasi_proyeksi" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                    <tr id="dinamic_tr_top">
                    </tr>
                    <tr id="dinamic_tr">
                    </tr>
                </thead>
            </table>`
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">JENIS BIAYA</th>'
            var kolom = ''
            var column = [
                { data: 'name', orderable:false},
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "POST",
                url : '{{route("header_simulasi_proyeksi")}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#filter_version_generate').val(),
                    produk:$('#filter_material').val(),
                    plant:$('#filter_plant').val(),
                    cost_center:$('#filter_cost_center').val(),
                },
                success:function (response) {
                    // console.log(response);
                    // for (let i = 0; i < response.asumsi.length;i++){

                    //     column.push({ data: i.toString()+'harga_satuan', orderable:false});
                    //     column.push({ data: i.toString()+'cr', orderable:false});
                    //     column.push({ data: i.toString()+'biaya_perton', orderable:false});
                    //     column.push({ data: i.toString()+'total_biaya', orderable:false});

                    //     // kolom_top += '<th colspan="4" class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+'<br>'+ response.produk[0].material_code + ' ' + response.produk[0].material_name +'<br>'+ response.plant[0].plant_code + ' ' + response.plant[0].plant_desc + '</th>';

                    //     kolom_top += '<th colspan="4" class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+ '</th>';

                    //     kolom += '<th class="text-center">Harga Satuan</th><th class="text-center">CR</th><th class="text-center">Biaya Per Ton</th></th><th class="text-center">Total Biaya</th>';
                    // }

                    // for (let j = 0; j < response.asumsi.length * 4 ; j++) {
                    //     column.push({ data: j.toString(), orderable:false})
                    // }

                    // $("#dinamic_tr_top").append(kolom_top);
                    // $("#dinamic_tr").append(kolom);
                    // $('#h_dt_simulasi_proyeksi').DataTable().clear().destroy();

                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url : '{{route("store_simulasi_proyeksi")}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version:$('#filter_version_generate').val(),
                            // produk:$('#filter_material').val(),
                            // plant:$('#filter_plant').val(),
                            // cost_center:$('#filter_cost_center').val(),
                        },success:function (response) {
                            Swal.fire({
                                title: 'Data berhasil diproyeksikan',
                                text: 'pilih versi, produk, plant dan cost center untuk menampilkan data proyeksi',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonColor: '#019267',
                                confirmButtonText: 'Konfirmasi',
                            })

                            // setTimeout(function(){
                            //     window.location.reload();
                            // }, 500);

                            // $("#h_dt_simulasi_proyeksi").DataTable({
                            //     scrollX: true,
                            //     dom: 'Bfrtip',
                            //     orderCellsTop: true,
                            //     processing: true,
                            //     serverSide: true,
                            //     pageLength: -1,
                            //     // fixedHeader: {
                            //     //     header: true,
                            //     //     headerOffset: $('#main_header').height()
                            //     // },
                            //     fixedColumns:   {
                            //         left: 1
                            //     },
                            //     buttons: [
                            //         { extend: 'pageLength', className: 'mb-5' },
                            //         { extend: 'excel', className: 'mb-5' }
                            //     ],
                            //     ajax: {
                            //         url : '{{route("simulasi_proyeksi")}}',
                            //         data: {
                            //             data:'index',
                            //             version:$('#filter_version').val(),
                            //             produk:$('#filter_material').val(),
                            //             plant:$('#filter_plant').val(),
                            //             cost_center:$('#filter_cost_center').val(),
                            //         }
                            //     },
                            //     columns: column,
                            //     createdRow: function ( row, data, index ) {
                            //         if (data.kategori === 0) {
                            //             $('td', row).eq(0).css('font-weight', 'bold');
                            //         } else {
                            //             $('td', row).eq(0).css('text-indent', '20px');
                            //         }
                            //     },
                            //     initComplete: function( settings ) {
                            //         $('.dataTables_scrollHead').css('overflow', 'auto');
                            //             $('.dataTables_scrollHead').on('scroll', function () {
                            //             $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                            //         });

                            //         $(document).on('scroll', function () {
                            //             $('.dtfh-floatingparenthead').on('scroll', function () {
                            //                 $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                            //             });
                            //         })

                            //         $('#h_dt_simulasi_proyeksi').DataTable().ajax.url('{{route('simulasi_proyeksi', ['save' => 'not_save'])}}').load();

                            //         // let api = this.api();
                            //         // api.columns.adjust().draw();
                            //     }
                            // })
                        },
                        error:function (response) {
                            handleError(response)
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                }
            })
        }
    </script>
@endsection
