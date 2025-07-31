<button 
    class="btn btn-sm round btn-outline-warning" data-bs-toggle="modal"
        data-bs-target="#editTeamModal{{ $row->id }}"> Edit</button>
<button 
  class="btn btn-sm round btn-outline-danger" 
  data-bs-toggle="modal" 
  data-bs-target="#confirmDeleteModal{{ $row->id }}"> Delete
</button>


<div class="modal fade" id="editTeamModal{{ $row->id }}" tabindex="-1" aria-labelledby="editTeamModalLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('teams.update', $row->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content" style="text-align: left">
        <div class="modal-header">
          <h5 class="modal-title" id="editTeamModalLabel{{ $row->id }}">Edit Team</h5>
          <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Team Name</label>
            <input type="text" name="name" class="form-control" value="{{ $row->name }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Short Name</label>
            <input type="text" name="short_name" class="form-control" value="{{ $row->short_name }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="{{ $row->country }}">
          </div>
          <div class="mb-3">
              <label class="form-label">Upload Logo</label><br>
              @if ($row->logo)
                <img src="{{ asset('storage/' . $row->logo) }}" height="160" class="mb-2"><br>
              @endif
              <input type="file" name="logo" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-sm round btn-info">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="confirmDeleteModal{{ $row->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-start" style="text-align: left">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel{{ $row->id }}">Confirm Delete</h5>
        <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong>{{ $row->name }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <form method="POST" action="{{ route('teams.destroy', $row->id) }}" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
