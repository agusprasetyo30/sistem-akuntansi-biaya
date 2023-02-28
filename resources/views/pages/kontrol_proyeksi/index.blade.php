@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Kontrol Proyeksi</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button> --}}
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Kontrol Proyeksi</div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="form-group" id="cost_center_pick">
                            <label class="form-label">Versi <span class="text-red">*</span></label>
                            <select id="filter_version_generate" class="form-control custom-select select2">
                            </select>
                        </div>
                        <div class="btn-list">
                            <button type="button" class="btn btn-primary btn-pill" id="btn_generate"><i class="fa fa-search me-2 fs-14"></i> Generate</button>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class=" tab-menu-heading p-0 bg-light">
                            <div class="tabs-menu1 ">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li class="" id="tabs_vertical"> <a href="#generate" class="active" data-bs-toggle="tab">Generate</a> </li>
                                    <li id="tabs_horizontal"> <a href="#laporan" data-bs-toggle="tab">Laporan</a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="generate">
                                    <div class="mb-4">
                                        <div class="mb-4">

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="laporan">
                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label class="form-label">Versi <span class="text-red">*</span></label>
                                            <select id="filter_version_laporan" class="form-control custom-select select2">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">PRODUK</label>
                                            <select id="filter_material" class="form-control custom-select select2">
                                                <option value="all" selected>Semua</option>
                                            </select>
                                        </div>
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-primary btn-pill" id="btn_tampilkan"><i class="fa fa-search me-2 fs-14"></i> Tampilkan</button>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive" id="table_main">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>

    </script>
@endsection
