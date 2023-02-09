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
        <h4 class="page-title mb-0 text-primary">Balans</h4>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">PERHITUNGAN BALANS</div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="form-group" id="cost_center_pick">
                        <label class="form-label">Versi <span class="text-red">*</span></label>
                        <select id="filter_version" class="form-control custom-select select2">
                        </select>
                    </div>
                </div>
                <div class="">
                    <div class="table-responsive" id="table_main">
{{--                        <table id="h_dt_salr" class="table table-bordered text-nowrap key-buttons text-center" style="width: auto;">--}}
{{--                            <thead>--}}
{{--                            <tr id="primary">--}}
{{--                                <th class="align-middle font-weight-bold" style="width: 5%;" rowspan="3">Group Account</th>--}}
{{--                                <th class="align-middle font-weight-bold" style="width: 20%;"  rowspan="3">Group Account Desc</th>--}}
{{--                                <th class="font-weight-bold" colspan="3">2023-05-01</th>--}}

{{--                            </tr>--}}
{{--                            <tr id="secondary">--}}
{{--                                <th class="font-weight-bold">Q</th>--}}
{{--                                <th class="font-weight-bold">P</th>--}}
{{--                                <th class="font-weight-bold">Nilai = Q x P</th>--}}

{{--                            </tr>--}}
{{--                            <tr id="third">--}}
{{--                                <th class="font-weight-bold">Ton</th>--}}
{{--                                <th class="font-weight-bold">Rp/Ton</th>--}}
{{--                                <th class="font-weight-bold">Nilai (Rp)</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                        </table>--}}


                    </div>
                </div>
            </div>
            @include('pages.buku_besar.balans.add')
            @include('pages.buku_besar.balans.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_balans" class="table table-bordered text-nowrap key-buttons text-center" style="width: auto;">' +
            '<thead>' +
            '<tr id="primary">' +
            '<th class="align-middle" style="width: 5%;" rowspan="3">Material</th>' +
            '<th class="align-middle" style="width: 20%;"  rowspan="3">Plant Code</th>' +
            '<th class="align-middle" style="width: 20%;"  rowspan="3">Keterangan</th>' +
            '</tr>' +
            '<tr id="secondary">' +
            '</tr>' +
            '<tr id="third">' +
            '</tr>' +
            '</thead>' +
            '</table>'

        $(document).ready(function () {

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
                get_data()
            })
        })

        function get_data(){
            var kolom;
            var kolom1;
            var kolom2;
            var column = [
                {data: 'material', orderable:false},
                {data: 'plant', orderable:false},
                {data: 'keterangan', orderable:false},
            ]

            $('#table_main').html(table_main_dt)

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route("header_dasar_balans")}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    version:$('#filter_version').val(),
                },
                success:function (response){
                    for (let i = 0; i < response.asumsi.length;i++){
                        column.push({ data: 'q'+i.toString(), orderable:false});
                        column.push({ data: 'p'+i.toString(), orderable:false});
                        column.push({ data: 'nilai'+i.toString(), orderable:false});

                        kolom += '<th class="text-center" colspan="3">'+helpDateFormat(response.asumsi[i].month_year)+'</th>';

                        kolom1 += '<th class="text-center">Q</th>' +
                            '<th class="text-center">P</th>' +
                            '<th class="text-center">Nilai = Q x P</th>';

                        kolom2 += '<th >Ton</th>' +
                            '<th>Rp/Ton</th>' +
                            '<th>Nilai (Rp)</th>';
                    }
                    console.log(column)
                    $("#primary").append(kolom);
                    $("#secondary").append(kolom1);
                    $("#third").append(kolom2);
                    $("#dt_balans").DataTable({
                        scrollX: true,
                        dom: 'Bfrtip',
                        orderCellsTop: true,
                        autoWidth: true,
                        processing: true,
                        serverSide: true,
                        deferRender:true,
                        fixedHeader: {
                            header: true,
                            headerOffset: $('#main_header').height()
                        },fixedColumns:   {
                            left: 3
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
                            this.api().columns.adjust().draw()
                        },
                        buttons: [
                            { extend: 'pageLength', className: 'mb-5' },
                            { extend: 'excel', className: 'mb-5', exportOptions:{
                                }, title: 'Balans' }
                        ],
                        ajax: {
                            url : '{{route("dasar_balans")}}',
                            data: {data:'index'}
                        },
                        columns: column
                    });
                }
            })

        }

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_laba_rugi')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal:$('#tanggal').val(),
                    kategori_produk:$('#data_main_kategori_produk').val(),
                    biaya_penjualan: $('#biaya_penjualan').val(),
                    biaya_administrasi_umum: $('#biaya_administrasi_umum').val(),
                    biaya_bunga: $('#biaya_bunga').val(),
                },
                success:function (response) {
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
                            $("#modal_add input").val("")
                            $('#data_main_kategori_produk').val('').trigger("change");
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            // update_dt_horizontal()
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_balans').DataTable().ajax.reload();
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    // $('#dt_price_rendaan').DataTable().ajax.reload();
                }
            })
        })

        function update_laba_rugi(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_laba_rugi')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    tanggal:$('#edit_tanggal'+id).val(),
                    kategori_produk:$('#edit_data_main_kategori_produk'+id).val(),
                    biaya_penjualan: $('#edit_biaya_penjualan'+id).val(),
                    biaya_administrasi_umum: $('#edit_biaya_administrasi_umum'+id).val(),
                    biaya_bunga: $('#edit_biaya_bunga'+id).val(),
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
                                // update_dt_horizontal()
                                // $("#table_main").empty();
                                // get_data()
                                $('#dt_balans').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    // $('#dt_price_rendaan').DataTable().ajax.reload();
                }
            })
        }

        function delete_laba_rugi(id) {
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
                        url: '{{route('delete_laba_rugi')}}',
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
                                        update_dt_horizontal()
                                        // $("#table_main").empty();
                                        // get_data()
                                        $('#dt_balans').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_price_rendaan').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
@endsection
