@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Players</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Players</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title m-0">Players</h4>
                            <div>
                                <button class="btn btn-sm round btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importPlayerModal"><i class="fa fa-upload">&nbsp;&nbsp;</i>Import Player</button>
                                <button class="btn btn-sm round btn-info" data-bs-toggle="modal" data-bs-target="#createPlayerModal"><i class="fa fa-plus">&nbsp;&nbsp;</i>Create Player</button>
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
<!-- Modal: Import Player -->
<div class="modal fade" id="importPlayerModal" tabindex="-1" aria-labelledby="importPlayerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('players.import.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="importPlayerModalLabel">Import Player</h5>
          <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
                <label for="file" class="form-label">Select File</label>
                <input type="file" name="file" id="file" class="form-control mt-2">
          </div>
          <a href="/assets/player-import-template.xlsx">Download Example</a>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Create Player -->
<div class="modal fade" id="createPlayerModal" tabindex="-1" aria-labelledby="createPlayerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('players.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPlayerModalLabel">Create Player</h5>
          <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Team</label>
            <select name="team_id" class="form-control" required>
                <option value="">Pilih</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}">
                        {{ $team->name }}
                    </option>  
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Player Name</label>
            <input type="text" name="name" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Player Nickname</label>
            <input type="text" name="nickname" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Avatar</label>
            <input type="file" name="avatar" id="avatar" class="form-control mt-2" accept="image/*">
          </div>
          <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Squad Name</label>
            <input type="text" name="squad" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <input type="text" name="role" class="form-control">
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
