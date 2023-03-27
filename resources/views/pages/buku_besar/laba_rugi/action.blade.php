@if (mapping_akses('laba_rugi','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('laba_rugi','update'))
    <a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('laba_rugi','delete'))
    <a class="btn bg-danger-transparent" onclick="delete_laba_rugi({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail"
     aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Laba Rugi</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label >Versi Asumsi <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{$model->version}}" id="detail_tanggal{{$model->id}}" placeholder="Masukkan Tahun" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label >Kategori Produk <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{$model->kategori_produk_name}} - {{$model->kategori_produk_desc}}" id="detail_data_main_kategori_produk{{$model->id}}" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label >Biaya Penjualan <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{rupiah($model->value_bp)}}" id="detail_biaya_penjualan{{$model->id}}" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label >Biaya Administrasi Umum <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{rupiah($model->value_bau)}}" id="detail_biaya_administrasi_umum{{$model->id}}" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label >Biaya Bunga <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{rupiah($model->value_bb)}}" id="detail_biaya_bunga{{$model->id}}" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Edit-->
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail"
     aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Laba Rugi</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label >Versi Asumsi <span class="text-red">*</span></label>
                                <select name="edit_data_version{{$model->id}}" id="edit_data_version{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->version_id}}" selected>{{$model->version}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori Produk</label>
                                <select name="edit_data_main_kategori_produk{{$model->id}}" id="edit_data_main_kategori_produk{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->kategori_produk_id}}" selected>{{$model->kategori_produk_name}} - {{$model->kategori_produk_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Biaya Penjualan <span class="text-red">*</span></label>
                                <input value="{{rupiah($model->value_bp)}}" class="form-control" type="text" placeholder="Masukkan Biaya Penjualan" required name="edit_biaya_penjualan{{$model->id}}" id="edit_biaya_penjualan{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Biaya Administrasi Umum <span class="text-red">*</span></label>
                                <input value="{{rupiah($model->value_bau)}}" class="form-control" type="text" placeholder="Masukkan Biaya Administrasi Umum" required name="edit_biaya_administrasi_umum{{$model->id}}" id="edit_biaya_administrasi_umum{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Biaya Bunga <span class="text-red">*</span></label>
                                <input value="{{rupiah($model->value_bb)}}" class="form-control" type="text" placeholder="Masukkan Biaya Bunga" required name="edit_biaya_bunga{{$model->id}}" id="edit_biaya_bunga{{$model->id}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_laba_rugi({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_data_version'+{{$model->id}}).select2({
        dropdownParent:$('#modal_edit'+{{$model->id}}),
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

    $('#edit_data_main_kategori_produk'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Kategori Produk',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('kategori_produk_select') }}",
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

    $('#edit_biaya_penjualan'+{{$model->id}}).on('keyup', function(){
        let rupiah = formatRupiah($(this).val(), "Rp ")
        $(this).val(rupiah)
    });

    $('#edit_biaya_administrasi_umum'+{{$model->id}}).on('keyup', function(){
        let rupiah = formatRupiah($(this).val(), "Rp ")
        $(this).val(rupiah)
    });

    $('#edit_biaya_bunga'+{{$model->id}}).on('keyup', function(){
        let rupiah = formatRupiah($(this).val(), "Rp ")
        $(this).val(rupiah)
    });
</script>
