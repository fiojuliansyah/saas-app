@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Team Panel</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Team Panel</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title m-0">Team Panel</h4>
                        </div>
                        <div class="table-responsive m-t-40">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

@endpush
