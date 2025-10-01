<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Upload File<?= $this->endSection() ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
.upload-container {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: auto;
}
.upload-container h3 { font-weight: bold; color: #0d2c54; margin-bottom: 20px; text-align: center; }
.form-label { font-weight: bold; color: #333; }
.form-select, .form-control { border-radius: 8px; border: 1px solid #ddd; }
.btn-upload { background: #0d2c54; color: #fff; font-weight: bold; padding: 10px 18px; border-radius: 8px; }
.btn-upload:hover { background: #133d7a; color: #fff; }
.alert { border-radius: 8px; }
</style>

<div class="container mt-5">
    <div class="upload-container">
        <h3>Upload Student File</h3>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('lecturer/saveFile') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Course (preselected and readonly) -->
            <div class="mb-3">
                <label class="form-label">Course</label>
                <input type="text" class="form-control" 
                       value="<?= esc($course['course_code']) ?> - <?= esc($course['course_name']) ?>" 
                       readonly>
                <input type="hidden" name="course_id" value="<?= esc($course['course_assignment_id']) ?>">
            </div>

            <!-- Assessment -->
            <div class="mb-3">
                <label for="assessment_id" class="form-label">Select Assessment</label>
                <select name="assessment_id" id="assessment_id" class="form-select" required>
                    <option value="">-- Select Assessment --</option>
                    <?php foreach ($assessments as $assessment): ?>
                        <option value="<?= esc($assessment['id']) ?>"><?= esc($assessment['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Students -->
            <div class="mb-3">
                <label for="student_id" class="form-label">Select Students (max 5)</label>
                <select name="student_id[]" id="student_id" class="form-select" multiple style="width:100%;" required></select>
            </div>

            <!-- File -->
            <div class="mb-3">
                <label for="filename" class="form-label">Upload File</label>
                <input type="file" name="filename" id="filename" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-upload w-100">Upload</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#student_id').select2({ placeholder: "Search and select students", allowClear: true });

    $('#student_id').on('select2:select', function() {
        let selected = $(this).val();
        if(selected.length > 5){
            alert("You can only select up to 5 students.");
            selected.pop();
            $(this).val(selected).trigger("change");
        }
    });

    // Auto-load students for this course
    let courseId = "<?= esc($course['course_assignment_id']) ?>";
    if(courseId){
        $.ajax({
            url: "<?= base_url('lecturer/getStudentsByCourse') ?>/" + courseId,
            type: "GET",
            dataType: "json",
            success: function(response){
                let $studentSelect = $('#student_id');
                $studentSelect.empty();

                if(response.length > 0){
                    response.forEach(function(student){
                        $studentSelect.append(new Option(student.student_id + " - " + student.student_name, student.id));
                    });
                } else {
                    $studentSelect.append(new Option("No students found", "", true, true));
                }

                $studentSelect.trigger('change');
            },
            error: function(){ alert("Failed to load students."); }
        });
    }
});
</script>

<?= $this->endSection() ?>
