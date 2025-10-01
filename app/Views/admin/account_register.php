<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container p-4">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <!-- Title -->
            <h1 class="mb-4 fw-bold text-center text-dark">
                Account Register
            </h1>

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

            <!-- Register Form -->
            <form method="post" action="<?= base_url('admin/process-account-register') ?>" novalidate>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control rounded-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <div class="p-2 border rounded-3 bg-light">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="admin">
                            <label class="form-check-label">Admin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="lecturer">
                            <label class="form-check-label">Lecturer</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="course_coordinator">
                            <label class="form-check-label">Course Coordinator</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control rounded-3" required minlength="8">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" class="form-control rounded-3" required minlength="8">
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2">
                    <i class="bi bi-save me-2"></i> Save
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background: #4f46e5;
        border: none;
    }
    .btn-primary:hover {
        background: #3730a3;
    }
</style>

<?= $this->endSection() ?>
