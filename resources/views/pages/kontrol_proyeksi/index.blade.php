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
                    <div class="">
                        <div class="table-responsive" id="table_main">
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
