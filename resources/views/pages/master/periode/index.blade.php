@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Periode</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add" class="btn btn-primary btn-pill"
                id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
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
                        <table id="dt_periode" class="table table-bordered text-nowrap key-buttons"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                    <th data-type='text' data-name='nama' class="border-bottom-0 text-center">NAMA</th>
                                    <th data-type='text' data-name='awal_periode' class="border-bottom-0 text-center">
                                        AWAL PERIODE</th>
                                    <th data-type='text' data-name='akhir_periode' class="border-bottom-0 text-center">
                                        AKHIR PERIODE</th>
                                    <th data-type='select' data-name='status' class="border-bottom-0 text-center">STATUS
                                    </th>
                                    <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION
                                    </th>
                                </tr>
                                <tr>
                                    <th data-type='text' data-name='nomor' class="text-center"></th>
                                    <th data-type='text' data-name='nama' class="text-center"></th>
                                    <th data-type='text' data-name='awal_periode' class="text-center"></th>
                                    <th data-type='text' data-name='akhir_periode' class="text-center"></th>
                                    <th data-type='select' data-name='status' class="text-center"></th>
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
        {{-- @include('pages.master.periode.add') --}}
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
<script>
    $(document).ready(function () {
        get_data()
    })

    function get_data() {
        $('#dt_periode').DataTable().clear().destroy();
        $("#dt_periode").DataTable({
            scrollX: true,
            dom: 'Bfrtip',
            // sortable: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
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
                    if (isSearchable) {
                        if (data_type == 'text') {
                            var input = document.createElement("input");
                            input.className = "form-control form-control-sm";
                            input.styleName = "width: 100%;";
                            $(input).
                            appendTo($(column.header()).empty()).
                            on('change clear', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        } else if (data_type == 'select') {
                            var input = document.createElement("select");
                            input.className = "form-control form-control-sm custom-select select2";
                            var options = "";
                            if (iName == 'status') {
                                options += '<option value="">Semua</option>';
                                @foreach (status_is_active() as $key => $value)
                                    options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                @endforeach
                            }
                            input.innerHTML = options
                            $(input).appendTo($(column.header()).empty())
                                .on('change clear', function () {
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
                url: '{{route("periode")}}',
                data: {
                    data: 'index'
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id',
                    searchable: false,
                    orderable: true
                },
                {
                    data: 'periode_name',
                    name: 'periode_name',
                    orderable: false
                },
                {
                    data: 'awal_periode',
                    name: 'awal_periode',
                    orderable: false
                },
                {
                    data: 'akhir_periode',
                    name: 'akhir_periode',
                    orderable: false
                },
                {
                    data: 'status',
                    name: 'filter_status',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],
            columnDefs: [{
                className: 'text-center',
                targets: [0, 4, 5]
            }],
        })
    }

</script>
@endsection
