<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h2>Code Evaluation</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Course</th>
            <th>Assessment</th>
            <th>Student(s)</th>
            <th>File</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($submissions as $submission): ?>
            <tr>
                <td><?= esc($submission['course_code']) ?> - <?= esc($submission['course_name']) ?></td>
                <td><?= esc($submission['assessment_title']) ?></td>
                <td><?= esc($submission['group_members']) ?></td>
                <td><?= esc($submission['filename']) ?></td>
                <td><?= esc($submission['uploaded_at']) ?></td>
                <td>
                    <a href="<?= site_url('lecturer/evaluateSubmission/'.$submission['id']) ?>" class="btn btn-sm btn-info">Evaluate</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
