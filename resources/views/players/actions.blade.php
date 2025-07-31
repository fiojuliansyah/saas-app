<button 
    class="btn btn-sm round btn-outline-warning" data-bs-toggle="modal"
        data-bs-target="#editPlayerModal{{ $row->id }}"> Edit</button>
<button 
  class="btn btn-sm round btn-outline-danger" 
  data-bs-toggle="modal" 
  data-bs-target="#deletePlayerModal{{ $row->id }}"> Delete
</button>


<!-- Edit Modal -->
<div class="modal fade" id="editPlayerModal{{ $row->id }}" tabindex="-1" aria-labelledby="editPlayerLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('players.update', $row->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content text-start" style="text-align: left">
        <div class="modal-header">
          <h5 class="modal-title">Edit Player</h5>
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
                    <option value="{{ $team->id }}" {{ $team->id == $row->team_id ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>  
                @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $row->name }}" required>
          </div>
          <div class="mb-2">
            <label>Nickname</label>
            <input type="text" name="nickname" class="form-control" value="{{ $row->nickname }}" required>
          </div>
          <div class="mb-2">
            <label>Country</label>
            <input type="text" name="country" class="form-control" value="{{ $row->country }}">
          </div>
          <div class="mb-2">
            <label>Squad Name</label>
            <input type="text" name="squad" class="form-control" value="{{ $row->squad }}">
          </div>
          <div class="mb-2">
            <label>Role</label>
            <input type="text" name="role" class="form-control" value="{{ $row->role }}">
          </div>
          <div class="mb-2">
            <label>Avatar</label>
            @if($row->avatar)
              <img src="{{ asset('storage/' . $row->avatar) }}" height="60" class="mb-2 d-block">
            @endif
            <input type="file" name="avatar" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info btn-sm">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deletePlayerModal{{ $row->id }}" tabindex="-1" aria-labelledby="deletePlayerLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-start" style="text-align: left">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn text-white fs-5" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
              <i class="fa fa-times"></i>
          </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong>{{ $row->name }}</strong>?
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('players.destroy', $row->id) }}">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>