<button 
    class="btn btn-sm round btn-outline-warning" data-bs-toggle="modal"
        data-bs-target="#editTeamModal{{ $row->id }}"> Edit</button>
<button 
  class="btn btn-sm round btn-outline-danger" 
  data-bs-toggle="modal" 
  data-bs-target="#confirmDeleteModal{{ $row->id }}"> Delete
</button>

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
