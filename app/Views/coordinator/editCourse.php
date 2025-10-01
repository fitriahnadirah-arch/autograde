<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Edit Course<?= $this->endSection() ?>
<?= $this->section('content') ?>

<h1 class="mb-4">Edit Course</h1>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('coordinator/updateCourse/'.$course['id']) ?>" method="post">
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" name="course_name" id="course_name" class="form-control"
                       value="<?= esc($course['course_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" name="course_code" id="course_code" class="form-control"
                       value="<?= esc($course['course_code']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select name="semester" id="semester" class="form-select" required>
                    <?php for($i=1; $i<=8; $i++): ?>
                        <option value="<?= $i ?>" <?= ($course['semester'] == $i ? 'selected' : '') ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="credit_hour" class="form-label">Credit Hour</label>
                <select name="credit_hour" id="credit_hour" class="form-select" required>
                    <?php for($i=1; $i<=4; $i++): ?>
                        <option value="<?= $i ?>" <?= ($course['credit_hour'] == $i ? 'selected' : '') ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="lecturer_id" class="form-label">Lecturer</label>
                <select name="lecturer_id" id="lecturer_id" class="form-select" required>
                    <option value="">Select Lecturer</option>
                    <?php foreach($lecturers as $lecturer): ?>
                        <option value="<?= esc($lecturer['id']) ?>"
                            <?= ($course['lecturer_id'] == $lecturer['id'] ? 'selected' : '') ?>>
                            <?= esc($lecturer['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= base_url('coordinator/addCourses') ?>" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
