<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Course Details<?= $this->endSection() ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="d-flex">
    <div class="flex-grow-1 p-4">

        <!-- Course Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?= esc($course['course_name']) ?></h5>
                <p class="card-text">Code: <?= esc($course['course_code']) ?></p>
            </div>
        </div>

        <!-- Header + Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Assessments</h4>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addAssessmentModal">
                <i class="bi bi-plus"></i> Add Assessment
            </button>
        </div>

        <!-- List existing assessments -->
        <?php if (!empty($assessments)): ?>
            <ul class="list-group">
                <?php foreach ($assessments as $a): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= esc($a['title']) ?></strong> 
                            (<?= esc($a['weight']) ?>%)
                        </div>
                        <div>
                            <!-- Edit button -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAssessmentModal<?= $a['id'] ?>">Edit</button>
                            <!-- Add Rubric button -->
                            <a href="<?= base_url('coordinator/addRubric/'.$a['id']) ?>" class="btn btn-sm btn-primary">Add Rubric</a>
                            <!-- Delete button -->
                            <form action="<?= base_url('coordinator/deleteAssessment/'.$a['id']) ?>" method="post" class="d-inline">
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </li>

                    <!-- Edit Assessment Modal -->
                    <div class="modal fade" id="editAssessmentModal<?= $a['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="<?= base_url('coordinator/updateAssessment/'.$a['id']) ?>" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Assessment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Assessment Name</label>
                                            <input type="text" name="title" class="form-control" value="<?= esc($a['title']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Percentage (%)</label>
                                            <input type="number" name="weight" class="form-control" value="<?= esc($a['weight']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No assessments added yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Add Assessment Modal -->
<div class="modal fade" id="addAssessmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('coordinator/addAssessment/'.$course['id']) ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Assessment Name</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter assessment name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Percentage (%)</label>
                        <input type="number" name="weight" class="form-control" placeholder="e.g. 20" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
