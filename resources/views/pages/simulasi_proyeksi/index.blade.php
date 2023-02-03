@extends('layouts.app')

@section('styles')

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
                    <div class="">
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
    <script>

        $(document).ready(function () {
            get_data_horiz()
        })

        function get_data_horiz(){
            var table = '<table id="h_dt_simulasi_proyeksi" class="table table-bordered text-nowrap key-buttons" style="width: 100%;"><thead><tr id="dinamic_tr"></tr></thead></table>'
            var kolom = '<th class="text-center">KODE</th><th class="text-center">BIAYA</th>'
            var column = [
                { data: 'code', orderable:false},
                { data: 'name', orderable:false},
            ]
            $("#dinamic_table").append(table);
            $("#dinamic_tr").append(kolom);
            $('#h_dt_simulasi_proyeksi').DataTable().clear().destroy();
            $("#h_dt_simulasi_proyeksi").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                processing: true,
                serverSide: true,
                fixedHeader: {
                    header: true,
                    headerOffset: $('#main_header').height()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5' }
                ],
                ajax: {
                    url : '{{route("simulasi_proyeksi")}}',
                    data: {data:'index'}
                },
                columns: column,
                initComplete: function( settings ) {
                    let api = this.api();
                    api.columns.adjust().draw();
                }
            })
        }
    </script>
@endsection
