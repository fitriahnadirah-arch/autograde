<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>My Courses<?= $this->endSection() ?>
<?= $this->section('content') ?>

<h1 class="mb-4">My Courses</h1>

<!-- Flash message -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($myCourses)): ?>
    <div class="row">
        <?php foreach ($myCourses as $course): ?>
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($course['course_name']) ?></h5>
                        <p class="card-text">
                            <strong>Code:</strong> <?= esc($course['course_code']) ?><br>
                            <strong>Semester:</strong> <?= esc($course['semester']) ?><br>
                            <strong>Credit Hours:</strong> <?= esc($course['credit_hour']) ?>
                        </p>

                        <!-- Class Dropdown -->
                        <div class="mb-2">
                            <label for="class_id_<?= $course['course_assignment_id'] ?>" class="form-label">
                                Select Class
                            </label>
                            <select name="class_id"
                                    id="class_id_<?= $course['course_assignment_id'] ?>"
                                    class="form-select form-select-sm"
                                    onchange="toggleUploadSection(<?= $course['course_assignment_id'] ?>)">
                                <option value="">-- Choose Class --</option>
                                <?php if (!empty($course['classes'])): ?>
                                    <?php foreach ($course['classes'] as $class): ?>
                                        <option value="<?= esc($class['id']) ?>">
                                            <?= esc($class['class_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No classes available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Upload Section (hidden until class is chosen) -->
                        <div id="upload_section_<?= $course['course_assignment_id'] ?>" style="display:none;">
                            <!-- Upload Students Form -->
                            <form action="<?= base_url('lecturer/upload-students/'.$course['course_assignment_id']) ?>"
                                  method="post" enctype="multipart/form-data">

                                <!-- Hidden class id (filled by JS) -->
                                <input type="hidden" name="class_id" id="hidden_class_id_<?= $course['course_assignment_id'] ?>">

                                <!-- File Upload -->
                                <div class="mb-2">
                                    <input type="file" name="student_excel"
                                           accept=".xls,.xlsx,.csv"
                                           class="form-control form-control-sm" required>
                                </div>

                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    Upload Students
                                </button>
                            </form>

                            <!-- Upload Assignment File Button -->
                            <div class="mt-2">
                                <a href="<?= base_url('lecturer/uploadFile/'.$course['course_assignment_id']) ?>"
                                   class="btn btn-primary btn-sm w-100">
                                   Upload Assignment File
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No courses assigned yet.</p>
<?php endif; ?>

<!-- Small JS to toggle upload section -->
<script>
function toggleUploadSection(courseId) {
    let dropdown = document.getElementById("class_id_" + courseId);
    let uploadSection = document.getElementById("upload_section_" + courseId);
    let hiddenInput = document.getElementById("hidden_class_id_" + courseId);

    if (dropdown.value) {
        uploadSection.style.display = "block";
        hiddenInput.value = dropdown.value;
    } else {
        uploadSection.style.display = "none";
        hiddenInput.value = "";
    }
}
</script>

<?= $this->endSection() ?>
