@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Mapping Kategori Balans</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('map_kategori_balans','create'))
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
                @endif
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Mapping Kategori Balans</div>
                </div>
                <div class="card-body">
                    <div class="mb-5 row">
                        @if (auth()->user()->mapping_akses('map_kategori_balans')->company_code == 'all')
                            <div class="form-group">
                                <label class="form-label">PERUSAHAAN</label>
                                <select id="filter_company" class="form-control custom-select select2">
                                    <option value="all" selected>Semua Perusahaan</option>
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="">
                        <div class="table-responsive" id="table_main">
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.mapping_balans.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_map_kategori_balans" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="versi" class="text-center">VERSI</th>' +
            '<th data-type="select" data-name="material" class="text-center">MATERIAL</th>' +
            '<th data-type="select" data-name="kategori_balans" class="text-center">KATEGORI BALANS</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {
            get_data()

            $('#filter_company').select2({
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

            $('#data_main_material_balans').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Material',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('material_balans_select') }}",
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

            $('#data_main_kategori_balans').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Kategori Plant',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('kategori_balans_select') }}",
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
                if ($('#data_main_kategori_balans').val() > 6){
                    $('#plant_field').css('display', 'block');
                    $('#data_main_plant').removeAttr('multiple', 'multiple').val([]).change().select2({
                        dropdownParent: $('#modal_add'),
                        placeholder: 'Pilih Plant / Cost Center',
                        width: '100%',
                        allowClear: false,
                        ajax: {
                            url: "{{route('glos_cc_balans_select')}}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    search: params.term,
                                    kategori: $('#data_main_kategori_balans').val(),
                                    material: $('#data_main_material_balans').val(),
                                };
                            },
                            processResults: function(response) {
                                return {
                                    results: response
                                };
                            }
                        }
                    });
                }else {
                    $('#plant_field').css('display', 'block');
                    $('#data_main_plant').attr('multiple', 'multiple').val([]).change().select2({
                        dropdownParent: $('#modal_add'),
                        placeholder: 'Pilih Plant / Cost Center',
                        width: '100%',
                        multiple: true,
                        allowClear: false,
                        ajax: {
                            url: "{{ route('plant_balans_select') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    search: params.term,
                                    kategori: $('#data_main_kategori_balans').val(),
                                    material: $('#data_main_material_balans').val(),
                                };
                            },
                            processResults: function(response) {
                                return {
                                    results: response
                                };
                            }
                        }
                    });
                }
            })

            $('#data_main_version').select2({
                dropdownParent: $('#modal_add'),
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
        })

        function get_data(){

            $('#table_main').html(table_main_dt)

            $('#dt_map_kategori_balans thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_map_kategori_balans thead');

            $("#dt_map_kategori_balans").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                deferRender:true,
                fixedHeader: {
                    header: true,
                    headerOffset: $('#main_header').height()
                },
                initComplete: function () {

                    $('.dataTables_scrollHead').css('overflow', 'auto');
                    $('.dataTables_scrollHead').on('scroll', function () {
                        // console.log('data')
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
                            }else if (data_type === 'select'){
                                var input = document.createElement("select");
                                input.className = "form-control custom-select select2";
                                var options = "";
                                if (iName === 'material'){
                                    input.className = "material_search form-control custom-select select2";
                                } else if (iName === 'kategori_balans'){
                                    input.className = "kategori_balans_search form-control custom-select select2";
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

                        $('.kategori_balans_search').select2({
                            placeholder: 'Pilih Kategori Balans',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('kategori_balans_dt') }}",
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

                        $('.material_search').select2({
                            placeholder: 'Pilih Material',
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

                    });
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2]
                        }, title: '',
                        filename: 'Mapping Kategori Balans'
                    }
                ],
                ajax: {
                    url : '{{route("map_kategori_balans")}}',
                    data: {
                        data:'index',
                        filter_company:$('#filter_company').val(),
                    }
                },
                columns: [
                    { data: 'version', name: 'filter_version', orderable:true},
                    { data: 'material', name: 'filter_material', orderable:true},
                    { data: 'kategori_balans', name: 'filter_kategori_balans', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3]}
                ]

            })
        }

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_map_kategori_balans')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    versi: $('#data_main_version').val(),
                    material_balans: $('#data_main_material_balans').val(),
                    kategori_balans: $('#data_main_kategori_balans').val(),
                    plant : $('#data_main_plant').val(),
                },
                success:function (response) {
                    // $("#tanggal").attr("disabled", true);
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result) => {
                        if (result.value) {
                            $('#modal_add').modal('hide');
                            $("#data_main_material_balans").val('Pilih Material').trigger('change');
                            $("#data_main_kategori_balans").val('Pilih Kategori Balans').trigger('change');
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            // $("#tanggal").attr("disabled", false);
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_map_kategori_balans').DataTable().ajax.reload();
                        }
                    })
                },
                error:function (response) {
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    handleError(response)
                }
            })
        })

        function update_map_balans(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_map_kategori_balans')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    versi: $('#edit_data_main_version'+id).val(),
                    plant : $('#edit_data_main_plant'+id).val(),
                    material_balans: $('#edit_material'+id).val(),
                    kategori_balans: $('#edit_kategori_balans'+id).val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    })
                        .then((result) => {
                            if (result.value) {
                                $('#modal_edit'+id).modal('hide')
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                                $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit"+id).attr("disabled", false);
                                $('#dt_map_kategori_balans').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    handleError(response)
                }
            })
        }

        function delete_mapping_balans(id) {
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
                        url: '{{route('delete_map_kategori_balans')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false,
                                confirmButtonColor: '#019267',
                                confirmButtonText: 'Konfirmasi',
                            })
                                .then((result) => {
                                    if (result.value) {
                                        // $("#table_main").empty();
                                        // get_data()
                                        $('#dt_map_kategori_balans').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_map_kategori_balans').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
@endsection
