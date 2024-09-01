<div class="modal fade" id="guruModal" tabindex="-1" aria-labelledby="guruModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="guru_form" action="{{ route('guru.store') }}" method="POST">
            @csrf
            <input type="hidden" id="guru_id" name="guru_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guruModalLabel">Add Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- NIK -->
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                    <!-- User Selection -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Select User</option>
                            @foreach(App\Models\User::whereHas('roles', function($query) {
                                $query->where('name', 'guru');
                            })->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Kelas Selection -->
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id" required>
                            <option value="">Select Kelas</option>
                            @foreach(App\Models\Kelas::all() as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Additional Fields (if any) -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Guru</button>
                </div>
            </div>
        </form>
    </div>
</div>
