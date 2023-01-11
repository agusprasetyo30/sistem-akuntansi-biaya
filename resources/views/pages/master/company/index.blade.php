@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Master Company</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
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
                <div class="card-title">Master Company</div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                    </div>
                </div>
            </div>
        </div>
        @include('pages.master.company.add')
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            table()

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#company_code').keyup(function(){
                this.value = this.value.toUpperCase();
            });
        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_company" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                <tr>
                    <th data-type='text' data-name='code' class="text-center">CODE</th>
                    <th data-type='text' data-name='nama' class="text-center">NAMA</th>
                    <th data-type='select' data-name='status' class="text-center">STATUS</th>
                    <th data-type='text' data-name='action' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_company thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_company thead');

            $('#dt_company').DataTable().clear().destroy();
            $("#dt_company").DataTable({
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
                                input.className = "form-control";
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

                    });
                },

                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2]
                        }, title: 'Master Company'
                    }
                ],

                ajax: {
                    url : '{{route("company")}}',
                    data: {data:'index'}
                },
                columns: [
                    //{ data: 'DT_RowIndex', name: 'company_code', searchable: false, orderable:true},
                    { data: 'company_code', name: 'company_code', orderable:true},
                    { data: 'company_name', name: 'company_name', orderable: true,},
                    //{ data: 'link_sso', name: 'link_sso', searchable: false, orderable:true},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,2,3]}
                ],

            })
        }

        $('#submit').on('click', function () {
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
                        url: '{{route('insert_company')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            company_code: $('#company_code').val(),
                            company_name: $('#company_name').val(),
                            link_sso: $('#link_sso').val(),
                            is_active: $('#is_active').val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#is_active').val('').trigger("change");
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 400){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                toastr.warning(response.msg, 'Warning')
                            }else {
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }


                        }
                    })

                }

            })
        })


        function update_company(id) {
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('update_company')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            company_code: $('#edit_company_code'+id).val(),
                            company_name: $('#edit_company_name'+id).val(),
                            link_sso: $('#edit_link_sso'+id).val(),
                            is_active: $('#edit_is_active'+id).val(),
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false,
                                confirmButtonColor: "#019267",
                                confirmButtonText: 'Konfirmasi',
                            })
                            .then((result) => {
                                if (result.value) {
                                    $('#modal_edit'+id).modal('hide')
                                    $('body').removeClass('modal-open');
                                    $('.modal-backdrop').remove();
                                    $('#dt_company').DataTable().ajax.reload();
                                }
                            })
                        },
                        error: function (response) {
                            handleError(response)
                        }
                    })
        }

        function delete_company(company_code) {
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
                        url: '{{route('delete_company')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            company_code: company_code,
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false,
                                confirmButtonColor: "#019267",
                                confirmButtonText: 'Konfirmasi',
                            })
                            .then((result) => {
                                if (result.value) {
                                    // table()
                                    $('#dt_company').DataTable().ajax.reload();
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
