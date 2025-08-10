@extends('layouts.app')

@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Teams</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Teams</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title m-0">Teams</h4>
                            <div>
                                <button class="btn btn-sm round btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importTeamModal"><i class="fa fa-upload">&nbsp;&nbsp;</i>Import Team</button>
                                <button class="btn btn-sm round btn-info" data-bs-toggle="modal" data-bs-target="#createTeamModal"><i class="fa fa-plus">&nbsp;&nbsp;</i>Create Team</button>
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
<!-- Modal: Import Team -->
<div class="modal fade" id="importTeamModal" tabindex="-1" aria-labelledby="importTeamModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('teams.import.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="importTeamModalLabel">Import Team</h5>
          <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
                <label for="file" class="form-label">Select File</label>
                <input type="file" name="file" id="file" class="form-control mt-2">
          </div>
          <a href="/assets/team-import-template.xlsx">Download Example</a>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Create Team -->
<div class="modal fade" id="createTeamModal" tabindex="-1" aria-labelledby="createTeamModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createTeamModalLabel">Create Team</h5>
          <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Team Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Short Name</label>
            <input type="text" name="short_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload Logo</label>
            <img id="logoPreview" class="logo-preview mt-2" alt="Logo Preview">
            <div class="logo-upload-box" onclick="document.getElementById('logoInput').click()">
              <i class="fa fa-upload"></i><br>
              <span>Click to upload logo image</span>
              <input type="file" name="logo" id="logoInput" accept="image/*" required onchange="previewLogo(event)">
            </div>
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

    <script>
      function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');
        
        if (input.files && input.files[0]) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          }
          reader.readAsDataURL(input.files[0]);
        }
      }
    </script>

@endpush
