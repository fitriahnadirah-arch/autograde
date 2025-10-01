<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Courses<?= $this->endSection() ?>
<?= $this->section('content') ?>

<h1 class="mb-4">Courses</h1>

<?php if (!$activeSession): ?>
    <div class="alert alert-warning">
        No active academic session set.
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title fw-bold">My Courses</h5>
        <?php if (!empty($admin_courses)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Semester</th>
                            <th>Credit Hour</th>
                            <th>Lecturers & Classes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admin_courses as $index => $course): ?>
                            <?php
                                // Filter lecturer & class untuk course ni
                                $assigned = array_filter($course_lecturer_class, function($c) use ($course) {
                                    return $c['course_id'] == $course['id'];
                                });
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($course['course_name']) ?></td>
                                <td><?= esc($course['course_code']) ?></td>
                                <td><?= esc($course['semester']) ?></td>
                                <td><?= esc($course['credit_hour']) ?></td>
                                <td>
                                    <?php if (!empty($assigned)): ?>
                                        <ul class="mb-0 list-unstyled small">
                                            <?php foreach ($assigned as $a): ?>
                                                <li>
                                                    <span class="badge bg-info text-dark me-1"><?= esc($a['class_name']) ?></span> 
                                                    <?= esc($a['lecturer_name']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span class="text-muted">Not Assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Button to trigger Modal -->
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignLecturerModal<?= $course['id'] ?>">
                                        <i class="bi bi-person-plus-fill"></i> Assign
                                    </button>

                                    <a href="<?= base_url('coordinator/detailCourse/' . $course['id']) ?>"
                                        class="btn btn-sm btn-success rounded-pill mt-1">
                                        <i class="bi bi-plus-circle"></i> Add Assessment
                                    </a>

                                    <!-- MODAL -->
                                    <div class="modal fade" id="assignLecturerModal<?= $course['id'] ?>"
                                        tabindex="-1" aria-labelledby="assignLecturerLabel<?= $course['id'] ?>"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="<?= base_url('coordinator/assignLecturer/' . $course['id']) ?>" method="post">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="assignLecturerLabel<?= $course['id'] ?>">
                                                            Assign Lecturers & Classes for: <strong><?= esc($course['course_code']) ?> - <?= esc($course['course_name']) ?></strong>
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-muted small">Add one or more Lecturers and assign a Class Name for each assignment.</p>
                                                        
                                                        <div id="assignment-container-<?= $course['id'] ?>" class="dynamic-assignment-container">
                                                            <div class="row g-2 mb-3 assignment-row border-bottom pb-2">
                                                                <div class="col-md-6">
                                                                    <label class="form-label small fw-medium">Lecturer</label>
                                                                    <select name="assignments[0][lecturer_id]" class="form-select lecturer-select" required>
                                                                        <option value="">Select Lecturer</option>
                                                                        <?php foreach ($lecturers as $lecturer): ?>
                                                                            <option value="<?= esc($lecturer['id']) ?>"><?= esc($lecturer['username']) ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label small fw-medium">Class Name</label>
                                                                    <input type="text" name="assignments[0][class_name]" class="form-control" placeholder="Enter Class Name" required>
                                                                </div>
                                                                <div class="col-12 mt-2 text-end d-none delete-btn-container">
                                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-assignment-row">Remove</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill mt-3" 
                                                            data-course-id="<?= $course['id'] ?>" 
                                                            onclick="addAssignmentRow(this)">
                                                            + Add More Lecturer/Class
                                                        </button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END MODAL -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No courses for this session.</p>
        <?php endif; ?>
    </div>
</div>

<script>
let assignmentIndex = 1;

function addAssignmentRow(button) {
    const courseId = button.getAttribute('data-course-id');
    const container = document.getElementById(`assignment-container-${courseId}`);
    const templateRow = container.querySelector('.assignment-row');
    if (!templateRow) return;

    const newRow = templateRow.cloneNode(true);
    newRow.classList.remove('d-none');

    newRow.querySelectorAll('select, input').forEach(input => {
        input.name = input.name.replace(/\[\d+\]/, `[${assignmentIndex}]`);
        input.value = '';
        input.setAttribute('required', 'required');
    });

    const removeBtnContainer = newRow.querySelector('.delete-btn-container');
    if (removeBtnContainer) removeBtnContainer.classList.remove('d-none');

    newRow.querySelector('.remove-assignment-row').addEventListener('click', function() {
        newRow.remove();
    });

    container.appendChild(newRow);
    assignmentIndex++;
}

document.querySelectorAll('.remove-assignment-row').forEach(button => {
    button.addEventListener('click', function() {
        this.closest('.assignment-row').remove();
    });
});
</script>

<?= $this->endSection() ?>
