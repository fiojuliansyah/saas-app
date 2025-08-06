<button 
    class="btn btn-sm round btn-outline-warning" data-bs-toggle="modal"
        data-bs-target="#editMatchModal{{ $row->id }}"> Edit</button>
<button 
  class="btn btn-sm round btn-outline-danger" 
  data-bs-toggle="modal" 
  data-bs-target="#confirmDeleteModal{{ $row->id }}"> Delete
</button>


<div class="modal fade" id="editMatchModal{{ $row->id }}" tabindex="-1" aria-labelledby="editMatchModalLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('matches.update', $row->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
        <div class="modal-content text-start" style="text-align: left">
            <div class="modal-header">
            <h5 class="modal-title" id="createMatchModalLabel">Edit Match</h5>
            <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
                <i class="fa fa-times"></i>
            </button>
            </div>
            <div class="modal-body">
           <div class="mb-3">
                <label class="form-label">CHOOSE TEAM A</label>
                <select name="team_a_id" class="form-control">
                    <option value="">Choose Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(old('team_a_id', $row->team_a_id ?? null) == $team->id)>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">CHOOSE TEAM B</label>
                <select name="team_b_id" class="form-control">
                    <option value="">Choose Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(old('team_b_id', $row->team_b_id ?? null) == $team->id)>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">MATCH DATE</label>
                <input type="date" name="match_datetime" value="{{ $row->match_datetime }}" class="form-control" required>
            </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-sm round btn-info">Submit</button>
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
        Are you sure you want to delete <strong>{{ $row->teamA->name }} vs {{ $row->teamB->name }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <form method="POST" action="{{ route('matches.destroy', $row->id) }}" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
