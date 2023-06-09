@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Total Pengadaan</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            {{-- <button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button> --}}
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-md-12">
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
                                    @if (auth()->user()->mapping_akses('qty_rendaan')->company_code == 'all')
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
                                    @if (auth()->user()->mapping_akses('qty_rendaan')->company_code == 'all')
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

                                    <div class="form-group">
                                        <label class="form-label">VALUE</label>
                                        <select id="filter_val" class="form-control custom-select select2">
                                            @foreach (value_dt() as $key => $value)
                                                options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                            @endforeach
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
        </div>
    </div>
</div>


<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            table()

            $('#tabs_vertical').on('click', function () {
                table()
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

            $('#filter_val').select2({
                placeholder: 'Pilih Value',
                width: '100%',
                allowClear: false,
            }).on('change', function () {
                $("#dinamic_table").empty();
                get_data_horiz()
            })
        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_total_daan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                <tr>
                    <th data-type='select' data-name='version' class="text-center">VERSI</th>
                    <th data-type='text' data-name='periode' class="text-center">PERIODE</th>
                    <th data-type='select' data-name='material' class="text-center">MATERIAL</th>
                    <th data-type='text' data-name='region' class="text-center">REGION</th>
                    <th data-type='text' data-name='value' class="text-center">VALUE</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_total_daan thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_total_daan thead');

            $('#dt_total_daan').DataTable().clear().destroy();
            $("#dt_total_daan").DataTable({
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
                                if (iName == 'material'){
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
                    {
                        extend: 'collection',
                        className: 'mb-5',
                        text:'Mata Uang',
                        buttons:[
                            {
                                text:'Rupiah',
                                action: function () {
                                    $('#dt_total_daan').DataTable().ajax.url('{{route('total_daan', ['currency' => 'Rupiah'])}}').load();
                                }
                            },
                            {
                                text:'Dollar',
                                action: function () {
                                    $('#dt_total_daan').DataTable().ajax.url('{{route('total_daan', ['currency' => 'Dollar'])}}').load();
                                }
                            }
                        ]

                    },
                    { 
                        extend: 'excel', 
                        className: 'mb-5',
                        title: '',
                        filename: 'Total Pengadaan - Vertikal'  
                    }
                ],
                ajax: {
                    url : '{{route("total_daan")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company_ver').val(),
                        filter_version:$('#filter_version_ver').val()
                    }
                },
                columns: [
                    { data: 'version', name: 'filter_version', orderable:true},
                    { data: 'periode', name: 'filter_periode', orderable:true},
                    { data: 'material', name: 'filter_material', orderable:true},
                    { data: 'region_desc', name: 'filter_region', orderable:true},
                    { data: 'value', name: 'value', searchable:false, orderable:false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0]}
                ],

            })
        }

        var mataUang = 'IDR'
        function get_data_horiz(){
            var table = '<table id="h_dt_total_daan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;"><thead><tr id="dinamic_tr"></tr></thead></table>'
            var kolom = '<th class="text-center">MATERIAL</th><th class="text-center">REGION</th>'
            var column = [
                { data: 'material', orderable:false},
                { data: 'region_desc', orderable:false}
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "GET",
                url : '{{route("total_daan")}}',
                data: {
                    data:'version',
                    version:$('#filter_version').val(),
                    company:$('#filter_company').val(),
                    val:$('#filter_val').val()
                },
                success:function (response) {
                    for (let i = 0; i < response.asumsi.length;i++){
                        column.push({ data: i.toString(), orderable:false})
                        kolom += '<th class="text-center">'+helpDateFormat(response.asumsi[i].month_year, 'bi')+'</th>';
                    }
                    $("#dinamic_tr").append(kolom);
                    $('#h_dt_total_daan').DataTable().clear().destroy();
                    $("#h_dt_total_daan").DataTable({
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
                                extend: 'collection',
                                className: 'mb-5',
                                text:'Mata Uang',
                                buttons:[
                                    {
                                        text:'Rupiah',
                                        action: function () {
                                            $('#h_dt_total_daan').DataTable().ajax.url('{{route('total_daan', ['currency' => 'Rupiah'])}}').load();
                                            mataUang = 'IDR'
                                        }
                                    },
                                    {
                                        text:'Dollar',
                                        action: function () {
                                            $('#h_dt_total_daan').DataTable().ajax.url('{{route('total_daan', ['currency' => 'Dollar'])}}').load();
                                            mataUang = 'USD'
                                        }
                                    }
                                ]

                            },
                            {
                                text: 'Excel',
                                action: function(e, dt, node, config) {
                                    
                                    let version_search = $('#filter_version').val()

                                    window.location = '{{ route("export_h_total_daan") }}' + "?version=" + version_search + "&value=" + $('#filter_val').val() + "&mata_uang=" + mataUang
                                },
                                className: 'mb-5'
                            }
                            // { 
                            //     extend: 'excel', 
                            //     className: 'mb-5',
                            //     title: '',
                            //     filename: 'Total Pengadaan - Horizontal' 
                            // }

                        ],
                        ajax: {
                            url : '{{route("total_daan")}}',
                            data: {
                                data:'horizontal',
                                version:$('#filter_version').val(),
                                company:$('#filter_company').val(),
                                val:$('#filter_val').val()
                            }
                        },
                        columns: column,
                        createdRow: function ( row, data, index ) {
                            // console.log(data);
                            // console.log(response.asumsi.length);
                            // if ( data['1'] != null ) {
                            //     $('td', row).eq(3).addClass('success');
                            // } else {
                            //     $('td', row).eq(3).addClass('danger');
                            // }

                            for (let i = 0; i < response.asumsi.length;i++){
                                // console.log(data[i])
                                if (data[i] === '-') {
                                    // console.log('yah kosong')
                                    $('td', row).eq(2+i).css('background-color', 'Salmon');
                                } else if (data[i] === "") {
                                    // console.log('ada isinya loh')
                                    $('td', row).eq(2+i).css('background-color', 'white');
                                    // $('td', row).css('background-color', 'Green');
                                } else {
                                    $('td', row).eq(2+i).css('background-color', 'DarkSeaGreen');
                                }
                            }
                        },
                        initComplete: function( settings ) {
                            let api = this.api();
                            api.columns.adjust().draw();
                        }
                    })
                }
            })
        }
    </script>
@endsection
