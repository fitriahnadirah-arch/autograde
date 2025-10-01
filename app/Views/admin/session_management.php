<?= $this->extend('layout/main') ?> 
<?= $this->section('content') ?>

<style>
/* --- Custom switch --- */
.switch {
  position: relative;
  display: inline-block;
  width: 55px;
  height: 28px;
}
.switch input {display:none;}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #dc3545;
  transition: .4s;
  border-radius: 34px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 20px; width: 20px;
  left: 4px; bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #198754;
}
input:checked + .slider:before {
  transform: translateX(26px);
}

/* --- Card effect belakang --- */
.card {
  background: #ffffff !important;
  backdrop-filter: blur(12px) !important;
  -webkit-backdrop-filter: blur(12px) !important;
  border-radius: 1rem !important;
  border: 1px solid rgba(255,255,255,0.3) !important;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
  transition: transform 0.2s ease;
}
.card:hover {
  transform: translateY(-3px);
}

.add-session-card {
  padding: 1rem 1.5rem !important;   
  min-height: 120px;                
}
</style>

<div class="container py-4">

    <!-- Add new session -->
    <div class="card border-0 rounded-4 mb-4 add-session-card">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Add New Session</h5>

            <!-- Flash Messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('admin/save-session') ?>" class="row g-2 needs-validation" novalidate>
                <div class="col-md-9">
                    <input type="text" name="session_name" 
                           class="form-control rounded-pill" 
                           placeholder="e.g. SESI I 2024/2025" required>
                    <div class="invalid-feedback">Please enter the session name.</div>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-success rounded-pill">
                        <i class="bi bi-save"></i> Save Session
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Academic Session List</h5>
            <table class="table align-middle table-hover">
                <thead class="table-primary">
                    <tr>
                        <th style="width:5%">No.</th>
                        <th>Session</th>
                        <th style="width:25%">Status</th>
                        <th style="width:15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($sessions as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-semibold"><?= esc($s['session_name']) ?></td>
                        <td>
                            <form method="post" action="<?= base_url('admin/toggle-status/'.$s['id']) ?>" class="d-flex align-items-center gap-2">
                                <label class="switch">
                                    <input type="checkbox" onchange="this.form.submit()" <?= $s['status']=='active'?'checked':'' ?>>
                                    <span class="slider"></span>
                                </label>
                                <span class="fw-semibold <?= $s['status']=='active'?'text-success':'text-danger' ?>">
                                    <?= $s['status']=='active'?'Active':'Not Active' ?>
                                </span>
                            </form>
                        </td>
                        <td>
                            <!-- Delete button trigger modal -->
                            <button type="button" 
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteSessionModal"
                                    data-id="<?= $s['id'] ?>">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($sessions)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="bi bi-info-circle"></i> No sessions found
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteSessionModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?= base_url('admin/delete-session') ?>">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this academic session?
          <input type="hidden" name="id" id="delete_session_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Pass ID to delete modal
  const deleteModal = document.getElementById('deleteSessionModal');
  deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    document.getElementById('delete_session_id').value = button.getAttribute('data-id');
  });

  // Bootstrap validation
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>

<?= $this->endSection() ?>
