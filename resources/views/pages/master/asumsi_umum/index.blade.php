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
                @if (mapping_akses('asumsi_umum','create'))
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
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table_main">
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
        var data_array = [];
        var table_main_dt = '<table id="dt_version" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="version" class="text-center">VERSI</th>' +
            '<th data-type="text" data-name="saldo_awal" class="text-center">SALDO AWAL</th>' +
            '<th data-type="text" data-name="jumlah_bulan" class="text-center">JUMLAH BULAN</th>' +
            '<th data-type="text" data-name="awal_periode" class="text-center">AWAL PERIODE</th>' +
            '<th data-type="text" data-name="akhir_periode" class="text-center">AKHIR PERIODE</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'
        $(document).ready(function () {


            var funArr = [];
            let loop = 0;
            var answersList = [];

            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

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
                if(!isNaN(d)){

                    var date = new Date(d);
                    var month = date.getMonth()+1
                    // var month = date.toLocaleDateString('default', { month: 'long' })
                    var result = month +"/"+ date.getFullYear();
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
                        periode = moment(periode, "MM/YYYY").add(1, 'months').format('MM/YYYY');
                    }
                    var html = '<div class="col-md-12">' +
                        '<strong>PERIODE :'+periode+'</strong>' +
                        '<input readonly style="display:none;" type="text" value="'+periode+'" id="periode'+i+'">' +
                        '</div>' +
                        '<div class="col-sm-4 col-md-4">' +
                        '<div class="form-group">' +
                        '<label class="form-label">Kurs  <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="text" name="currency" id="currency'+i+'" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="1.000.000.00">' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-sm-4 col-md-4">' +
                        '<div class="form-group">' +
                        '<label class="form-label">Adjustment (%) <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="number" value="0" placeholder="0" required name="adjustment" id="adjustment'+i+'" min="0" step="0.01" title="adjustment" pattern="^\d+(?:\.\d{1,2})?$">' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-sm-4 col-md-4">' +
                        '<div class="form-group">' +
                        '<label class="form-label">Inflasi (%) <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="number" value="0" placeholder="0" required name="inflasi" id="inflasi'+i+'" min="0" step="0.01" title="inflasi" pattern="^\d+(?:\.\d{1,2})?$">' +
                        '</div>' +
                        '</div>';

                    $('#section_asumsi').append(html);

                    $('#currency'+i).on({
                        keyup: function() {
                            formatCurrency($(this));
                        },
                        blur: function() {
                            formatCurrency($(this), "blur");
                        },
                    });


                    $.ajax({
                        url: "{{ route('helper_kurs') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            _token: "{{ csrf_token() }}",
                            periode:periode
                        },
                        success:function (response) {
                            $('#currency'+i).val(response.data_kurs).trigger('keyup');
                        }
                    })
                }
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
                        for (let i = 0; i<loop; i++){
                            var kurs = $('#currency'+i).val();
                            var ajust = $('#adjustment'+i).val();
                            var inflasi = $('#inflasi'+i).val();
                            var periode = $('#periode'+i).val();

                            if (kurs !== '' && ajust !== '' && kurs !== 'Rp ,00' && inflasi !== ''){
                                answersList.push({
                                    kurs:kurs,
                                    adjustment: ajust,
                                    inflasi: inflasi,
                                    peride_month: periode,
                                });
                            }else {
                                answersList.push({
                                    kurs:false,
                                    adjustment: false,
                                    inflasi: false,
                                    peride_month: periode,
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
                                    versi: $('#nama_versi').val(),
                                    jumlah_bulan: $('#jumlah_bulan').val(),
                                    start_date: $('#tanggal_awal').val(),
                                    asumsi:answersList
                                },
                                success:function (response) {
                                    Swal.fire({
                                        title: response.title,
                                        text: response.msg,
                                        icon: response.type,
                                        allowOutsideClick: false,
                                        confirmButtonColor: '#019267',
                                        confirmButtonText: 'Konfirmasi',
                                    }).then((result)=>{
                                        if (result.value) {
                                            $('#modal_add').modal('hide');
                                            $("#modal_add input").val("")
                                            $('#data_main_plant').val('').trigger("change");
                                            $('#is_active').val('').trigger("change");
                                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                                            $("#section_asumsi").empty();
                                            // $("#table_main").empty();
                                            // get_data()
                                            $('#dt_version').DataTable().ajax.reload();
                                        }
                                    })
                                },
                                error:function (response) {
                                    handleError(response)
                                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                                    // $('#dt_version').DataTable().ajax.reload();
                                }
                            })
                        }else {
                            Swal.fire({
                                title: 'PERINGATAN',
                                text: "Terdapat Data Kurs, Adjustment, dan Inflasi yang kosong.\n Silakan Isi Data Tersebut",
                                icon: 'warning',
                                confirmButtonColor: '#019267',
                                cancelButtonColor: '#EF4B4B',
                                confirmButtonText: 'Konfirmasi',
                            }).then((result)=>{
                                if (result.value){
                                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                                }
                            })
                        }
                    }

                })
            })

        });

        function get_data(){
            $('#table_main').html(table_main_dt)

            $('#dt_version thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_version thead');

            // $('#dt_version').DataTable().clear().destroy();
            $("#dt_version").DataTable({
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
                                $(input).appendTo(cell.empty())
                                    .on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });

                            }
                        }else {
                            cell.empty()
                        }

                    });
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2,3,4]
                        }, title: '',
                        filename: 'Asumsi Umum'
                    }
                ],
                ajax: {
                    url : '{{route("asumsi_umum")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'c_version', name: 'version', orderable:true},
                    { data: 'c_saldo_awal', name: 'filter_c_saldo_awal', orderable:true},
                    { data: 'c_data_bulan', name: 'filter_bulan', orderable:true},
                    { data: 'c_awal_periode', name: 'filter_c_awal_periode', orderable:true},
                    { data: 'c_akhir_periode', name: 'filter_c_akhir_periode', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3,4,5]}
                ],

            })
        }

        function update_asumsi_umum(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            var answersList_edit = [];
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
                    var lenght = $('#edit_jumlah_bulan'+id).val()

                    if(answersList_edit.length !== 0){
                        answersList_edit = []
                    }

                    var value_edit = true;

                    for (let j = 0 ; j < lenght ; j++){
                        var kurs = $('#edit_currency'+j+id).val();
                        var adjustment = $('#edit_adjustment'+j+id).val();
                        var inflasi = $('#edit_inflasi'+j+id).val();
                        var periode = $('#edit_periode'+j+id).val();

                        if(kurs !== '' && adjustment !== '' && periode !== ''){
                            answersList_edit.push({
                                kurs:kurs,
                                adjustment:adjustment,
                                inflasi: inflasi,
                                periode:periode,
                            });
                        }else{
                            answersList_edit.push({
                                kurs:'',
                                adjustment:'',
                                periode:'',
                                inflasi: '',
                            });
                            value_edit = false
                        }
                    }

                    if (value_edit === true){
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{route('update_asumsi_umum')}}',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id,
                                nama_version: $('#edit_nama_versi'+id).val(),
                                jumlah_bulan: $('#edit_jumlah_bulan'+id).val(),
                                tanggal: $('#edit_tanggal_awal'+id).val(),
                                answer:answersList_edit
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
                                            // $("#table_main").empty();
                                            // get_data()
                                            $('#dt_version').DataTable().ajax.reload();
                                        }
                                    })
                            },
                            error: function (response) {
                                handleError(response)
                                $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit"+id).attr("disabled", false);
                                // $('#dt_version').DataTable().ajax.reload();
                            }

                        })
                    }else {
                        Swal.fire({
                            title: 'PERINGATAN',
                            text: "Terdapat Data Kurs, Adjustment, dan Inflasi \n Silakan Isi Data Tersebut",
                            icon: 'warning',
                            confirmButtonColor: '#019267',
                            cancelButtonColor: '#EF4B4B',
                            confirmButtonText: 'Konfirmasi',
                        }).then((result)=>{
                            if (result.value){
                                $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit"+id).attr("disabled", false);
                            }
                        })
                    }
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
                                        $('#dt_version').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_version').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }

    </script>
@endsection
