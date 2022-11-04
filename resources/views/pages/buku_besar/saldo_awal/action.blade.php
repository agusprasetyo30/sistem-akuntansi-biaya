<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_saldo_awal({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Saldo Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Company Code </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Company Code" value="{{$model->company_code}}" name="detail_company_code"
                                    id="detail_company_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>GL Account </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="GL Account " value="{{$model->gl_account}}" name="detail_gl_account"
                                    id="detail_gl_account" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Valuation Class </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Valuation Class" value="{{$model->valuation_class}}" name="detail_valuation_class"
                                    id="detail_valuation_class" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Price Control </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Material" value="{{$model->price_control}}" name="detail_price_control"
                                    id="detail_price_control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Material </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->material_name}}" name="detail_material_name"
                                    id="detail_material_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Kode Plant </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Plant" value="{{$model->plant_code}}" name="detail_plant_name"
                                    id="detail_plant_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Stock </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Total Stock" value="{{$model->total_stock}}" name="detail_total_stock"
                                    id="detail_total_stock" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Value </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Total Value" value="{{$model->total_value}}" name="detail_total_value"
                                    id="detail_total_value" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Nilai Satuan </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->nilai_satuan}}" name="detail_nilai_satuan"
                                    id="detail_nilai_satuan" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Saldo Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Company Code </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Company Code"
                                    value="{{$model->company_code}}" name="edit_company_code"
                                    id="edit_company_code{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>GL Account </label>
                                <input type="text" class="form-control form-control-sm" placeholder="GL Account"
                                    value="{{$model->gl_account}}" name="edit_gl_account"
                                    id="edit_gl_account{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Valuation Class </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Valuation Class"
                                    value="{{$model->valuation_class}}" name="edit_valuation_class"
                                    id="edit_valuation_class{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Price Control </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Price Control"
                                    value="{{$model->price_control}}" name="edit_price_control"
                                    id="edit_price_control{{$model->id}}" autocomplete="off">
                            </div>
                            {{-- <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_id}}" selected>{{$model->material_name}}</option>
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_id}}" selected>{{$model->material_name}}</option>
                                </select>

                                {{-- <label class="form-label">Kategori Produk</label>
                                <select name="kategori_produk_id" id="kategori_produk_id" class="form-control custom-select select2">
                                    @foreach ($kategori as $row)
                                        <option value="{{ $row->id }}" {{ $res->kategori_produk_id == $row->id ? "selected" : "" }}>{{ $row->kategori_produk_name }}</option>							
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Plant</label>
                                <select name="main_plant" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->plant_id}}" selected>{{$model->plant_code}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Stock </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total Stock"
                                    value="{{$model->total_stock}}" name="edit_total_stock"
                                    id="edit_total_stock{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Value </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total value"
                                    value="{{$model->total_value}}" name="edit_total_value"
                                    id="edit_total_value{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Nilai Satuan </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nilai Satuan"
                                    value="{{$model->nilai_satuan}}" name="edit_nilai_satuan"
                                    id="edit_nilai_satuan{{$model->id}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_saldo_awal({{$model->id}})"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Saldo Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Kategori" name="kategori_material_name" id="kategori_material_name" autocomplete="off">
                            </div>
                            
                            <div class="form-group">
                                <label>Company Code </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Company Code" name="company_code"
                                    id="company_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>GL Account </label>
                                <input type="text" class="form-control form-control-sm" placeholder="GL Account" name="gl_account"
                                    id="gl_account" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Valuation Class </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Valuation Class" name="valuation_class"
                                    id="valuation_class" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Price Control </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Price Control" name="price_control"
                                    id="price_control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="data_main_material" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                </select>
                            </div>                          
                            <div class="form-group">
                                <label class="form-label">Kode Plant</label>
                                <select name="main_plant" id="data_main_plant" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Stock </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total Stock" name="total_stock"
                                    id="total_stock" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Value </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total value" name="total_value"
                                    id="total_value" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Nilai Satuan </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nilai Satuan" name="nilai_satuan"
                                    id="nilai_satuan" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
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

        $('#edit_data_main_plant'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
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

        $('#edit_data_main_material'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
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
    })

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
                    url: '{{route('insert_saldo_awal')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        company_code: $('#company_code').val(),
                        gl_account: $('#gl_account').val(),
                        valuation_class: $('#valuation_class').val(),
                        price_control: $('#price_control').val(),
                        material_id: $('#data_main_material').val(),
                        plant_id: $('#data_main_plant').val(),
                        total_stock: $('#total_stock').val(),
                        total_value: $('#total_value').val(),
                        nilai_satuan: $('#nilai_satuan').val(),
                    },
                    success:function (response) {
                        if (response.Code === 200){
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
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

            }

        })
    })

    function update_saldo_awal(id) {
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
                    url: '{{route('update_saldo_awal')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        company_code: $('#edit_company_code'+id).val(),
                        gl_account: $('#edit_gl_account'+id).val(),
                        valuation_class: $('#edit_valuation_class'+id).val(),
                        price_control: $('#edit_price_control'+id).val(),
                        material_id: $('#edit_data_main_material'+id).val(),
                        plant_id: $('#edit_data_main_plant'+id).val(),
                        total_stock: $('#edit_total_stock'+id).val(),
                        total_value: $('#edit_total_value'+id).val(),
                        nilai_satuan: $('#edit_nilai_satuan'+id).val(),
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

    function delete_saldo_awal(id) {
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
                    url: '{{route('delete_saldo_awal')}}',
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
