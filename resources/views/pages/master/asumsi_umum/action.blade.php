
<button id="detail_view{{$model->id}}" type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a id="edit_view{{$model->id}}" class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_asumsi_umum({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Asumsi Umum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detail_data_asumsi{{$model->id}}">
                    <ul>
                        <li><a href="#detail_step-10">VERSION</a></li>
                        <li><a href="#detail_step-11">ASUMSI UMUM</a></li>
                    </ul>
                    <div>
                        <div id="detail_step-10" class="">
                            <form id="detail_form-1" novalidate>
                                <div class="form-group">
                                    <label for="nama_versi">Nama Versi <span class="text-red">*</span></label>
                                    <input disabled type="text" class="form-control" id="detail_nama_versi{{$model->id}}" placeholder="Ext : 202001" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_awal">Bulan Awal <span class="text-red">*</span></label>
                                    <input disabled type="text" class="form-control" id="detail_tanggal_awal{{$model->id}}" placeholder="Bulan-Tahun" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_awal">Bulan Akhir <span class="text-red">*</span></label>
                                    <input disabled type="text" class="form-control" id="detail_tanggal_akhir{{$model->id}}" placeholder="Bulan-Tahun" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="detail_step-11" class="">
                            <form class="asumsi_form" id="detail_form-2" novalidate>
                                <div class="row" id="detail_section_asumsi{{$model->id}}">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
{{--                <button type="button" id="submit" class="btn btn-primary">Simpan</button>--}}
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Edit-->
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Asumsi Umum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="edit_data_asumsi{{$model->id}}">
                    <ul>
                        <li><a href="#detail_step-10">VERSION</a></li>
                        <li><a href="#detail_step-11">ASUMSI UMUM</a></li>
                    </ul>
                    <div>
                        <div id="detail_step-10" class="">
                            <form id="{{$model->id}}edit_form-1" novalidate>
                                <div class="form-group">
                                    <label for="nama_versi">Nama Versi <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="edit_nama_versi{{$model->id}}" placeholder="Ext : 202001" required>
                                    <input type="text" id="id{{$model->id}}" style="display: none;">
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_bulan">Jumlah Bulan <span class="text-red">*</span></label>
                                    <input type="number" min="1" max="12" class="form-control" id="edit_jumlah_bulan{{$model->id}}" placeholder="Jumlah Bulan" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_awal">Tanggal Awal <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="edit_tanggal_awal{{$model->id}}" placeholder="Bulan-Tahun" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="detail_step-11" class="">
                            <form class="asumsi_form" id="{{$model->id}}edit_form-2" novalidate>
                                <div class="row" id="edit_section_asumsi{{$model->id}}">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit{{$model->id}}" onclick="update_asumsi_umum({{$model->id}})" class="btn btn-primary">Simpan</button>
                <button type="button" id="kembali{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                <button id="loader{{$model->id}}" type="button" class="btn btn-success btn-loaders btn-icon" style="display: none;">Harap Tunggu, Proses Sedang Berjalan !</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>


    $('#detail_view'+{{$model->id}}).on('click', function () {
        var html2='';
        $("#detail_section_asumsi"+{{$model->id}}).empty();
        $('#detail_data_asumsi'+{{$model->id}}).smartWizard({
            selected: 0,
            theme: 'dots',
            transitionEffect:'fade',
            showStepURLhash: false,
        })
        $.ajax({
            url: "{{ route('view_asumsi_umum') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                _token: "{{ csrf_token() }}",
                id:'{{$model->id}}'
            },
            success:function (response) {
                if (response.code === 200){
                    $('#detail_nama_versi'+{{$model->id}}).val(response.data['version'].version)
                    $('#detail_tanggal_awal'+{{$model->id}}).val(helpDateFormat(response.data['version'].awal_periode, 'eng'))
                    $('#detail_tanggal_akhir'+{{$model->id}}).val(helpDateFormat(response.data['version'].akhir_periode, 'eng'))

                    for (let i = 0 ; i < response.data['asumsi'].length ; i++){
                        html2 = '<div class="col-md-12">' +
                            '<strong>PERIODE :'+helpDateFormat(response.data['asumsi'][i]['month_year'], 'eng')+'</strong>' +
                            '</div>' +
                            '<div class="col-sm-6 col-md-6">' +
                            '<div class="form-group">' +
                            '<label class="form-label">Kurs  <span class="text-red">*</span></label>' +
                            '<input disabled class="form-control" type="text" name="detail_currency" id="detail_currency'+i+''+{{$model->id}}+'" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="1.000.000.00"></div>' +
                            '</div><div class="col-sm-6 col-md-6">' +
                            '<div class="form-group">' +
                            '<label class="form-label">Ajustment (%) <span class="text-red">*</span></label>' +
                            '<input disabled class="form-control" type="number" placeholder="0" required name="detail_adjustment" id="detail_adjustment'+i+''+{{$model->id}}+'" min="0" step="0.01" title="adjustment" pattern="^\d+(?:\.\d{1,2})?$"></div></div>';

                        $('#detail_section_asumsi'+{{$model->id }}).append(html2);

                        $('#detail_currency'+i+{{$model->id}}).on({
                            keyup: function() {
                                formatCurrency($(this));
                            },
                            blur: function() {
                                formatCurrency($(this), "blur");
                            },
                            show:function () {
                                formatCurrency($(this));
                            }
                        });

                        $('#detail_currency'+i+{{$model->id}}).val(response.data['asumsi'][i]['usd_rate']).trigger('keyup');
                        $('#detail_adjustment'+i+{{$model->id}}).val(response.data['asumsi'][i]['adjustment']).trigger('keyup');

                    }

                }else {
                }
            }
        })
    })

    $('#edit_view'+{{$model->id}}).on('click', function () {
        $("#edit_section_asumsi"+{{$model->id}}).empty();
        $('#edit_data_asumsi'+{{$model->id}}).smartWizard({
            selected: 0,
            theme: 'dots',
            transitionEffect:'fade',
            showStepURLhash: false,
        })
        $.ajax({
            url: "{{ route('view_asumsi_umum') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                _token: "{{ csrf_token() }}",
                id:'{{$model->id}}'
            },
            success:function (response) {
                if (response.code === 200){
                    $('#edit_nama_versi'+{{$model->id}}).val(response.data['version'].version)
                    $('#edit_jumlah_bulan'+{{$model->id}}).val(response.data['version'].data_bulan)
                    var date = new Date(response.data['version'].awal_periode)

                    $('#edit_tanggal_awal'+{{$model->id}}).bootstrapdatepicker({
                        format: "MM-yyyy",
                        viewMode: "months",
                        minViewMode: "months",
                        autoclose:true,
                    }).val(helpDateFormat(date, 'eng1'));

                }
            }
        })
    })

    $("#edit_data_asumsi"+{{$model->id}}).on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {
        // Validate only on forward movement

        if (nextStepIdx === 'forward') {
            let form = document.getElementById('{{$model->id}}edit_form-' + (currentStepIdx + 1));
            if (form) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    $("#edit_data_asumsi"+{{$model->id}}).smartWizard("setState", [currentStepIdx], 'error');
                    $("#edit_data_asumsi"+{{$model->id}}).smartWizard('fixHeight');
                    return false;
                }
                if (currentStepIdx === 0){

                    var data_id;
                    let month = $('#edit_jumlah_bulan'+{{$model->id}}).val();
                    var param;
                    for (let x = 0; x < month ; x++){
                        if (x === 0){
                            data_id = {{$model->id}};
                            param = parser_edit($('#edit_tanggal_awal'+{{$model->id}}).val())
                        }else {
                            param = moment(param, "MM/YYYY").add(1, 'months').format('MM/YYYY');
                        }
                        data_first_edit(joining(param), x, data_id);
                        // console.log(result)
                    }

                    // data_array.forEach(myFunction)
                    // console.log(data_array['html1'])

                    // $('#submit').prop('disabled', false);
                }
                $("#edit_data_asumsi"+{{$model->id}}).smartWizard("unsetState", [currentStepIdx], 'error');

            }

        }else {
            $("#edit_section_asumsi"+{{$model->id}}).empty();
        }
    });

    function joining(parameter) {
        var array = parameter.split('/')
        var result_time;

        if(array[0].length === 1){
            result_time = array[1]+'-'+'0'+array[0]+'-'+'01';
        }else {
            result_time = array[1]+'-'+array[0]+'-'+'01';
        }
        return result_time;
    }

    function toDate_edit(dateStr) {
        var parts = dateStr.split("-")
        return parts;
    }

    function parser_edit(parameter){

        var data = toDate_edit(parameter);
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

    function parser1(parameter) {
        var date = new Date(parameter);
        var month = date.getMonth()+1
        // var month = date.toLocaleDateString('default', { month: 'long' })
        var result = month +"/"+ date.getFullYear();
        return result;
    }

    function data_first_edit(d, id, data_id) {
        // var result;
        var html1;
        $.ajax({
            type: "POST",
            async:false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{route('view_edit_asumsi_umum')}}',
            data: {
                _token: "{{ csrf_token() }}",
                id:data_id,
                date:d
            },
            success:function (response) {
                if (response.code === 200){
                    html1 = '<div class="col-md-12">' +
                        '<strong>PERIODE :'+helpDateFormat(d, 'eng')+'</strong>' +
                        '</div>' +
                        '<div class="col-sm-6 col-md-6">' +
                        '<input id="edit_periode'+id+''+data_id+'" value="'+helpDateFormat(d)+'" style="display: none;">'+
                        '<div class="form-group">' +
                        '<label class="form-label">Kurs  <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="text" name="edit_currency" id="edit_currency'+id+''+data_id+'" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="1.000.000.00"></div>' +
                        '</div><div class="col-sm-6 col-md-6">' +
                        '<div class="form-group">' +
                        '<label class="form-label">Ajustment (%) <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="number" placeholder="0" required name="edit_adjustment" id="edit_adjustment'+id+''+data_id+'" min="0" step="0.01" title="adjustment" pattern="^\d+(?:\.\d{1,2})?$"></div></div>';
                }else {
                    html1 = '<div class="col-md-12">' +
                        '<strong>PERIODE :'+helpDateFormat(d, 'eng')+'</strong>' +
                        '</div>' +
                        '<div class="col-sm-6 col-md-6">' +
                        '<input id="edit_periode'+id+''+data_id+'" value="'+helpDateFormat(d)+'" style="display: none;">'+
                        '<div class="form-group">' +
                        '<label class="form-label">Kurs  <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="text" name="edit_currency" id="edit_currency'+id+''+data_id+'" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="1.000.000.00"></div>' +
                        '</div><div class="col-sm-6 col-md-6">' +
                        '<div class="form-group">' +
                        '<label class="form-label">Ajustment (%) <span class="text-red">*</span></label>' +
                        '<input class="form-control" type="number" placeholder="0" required name="edit_adjustment" id="edit_adjustment'+id+''+data_id+'" min="0" step="0.01" title="adjustment" pattern="^\d+(?:\.\d{1,2})?$"></div></div>';

                }

                $('#edit_section_asumsi'+data_id).append(html1);

                $('#edit_currency'+id+data_id).on({
                    keyup: function() {
                        formatCurrency($(this));
                    },
                    blur: function() {
                        formatCurrency($(this), "blur");
                    },
                    show:function () {
                        formatCurrency($(this));
                    }
                });

                $('#edit_currency'+id+data_id).val(response.data['usd_rate']).trigger('keyup');
                $('#edit_adjustment'+id+data_id).val(response.data['adjustment']).trigger('keyup');

            }
        })
    }

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


</script>
