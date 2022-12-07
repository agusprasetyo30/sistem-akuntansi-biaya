@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Asumsi Umum</h4>
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
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table-wrapper">
                            <table id="dt_version" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                    <th data-type='text' data-name='version' class="border-bottom-0 text-center">VERSION</th>
                                    <th data-type='text' data-name='range' class="border-bottom-0 text-center">RANGE BULAN</th>
                                    <th data-type='text' data-name='action' class="border-bottom-0 text-center">ACTION</th>
                                </tr>
                                <tr>
                                    <th data-type='text' data-name='nomor' ></th>
                                    <th data-type='text' data-name='version' ></th>
                                    <th data-type='text' data-name='range' ></th>
                                    <th data-type='text' data-name='action'></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.asumsi_umum.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            var funArr = [];
            let loop = 0;
            var answersList = [];

            get_data()

            $('#data_asumsi').smartWizard({
                selected: 0,
                theme: 'dots',
                transitionEffect:'fade',
                showStepURLhash: false,
            })

            $('#tanggal_awal').bootstrapdatepicker({
                format: "MM-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose:true
            });

            $("#data_asumsi").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {
                // Validate only on forward movement

                if (nextStepIdx === 'forward') {
                    let form = document.getElementById('form-' + (currentStepIdx + 1));
                    if (form) {
                        if (!form.checkValidity()) {
                            form.classList.add('was-validated');
                            $('#data_asumsi').smartWizard("setState", [currentStepIdx], 'error');
                            $("#data_asumsi").smartWizard('fixHeight');
                            return false;
                        }
                        if (currentStepIdx === 0){
                            data_first();
                            $('#submit').prop('disabled', false);
                        }
                        $('#data_asumsi').smartWizard("unsetState", [currentStepIdx], 'error');

                    }

                }else {
                    $("#section_asumsi").empty();
                }
            });

            $('#data_main_version').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('periode_select') }}",
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

            $('#currency').on({
                keyup: function() {
                    formatCurrency($(this));
                },
                blur: function() {
                    formatCurrency($(this), "blur");
                }
            });

            function formatNumber(n) {
                // format number 1000000 to 1,234,567
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }

            function formatCurrency(input, blur) {
                // appends $ to value, validates decimal side
                // and puts cursor back in right position.

                // get input value
                var input_val = input.val();

                // don't validate empty input
                if (input_val === "") { return; }

                // original length
                var original_len = input_val.length;

                // initial caret position
                var caret_pos = input.prop("selectionStart");

                // check for decimal
                if (input_val.indexOf(",") >= 0) {

                    // get position of first decimal
                    // this prevents multiple decimals from
                    // being entered
                    var decimal_pos = input_val.indexOf(",");

                    // split number by decimal point
                    var left_side = input_val.substring(0, decimal_pos);
                    var right_side = input_val.substring(decimal_pos);

                    // add commas to left side of number
                    left_side = formatNumber(left_side);

                    // validate right side
                    right_side = formatNumber(right_side);

                    // On blur make sure 2 numbers after decimal
                    if (blur === "blur") {
                        right_side += "00";
                    }

                    // Limit decimal to only 2 digits
                    right_side = right_side.substring(0, 2);

                    // join number by .
                    input_val = "Rp " + left_side + "," + right_side;

                } else {
                    // no decimal entered
                    // add commas to number
                    // remove all non-digits
                    input_val = formatNumber(input_val);
                    input_val = "Rp " + input_val;

                    // final formatting
                    if (blur === "blur") {
                        input_val += ",00";
                    }
                }

                // send updated string to input
                input.val(input_val);

                // put caret back in the right position
                var updated_len = input_val.length;
                caret_pos = updated_len - original_len + caret_pos;
                input[0].setSelectionRange(caret_pos, caret_pos);
            }

            function toDate(dateStr) {
                var parts = dateStr.split("-")
                return parts;
            }

            function parser(parameter){

                var data = toDate(parameter);
                var d = Date.parse(data[0] + data[1]);
                console.log(d);
                if(!isNaN(d)){
                    var date = new Date(d);
                    var month = date.getMonth()+1
                    var result = date.getDate() +"/"+ month +"/"+ date.getFullYear();
                    return result;
                }
                return -1;
            }

            function data_first() {
                var periode;
                var nama = $('#nama_versi').val();
                let jumlah = $('#jumlah_bulan').val();
                var tanggal = $('#tanggal_awal').val();

                var data = parser(tanggal);

                loop = jumlah;

                for (let i = 0; i<jumlah; i++){


                    if (i===0){
                        periode = data;
                    }else {
                        periode = moment(periode, "DD/MM/YYYY").add(1, 'months').format('DD/MM/YYYY');
                    }
                    var html = '<div class="col-md-12"><strong>PERIODE :'+periode+'</strong><input readonly style="display:none;" type="text" value="'+periode+'" id="periode'+i+'"></div><div class="col-sm-6 col-md-6"><div class="form-group"><label class="form-label">Kurs  <span class="text-red">*</span></label><select class="form-control" name="currency" id="currency'+i+'"></select></div></div><div class="col-sm-6 col-md-6"><div class="form-group"><label class="form-label">Ajustment <span class="text-red">*</span></label><input type="text" class="form-control" name="ajustment" id="ajustment'+i+'" placeholder="Ajustment"></div></div>';

                    $('#section_asumsi').append(html);

                    $('#currency'+i).select2({
                        dropdownParent: $('#modal_add'),
                        placeholder: 'Pilih Krus',
                        width: '100%',
                        tags:true,
                        allowClear: false,
                        ajax: {
                            url: "{{ route('kurs_select') }}",
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

                }

            }

            function get_data(){
                $('#dt_version').DataTable().clear().destroy();
                $("#dt_version").DataTable({
                    scrollX: true,
                    dom: 'Bfrtip',
                    // searching: false,
                    sortable: false,
                    processing: true,
                    serverSide: true,
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

                        this.api().columns().every(function (index) {
                            var column = this;
                            var data_type = this.header().getAttribute('data-type');
                            var iName = this.header().getAttribute('data-name');
                            var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                            if (isSearchable){
                                if (data_type == 'text'){
                                    var input = document.createElement("input");
                                    input.className = "form-control";
                                    input.styleName = "width: 100%;";
                                    $(input).
                                    appendTo($(column.header()).empty()).
                                    on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                                }else if (data_type == 'select'){
                                    var input = document.createElement("select");
                                    input.className = "form-control custom-select select2";
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
                        url : '{{route("asumsi_umum")}}',
                        data: {data:'index'}
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                        { data: 'version', name: 'version', orderable:false},
                        { data: 'data_bulan', name: 'data_bulan', orderable:false},
                        { data: 'action', name: 'action', orderable:false, searchable: false},

                    ],
                    columnDefs:[
                        {className: 'text-center', targets: [0,1,2,3]}
                    ],success:function (){

                    }

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
                        if(answersList.length !== 0){
                            answersList = []
                        }


                        var value = true;

                        console.log(typeof(loop))
                        for (let i = 0; i<loop; i++){
                            console.log(i);
                            var kurs = $('#currency'+i).val();
                            var ajust = $('#ajustment'+i).val();

                            if (kurs !== undefined && ajust !== undefined){
                                answersList.push({
                                    kurs:kurs,
                                    ajustment: ajust,
                                });
                            }else {
                                answersList.push({
                                    kurs:false,
                                    ajustment: false,
                                });
                                value = false
                            }
                        }

                        if (value === true){
                            $.ajax({
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '{{route('insert_asumsi_umum')}}',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id_periode: $('#data_main_periode').val(),
                                    kurs: $('#kurs').val(),
                                    handling_bb: $('#handling_bb').val(),
                                    // data_saldo_awal: $('#data_saldo_awal').val(),
                                },
                                success:function (response) {
                                    if (response.Code === 200){
                                        $('#modal_add').modal('hide');
                                        $("#modal_add input").val("")
                                        $('#data_main_plant').val('').trigger("change");
                                        $('#is_active').val('').trigger("change");
                                        toastr.success('Data Berhasil Disimpan', 'Success')
                                        get_data()
                                    }else if (response.Code === 0){
                                        $('#modal_add').modal('hide');
                                        $("#modal_add input").val("")
                                        toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                                    }else {
                                        $('#modal_add').modal('hide');
                                        $("#modal_add input").val("")
                                        toastr.error('Terdapat Kesalahan System', 'System Error')
                                    }


                                }
                            })
                        }else {
                            toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                        }

                        console.log(answersList);
                    }

                })
            })

        })



        function update_asumsi_umum(id) {
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
                        url: '{{route('update_asumsi_umum')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            id_periode: $('#edit_data_main_periode'+id).val(),
                            kurs: $('#edit_kurs'+id).val(),
                            handling_bb: $('#edit_handling_bb'+id).val(),
                            data_saldo_awal: $('#edit_data_saldo_awal'+id).val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_edit'+id).modal('hide');
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                $('#modal_edit'+id).modal('hide');
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                $('#modal_edit'+id).modal('hide');
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }
                        }
                    })

                }

            })
        }

        function delete_asumsi_umum(id) {
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
                        url: '{{route('delete_asumsi_umum')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
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
    {{--    <script src="{{asset('assets/js/pages/regions.js')}}"></script>--}}
@endsection
