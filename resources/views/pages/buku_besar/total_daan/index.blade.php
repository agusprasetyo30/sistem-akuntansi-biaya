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
    {{-- <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                        <table id="dt_total_daan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='material_id' class="border-bottom-0 text-center">MATERIAL</th>
                                <th data-type='text' data-name='periode_id' class="border-bottom-0 text-center">VERSION</th>
                                <th data-type='text' data-name='region' class="border-bottom-0 text-center">REGION</th>
                                <th data-type='text' data-name='qty_rendaan_value' class="border-bottom-0 text-center">VALUE</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                                <th data-type='text' data-name='material_id' class="text-center"></th>
                                <th data-type='text' data-name='periode_id' class="text-center"></th>
                                <th data-type='text' data-name='region' class="text-center"></th>
                                <th data-type='text' data-name='qty_rendaan_value' class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="panel panel-primary">
                    <div class=" tab-menu-heading p-0 bg-light">
                        <div class="tabs-menu1 ">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class=""> <a href="#tab5" class="active" data-bs-toggle="tab">Vertikal</a> </li>
                                <li> <a href="#tab6" data-bs-toggle="tab">Horizontal</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active " id="tab5">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="">
                                                <div class="table-responsive" id="table-wrapper">
                                                    <table id="dt_total_daan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                                            <th data-type='text' data-name='material_id' class="border-bottom-0 text-center">MATERIAL</th>
                                                            <th data-type='text' data-name='periode_id' class="border-bottom-0 text-center">VERSION</th>
                                                            <th data-type='text' data-name='region' class="border-bottom-0 text-center">REGION</th>
                                                            <th data-type='text' data-name='qty_rendaan_value' class="border-bottom-0 text-center">VALUE</th>
                                                        </tr>
                                                        <tr>
                                                            <th data-type='text' data-name='nomor' class="text-center"></th>
                                                            <th data-type='text' data-name='material_id' class="text-center"></th>
                                                            <th data-type='text' data-name='periode_id' class="text-center"></th>
                                                            <th data-type='text' data-name='region' class="text-center"></th>
                                                            <th data-type='text' data-name='qty_rendaan_value' class="text-center"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="tab6">
                                <p> default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like</p>
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
            get_data()
        })

        function get_data(){
            $('#dt_total_daan').DataTable().clear().destroy();
            $("#dt_total_daan").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
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

                    this.api().columns().every(function (index) {
                        var column = this;
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo($(column.header()).empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            }
                        }

                    });
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5' }
                ],
                ajax: {
                    url : '{{route("total_daan")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                    { data: 'material', name: 'filter_material', orderable:false},
                    { data: 'version_periode', name: 'filter_version_periode', orderable:false},
                    { data: 'region_name', name: 'filter_region', orderable:false},
                    { data: 'value', name: 'qty_rendaan_value', orderable:false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0]}
                ],

            })
        }
    </script>
@endsection
