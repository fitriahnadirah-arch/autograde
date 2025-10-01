<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
/* --- Card Styling --- */
.card {
  border: none;
  border-radius: 1.5rem; /* lebih rounded */
  box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

.card-header {
  background: #f8f9fa;
  font-size: 1.6rem;
  font-weight: 700;
  color: #212529;
  border-bottom: 2px solid #e9ecef;
  text-align: left; /* kiri */
  padding: 0.75rem 1rem;
  border-top-left-radius: 1.5rem;
  border-top-right-radius: 1.5rem;
}

/* --- Table Styling --- */
.table thead th {
  background: #f1f3f5;
  color: #495057;
  font-size: 0.9rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .5px;
  border: none;
}

.table tbody tr {
  transition: background 0.2s ease;
}
.table tbody tr:hover {
  background: #f8f9fa;
}

/* --- Role Chip Improved Colors --- */
.role-chip {
  font-size: 0.8rem;
  padding: 0.35rem 0.9rem;
  border-radius: 25px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.role-admin {
  background: #0d6efd; /* biru terang */
  color: #fff;
}

.role-lecturer {
  background: #198754; /* hijau vivid */
  color: #fff;
}

.role-coordinator {
  background: #ffc107; /* oren keemasan */
  color: #212529; /* teks gelap */
}

/* --- Update Button --- */
.btn-update {
  background: #0d6efd;
  border: none;
  color: #fff;
  font-weight: 600;
  padding: 0.35rem 0.75rem; /* button kecil */
  font-size: 0.85rem;
  border-radius: 25px;
  transition: all 0.2s ease;
}
.btn-update:hover {
  background: #0b5ed7;
  transform: scale(1.05);
}

/* --- Modal Styling --- */
.modal-content {
  border-radius: 1.5rem;
}
</style>

<div class="container py-4">

    <!-- Flash messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="flash-message">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="flash-message">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- User Update Card -->
    <div class="card rounded-4">
        <div class="card-header">User Update</div>
        <div class="card-body">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Update Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= esc($user['id']) ?></td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td>
                            <?php foreach(explode(',', $user['role']) as $role): ?>
                                <?php $trimmed = trim($role); ?>
                                <span class="role-chip 
                                    <?= $trimmed == 'admin' ? 'role-admin' : '' ?>
                                    <?= $trimmed == 'lecturer' ? 'role-lecturer' : '' ?>
                                    <?= $trimmed == 'course_coordinator' ? 'role-coordinator' : '' ?>">
                                    <?php if($trimmed == 'admin'): ?>
                                        <i class="bi bi-shield-lock"></i>
                                    <?php elseif($trimmed == 'lecturer'): ?>
                                        <i class="bi bi-person-badge"></i>
                                    <?php elseif($trimmed == 'course_coordinator'): ?>
                                        <i class="bi bi-people"></i>
                                    <?php endif; ?>
                                    <?= ucfirst($trimmed) ?>
                                </span>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn-update btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#updateRoleModal<?= $user['id'] ?>">
                                <i class="bi bi-pencil-square"></i> Update
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals -->
<?php foreach($users as $user): ?>
<div class="modal fade" id="updateRoleModal<?= $user['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content shadow rounded-4">
            <form method="post" action="<?= base_url('admin/update-user-role') ?>">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Update Role - <?= esc($user['username']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="admin"
                            <?= strpos($user['role'], 'admin') !== false ? 'checked' : '' ?>>
                        <label class="form-check-label">Admin</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="lecturer"
                            <?= strpos($user['role'], 'lecturer') !== false ? 'checked' : '' ?>>
                        <label class="form-check-label">Lecturer</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="course_coordinator"
                            <?= strpos($user['role'], 'course_coordinator') !== false ? 'checked' : '' ?>>
                        <label class="form-check-label">Coordinator</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Auto-hide flash message -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(() => {
            let bsAlert = new bootstrap.Alert(flash);
            bsAlert.close();
        }, 3000);
    }
});
</script>

<?= $this->endSection() ?>
