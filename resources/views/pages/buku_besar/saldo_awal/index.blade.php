@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Saldo Awal</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Basic DataTable</div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                        <table id="dt_saldo_awal" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='company_code' class="border-bottom-0 text-center">COMPANY CODE</th>
                                <th data-type='text' data-name='gl_account' class="border-bottom-0 text-center">GL ACCOUNT</th>
                                <th data-type='text' data-name='valuation_class' class="border-bottom-0 text-center">VALUATION CLASS</th>
                                <th data-type='text' data-name='price_control' class="border-bottom-0 text-center">PRICE CONTROL</th>
                                <th data-type='text' data-name='material' class="border-bottom-0 text-center">MATERIAL</th>
                                <th data-type='text' data-name='plant' class="border-bottom-0 text-center">PLANT</th>
                                <th data-type='text' data-name='total_stock' class="border-bottom-0 text-center">TOTAL STOCK</th>
                                <th data-type='text' data-name='total_value' class="border-bottom-0 text-center">TOTAL VALUE</th>
                                <th data-type='text' data-name='nilai_satuan' class="border-bottom-0 text-center">NILAI SATUAN</th>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                                <th data-type='text' data-name='company_code' class="text-center"></th>
                                <th data-type='text' data-name='gl_account' class="text-center"></th>
                                <th data-type='text' data-name='valuation_class' class="text-center"></th>
                                <th data-type='text' data-name='price_control' class="text-center"></th>
                                <th></th>
                                <th></th>
                                <th data-type='text' data-name='total_stock' class="text-center"></th>
                                <th data-type='text' data-name='total_value' class="text-center"></th>
                                <th data-type='text' data-name='nilai_satuan' class="text-center"></th>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
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
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            get_data()
        })

        function get_data(){
            $('#dt_saldo_awal').DataTable().clear().destroy();
            $("#dt_saldo_awal").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                // sortable: false,
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
                    'pageLength', 'csv', 'pdf', 'excel', 'print'
                ],
                ajax: {
                    url : '{{route("saldo_awal")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                    { data: 'company_code', name: 'company_code', orderable:false},
                    { data: 'gl_account', name: 'gl_account', orderable:false},
                    { data: 'valuation_class', name: 'valuation_class', orderable:false},
                    { data: 'price_control', name: 'price_control', orderable:false},
                    { data: 'material_name', name: 'material_name', orderable:false},
                    { data: 'plant_code', name: 'plant_code', orderable:false},
                    { data: 'total_stock', name: 'total_stock', orderable:false},
                    { data: 'total_value', name: 'total_value', orderable:false},
                    { data: 'nilai_satuan', name: 'nilai_satuan', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,10]}
                ],

            })
        }
    </script>
@endsection
