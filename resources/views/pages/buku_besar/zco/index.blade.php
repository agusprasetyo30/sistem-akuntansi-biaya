@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">ZCO</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import"  class="btn btn-outline-primary" id="btn-import"><i class="fe fe-download me-2"></i> Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel panel-primary">
                    <div class=" tab-menu-heading p-0 bg-light">
                        <div class="tabs-menu1 ">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="" id="tabs_vertical"> <a href="#vertical" class="active" data-bs-toggle="tab">Vertikal</a> </li>
                                <li id="tabs_horizontal"> <a href="#horizontal" data-bs-toggle="tab">Horizontal</a> </li>
                                <li id="tabs_group_account"> <a href="#group_account" data-bs-toggle="tab">Group Account</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active " id="vertical">
                                <div class="">
                                    <div class="table-responsive" id="table-wrapper">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="horizontal">
                                <div class="mb-2 row">
                                    <div class="form-group">
                                        <label class="form-label">PRODUK</label>
                                        <select id="filter_material" class="form-control custom-select select2">
                                        </select>
                                    </div>

                                </div>
                                <div class="mt-auto">
                                    <div class="table-responsive" id="dinamic_table">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="group_account">
                                {{-- <div class="">
                                    <div class="table-responsive" id="table-wrapper">
                                        
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @include('pages.buku_besar.zco.add')
            @include('pages.buku_besar.zco.import')
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

            $('#data_main_plant').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Plant',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('plant_select') }}",
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

            $('#data_main_produk').select2({
                dropdownParent: $('#modal_add'),
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

            $('#data_main_cost_element').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Cost Element',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('cost_element_select') }}",
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

            $('#data_main_material').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Material',
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

            $('#filter_material').select2({
                placeholder: 'Pilih Produk',
                width: '100%',
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
            }).on('change', function () {
                $("#dinamic_table").empty();
                get_data_horiz()
            })

            // $('#data_main_version').select2({
            //     dropdownParent: $('#modal_add'),
            //     placeholder: 'Pilih Versi',
            //     width: '100%',
            //     allowClear: false,
            //     ajax: {
            //         url: "{{ route('version_select') }}",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 search: params.term
            //             };
            //         },
            //         processResults: function(response) {
            //             return {
            //                 results: response
            //             };
            //         }
            //     }
            // }).on('change', function () {
            //     var data_version = $('#data_main_version').val();
            //     $('#data_detail_version').append('<option selected disabled value="">Pilih Bulan</option>').select2({
            //         dropdownParent: $('#modal_add'),
            //         placeholder: 'Pilih Bulan',
            //         width: '100%',
            //         allowClear: false,
            //         ajax: {
            //             url: "{{ route('version_detail_select') }}",
            //             dataType: 'json',
            //             delay: 250,
            //             data: function (params) {
            //                 return {
            //                     search: params.term,
            //                     version:data_version

            //                 };
            //             },
            //             processResults: function(response) {
            //                 return {
            //                     results: response
            //                 };
            //             }
            //         }
            //     });
            // })

            // $('#version').select2({
            //     dropdownParent: $('#modal_import'),
            //     placeholder: 'Pilih Versi',
            //     width: '100%',
            //     allowClear: false,
            //     ajax: {
            //         url: "{{ route('version_select') }}",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 search: params.term
            //             };
            //         },
            //         processResults: function(response) {
            //             return {
            //                 results: response
            //             };
            //         }
            //     }
            // }).on('change', function () {
            //     $("#submit-export").css("display", "block");
            // })

            // $('#qty_renprod_value').on('keyup', function(){
            //     let rupiah = formatRupiah($(this).val(), "Rp ")
            //     $(this).val(rupiah)
            // });

            // $('#filter_version').select2({
            //     placeholder: 'Pilih Versi',
            //     width: '100%',
            //     allowClear: false,
            //     ajax: {
            //         url: "{{ route('version_select') }}",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 search: params.term
            //             };
            //         },
            //         processResults: function(response) {
            //             return {
            //                 results: response
            //             };
            //         }
            //     }
            // }).on('change', function () {
            //     $("#dinamic_table").empty();
            //     get_data_horiz()
            // })

        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_zco" class="table table-bordered text-nowrap key-buttons" style="width: 200%;">
                <thead>
                <tr>
                    <th data-type='select' data-name='plant' class="text-center">PLANT</th>
                    <th data-type='text' data-name='periode' class="text-center">PERIODE</th>
                    <th data-type='select' data-name='product' class="text-center">PRODUK</th>
                    <th data-type='text' data-name='product_qty' class="text-center">PRODUK QTY</th>
                    <th data-type='select' data-name='cost_element' class="text-center">COST ELEMENT</th>
                    <th data-type='select' data-name='material' class="text-center">MATERIAL</th>
                    <th data-type='text' data-name='total_qty' class="text-center">TOTAL QTY</th>
                    <th data-type='text' data-name='currency' class="text-center">CURRENCY</th>
                    <th data-type='text' data-name='total_amount' class="text-center">TOTAL AMOUNT</th>
                    <th data-type='text' data-name='unit_price_product' class="text-center">UNIT PRICE PRODUCT</th>
                    <th data-type='text' data-name='nomor' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_zco thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_zco thead');

            $('#dt_zco').DataTable().clear().destroy();
            $("#dt_zco").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[1, 'desc']],
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
                                if (iName == 'plant'){
                                    input.className = "plant_search form-control custom-select select2";
                                } else if(iName == 'product'){
                                    input.className = "product_search form-control custom-select select2";
                                } else if(iName == 'cost_element'){
                                    input.className = "cost_element_search form-control custom-select select2";
                                } else if(iName == 'material'){
                                    input.className = "material_search form-control custom-select select2";
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

                        $('.plant_search').select2({
                            placeholder: 'Pilih Plant',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('plant_dt') }}",
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
                        $('.product_search').select2({
                            placeholder: 'Pilih Produk',
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

                        $('.cost_element_search').select2({
                            placeholder: 'Pilih Cost Element',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('cost_element_dt') }}",
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
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns: 'th:not(:last-child)'
                    }, title: 'ZCO' }
                ],
                ajax: {
                    url : '{{route("zco")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'plant_code', name: 'filter_plant', orderable:true},
                    { data: 'periode', name: 'periode', orderable:true},
                    { data: 'product', name: 'filter_product', orderable:true},
                    { data: 'product_qty', name: 'product_qty', orderable:true},
                    { data: 'cost_element', name: 'filter_cost_element', orderable:true},
                    { data: 'material', name: 'filter_material', orderable:true},
                    { data: 'total_qty', name: 'total_qty', orderable:true},
                    { data: 'currency', name: 'currency', orderable:true},
                    { data: 'total_amount', name: 'total_amount', orderable:true},
                    { data: 'unit_price_product', name: 'unit_price_product', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0]}
                ],

            })
        }

        function get_data_horiz(){
            var table = `
            <table id="h_dt_zco" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                    <tr id="dinamic_tr_top">
                    </tr>
                    <tr id="dinamic_tr">
                    </tr>
                </thead>
            </table>`
            // var kolom = '<th class="text-center">BIAYA </th>'
            var kolom_top = '<th style="vertical-align : middle;text-align:center;" rowspan="2" class="text-center">BIAYA</th>'
            var kolom = ''
            var column = [
                { data: 'material_code', orderable:false},
            ]
            $("#dinamic_table").append(table);
            $.ajax({
                type: "GET",
                url : '{{route("zco")}}',
                data: {
                    data:'material',
                    material:$('#filter_material').val()
                },
                success:function (response) {
                    for (let i = 0; i < response.material.length;i++){
                        kolom_top += '<th colspan="4" class="text-center">'+ response.material[i].material_code+' '+ response.material[i].material_name+'</th>';
                        kolom += '<th class="text-center">Harga Satuan</th><th class="text-center">CR</th><th class="text-center">Biaya Per Ton</th></th><th class="text-center">Total Biaya</th>';
                    }

                    for (let j = 0; j < response.material.length * 4 ; j++) {
                        column.push({ data: j.toString(), orderable:false})
                    }

                    $("#dinamic_tr_top").append(kolom_top);
                    $("#dinamic_tr").append(kolom);
                    $('#h_dt_zco').DataTable().clear().destroy();
                    $("#h_dt_zco").DataTable({
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
                            url : '{{route("zco")}}',
                            data: {
                                data:'horizontal',
                                material:$('#filter_material').val()
                            }
                        },
                        columns: column,

                    })
                }
            })
        }

        function update_dt_horizontal() {
            if ($('#filter_material').val() != null){
                $("#dinamic_table").empty();
                get_data_horiz()
            }
        }

        $('#submit').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_zco')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    plant_code: $('#data_main_plant').val(),
                    periode: $('#periode').val(),
                    product_code: $('#data_main_produk').val(),
                    product_qty: $('#product_qty').val(),
                    cost_element: $('#data_main_cost_element').val(),
                    material_code: $('#data_main_material').val(),
                    total_qty: $('#total_qty').val(),
                    currency: $('#currency').val(),
                    total_amount: $('#total_amount').val(),
                    unit_price_product: $('#unit_price_product').val(),
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
                            $('#modal_add').modal('hide')
                            $("#modal_add input").val("")

                            update_dt_horizontal()
                            // table()
                            $('#dt_zco').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })

        })

        $('#submit-import').on('click', function () {
            $("#submit-import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back-import").attr("disabled", true);
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_zco')}}',
                data: file,
                success:function (response) {
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        if (result.value) {
                            $('#modal_import').modal('hide')
                            $("#modal_import input").val("")
                            // table()
                            update_dt_horizontal()
                            $('#dt_zco').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                }
            })

        })

        $('#submit-export').on('click', function () {
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('export_zco')}}',
                data: {
                    version: $('#version').val(),
                },
                success:function (result, status, xhr) {
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : 'zco.xlsx');

                    // The actual download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                },
                error: function(){
                    $('#modal_import').modal('hide');
                    $("#modal_import input").val("")
                    toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                }
            })
        })

        function update_zco(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_zco')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    plant_code: $('#edit_data_main_plant'+id).val(),
                    periode: $('#edit_periode'+id).val(),
                    product_code: $('#edit_data_main_produk'+id).val(),
                    product_qty:$('#edit_product_qty'+id).val(),
                    cost_element: $('#edit_data_main_cost_element'+id).val(),
                    material_code:$('#edit_data_main_material'+id).val(),
                    total_qty: $('#edit_total_qty'+id).val(),
                    currency:$('#edit_currency'+id).val(),
                    total_amount: $('#edit_total_amount'+id).val(),
                    unit_price_product:$('#edit_unit_price_product'+id).val(),

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
                            
                            update_dt_horizontal()
                            // table()
                            $('#dt_zco').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })

        }

        function delete_zco(id) {
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
                        url: '{{route('delete_zco')}}',
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
                                confirmButtonColor: "#019267",
                                confirmButtonText: 'Konfirmasi',
                            })
                            .then((result) => {
                                if (result.value) {
                                    update_dt_horizontal()
                                    // table()
                                    $('#dt_zco').DataTable().ajax.reload();
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