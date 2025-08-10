@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Matches</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Matches</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title m-0">Matches</h4>
                            <div>
                                <button class="btn btn-sm round btn-info" data-bs-toggle="modal" data-bs-target="#createMatchModal"><i class="fa fa-plus">&nbsp;&nbsp;</i>Create Match</button>
                            </div>
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

<!-- Modal: Create Match -->
<div class="modal fade" id="createMatchModal" tabindex="-1" aria-labelledby="createMatchModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('matches.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createMatchModalLabel">Create Match</h5>
          <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">CHOOSE TEAM A</label>
            <select name="team_a_id" class="form-control" id="">
                <option value="">Choose Team</option>
                @foreach ($teams as $team)   
                  <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">CHOOSE TEAM B</label>
            <select name="team_b_id" class="form-control" id="">
                <option value="">Choose Team</option>
                @foreach ($teams as $team)   
                  <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">MATCH DATE</label>
            <input type="date" name="match_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">MATCH DATE</label>
            <input type="time" name="match_time" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-sm round btn-info">Create</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
