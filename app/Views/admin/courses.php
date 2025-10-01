<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Courses<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
/* Input & Select styling */
.form-control,
.form-select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px;
    transition: all 0.2s ease-in-out;
    background-color: #fff;
}
.form-control:hover,
.form-select:hover { background-color: #f1f3f5; }
.form-control:focus,
.form-select:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25); }

.card-title { font-weight: bold; }

.search-bar {
  display: flex; align-items: center;
  background: #f8f9fa; border: 1px solid #dee2e6;
  border-radius: 12px; padding: 6px 10px; max-width: 520px;
  margin-left: auto; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}
.search-bar:focus-within { border-color: #339af0; box-shadow: 0 0 0 3px rgba(51,154,240,0.25); }
.search-icon { font-size: 18px; color: #868e96; margin-right: 8px; }
.search-bar input { flex: 1; border: none; outline: none; font-size: 15px; background: transparent; padding: 6px; color: #212529; }
.search-btn { background: #339af0; color: #fff; border: none; border-radius: 8px; padding: 6px 16px; margin-left: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: 0.3s ease; }
.search-btn:hover { background: #1c7ed6; }
.reset-btn { background: #e9ecef; color: #495057; border-radius: 8px; padding: 6px 14px; margin-left: 6px; font-size: 14px; font-weight: 500; text-decoration: none; transition: 0.3s ease; }
.reset-btn:hover { background: #dee2e6; color: #212529; }
</style>

<h3 class="fw-bold mb-1">Set Courses</h3>

<!-- Add Course Form -->
<div class="card mb-4 shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
        <h4 class="card-title mb-4">Add New Course</h4>
        <form action="<?= base_url('admin/storeCourse') ?>" method="post" class="needs-validation" novalidate>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Course Name</label>
                <input type="text" name="course_name" class="form-control rounded-3" placeholder="Enter course name" required>
                <div class="invalid-feedback">Please enter the course name.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Course Code</label>
                <input type="text" name="course_code" class="form-control rounded-3" placeholder="Enter course code" required>
                <div class="invalid-feedback">Please enter the course code.</div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Credit Hour</label>
                    <select name="credit_hour" class="form-select rounded-3" required>
                        <option value="">-- Select Credit Hour --</option>
                        <?php for($i=1;$i<=4;$i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <div class="invalid-feedback">Please select a credit hour.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Semester</label>
                    <select name="semester" class="form-select rounded-3" required>
                        <option value="">-- Select Semester --</option>
                        <?php for($i=1;$i<=5;$i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <div class="invalid-feedback">Please select a semester.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Academic Session</label>
                    <select name="session_id" class="form-select rounded-3" required>
                        <option value="">-- Select Session --</option>
                        <?php if(!empty($sessions)): ?>
                            <?php foreach($sessions as $session): ?>
                                <?php if($session['status'] === 'active'): ?>
                                    <option value="<?= esc($session['id']) ?>"><?= esc($session['session_name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="invalid-feedback">Please select a session.</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Coordinator</label>
                <select name="coordinator_id" class="form-select rounded-3" required>
                    <option value="">-- Select Coordinator --</option>
                    <?php if(!empty($coordinators)): ?>
                        <?php foreach($coordinators as $coordinator): ?>
                            <option value="<?= esc($coordinator['id']) ?>"><?= esc($coordinator['username']) ?> (<?= esc($coordinator['email']) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="invalid-feedback">Please select a coordinator.</div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary fw-bold px-4 me-2">
                    <i class="bi bi-save me-1"></i> Save
                </button>
                <button type="reset" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Course Table -->
<div class="card shadow-sm rounded-4 border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Course Information</h4>
            <form action="<?= base_url('admin/courses/search') ?>" method="get" class="search-bar">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="query" placeholder="Search courses..." value="<?= esc($query ?? '') ?>">
                <button type="submit" class="search-btn">Search</button>
                <a href="<?= base_url('admin/courses') ?>" class="reset-btn">Reset</a>
            </form>
        </div>

        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Credit Hour</th>
                    <th>Semester</th>
                    <th>Session</th>
                    <th>Coordinator</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($courses)): ?>
                    <?php $i=1; foreach($courses as $course): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($course['course_code']) ?></td>
                            <td><?= esc($course['course_name']) ?></td>
                            <td><?= esc($course['credit_hour']) ?></td>
                            <td><?= esc($course['semester']) ?></td>
                            <td><?= esc($course['session_name'] ?? '-') ?></td>
                            <td><?= esc($course['coordinator_name'] ?? '-') ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateCourseModal"
                                        data-id="<?= $course['id'] ?>"
                                        data-code="<?= esc($course['course_code']) ?>"
                                        data-name="<?= esc($course['course_name']) ?>"
                                        data-hour="<?= esc($course['credit_hour']) ?>"
                                        data-semester="<?= esc($course['semester']) ?>"
                                        data-coordinator="<?= esc($course['coordinator_id'] ?? '') ?>"
                                        data-session="<?= esc($course['session_id'] ?? '') ?>">
                                    <i class="bi bi-pencil-square"></i> Update
                                </button>
                                <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCourseModal"
                                        data-id="<?= $course['id'] ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted fw-bold">No courses added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateCourseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <form method="post" action="<?= base_url('admin/updateCourse') ?>">
        <div class="modal-header">
          <h5 class="modal-title">Update Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="course_id">
          <div class="mb-3">
            <label class="form-label">Course Code</label>
            <input type="text" class="form-control rounded-3" name="course_code" id="course_code">
          </div>
          <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" class="form-control rounded-3" name="course_name" id="course_name">
          </div>
          <div class="mb-3">
            <label class="form-label">Credit Hour</label>
            <input type="number" class="form-control rounded-3" name="credit_hour" id="credit_hour">
          </div>
          <div class="mb-3">
            <label class="form-label">Semester</label>
            <input type="number" class="form-control rounded-3" name="semester" id="semester">
          </div>
          <div class="mb-3">
            <label class="form-label">Academic Session</label>
            <select name="session_id" id="update_session_id" class="form-select rounded-3" required>
                <option value="">-- Select Session --</option>
                <?php if(!empty($sessions)): ?>
                    <?php foreach($sessions as $session): ?>
                        <?php if($session['status'] === 'active'): ?>
                            <option value="<?= esc($session['id']) ?>"><?= esc($session['session_name']) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Coordinator</label>
            <select name="coordinator_id" id="coordinator_id" class="form-select rounded-3" required>
                <option value="">-- Select Coordinator --</option>
                <?php if(!empty($coordinators)): ?>
                    <?php foreach($coordinators as $coordinator): ?>
                        <option value="<?= esc($coordinator['id']) ?>"><?= esc($coordinator['username']) ?> (<?= esc($coordinator['email']) ?>)</option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <form method="post" action="<?= base_url('admin/deleteCourse') ?>">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this course?
          <input type="hidden" name="id" id="delete_course_id">
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
const updateModal = document.getElementById('updateCourseModal');
updateModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    document.getElementById('course_id').value = button.getAttribute('data-id');
    document.getElementById('course_code').value = button.getAttribute('data-code');
    document.getElementById('course_name').value = button.getAttribute('data-name');
    document.getElementById('credit_hour').value = button.getAttribute('data-hour');
    document.getElementById('semester').value = button.getAttribute('data-semester');
    document.getElementById('coordinator_id').value = button.getAttribute('data-coordinator');
    document.getElementById('update_session_id').value = button.getAttribute('data-session');
});

const deleteModal = document.getElementById('deleteCourseModal');
deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    document.getElementById('delete_course_id').value = button.getAttribute('data-id');
});

// Bootstrap Validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); }
        form.classList.add('was-validated');
      }, false);
    });
})();
</script>

<?= $this->endSection() ?>
