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
                        <table id="dt_company" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                                <thead>
                            <tr>
                                <th data-type='text' data-name='no' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='code' class="border-bottom-0 text-center">CODE</th>
                                <th data-type='text' data-name='name' class="border-bottom-0 text-center">NAME</th>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='no' class="text-center"></th>
                                <th data-type='text' data-name='code' class="text-center"></th>
                                <th data-type='text' data-name='name' class="text-center"></th>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                            </tr>
                                </thead>
                            </table>
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
            get_data()

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#company_code').keyup(function(){
                this.value = this.value.toUpperCase();
            });
        })

        function get_data(){
            $('#dt_company').DataTable().clear().destroy();
            $("#dt_company").DataTable({
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
                            }else if (data_type == 'select'){
                                var input = document.createElement("select");
                                input.className = "form-control form-control-sm custom-select select2";
                                var options = "";
                                if (iName == 'status'){
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
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5' }
                ],

                ajax: {
                    url : '{{route("company")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'company_code', searchable: false, orderable:true},
                    { data: 'company_code', name: 'company_code', orderable:false},
                    { data: 'company_name', name: 'company_name', orderable: false,},
                    //{ data: 'link_sso', name: 'link_sso', searchable: false, orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,3]}
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


        function update_company(company_code) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan segera disimpan",
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
                        url: '{{route('update_company')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            company_code: company_code,
                            company_code: $('#edit_company_code'+company_code).val(),
                            company_name: $('#edit_company_name'+company_code).val(),
                            link_sso: $('#edit_link_sso'+company_code).val(),
                            is_active: $('#edit_is_active'+company_code).val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_edit'+company_code).modal('hide');
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                $('#modal_edit'+company_code).modal('hide');
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                $('#modal_edit'+company_code).modal('hide');
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }
                        }
                    })

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
                        success:function (response) {
                            if (response.Code === 200){
                                toastr.success('Data Berhasil Dihapus', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }
                        }
                    })

                }

            })
        }
    </script>
@endsection
