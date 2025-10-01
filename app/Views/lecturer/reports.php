<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3>Evaluation Reports</h3>

<div class="mb-3">
    <a href="<?= base_url('lecturer/exportExcel') ?>" class="btn btn-success">
        <i class="fa-solid fa-file-excel"></i> Export to Excel
    </a>
    <a href="<?= base_url('lecturer/exportPDF') ?>" class="btn btn-danger" target="_blank">
        <i class="fa-solid fa-file-pdf"></i> Export to PDF
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Student(s)</th>
            <th>Course</th>
            <th>Assessment</th>
            <th>Score</th>
            <th>Feedback</th>
            <th>Graded At</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($grades)): ?>
            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= esc($grade['group_members']) ?></td>
                    <td><?= esc($grade['course_name']) ?> (<?= esc($grade['course_code']) ?>)</td>
                    <td><?= esc($grade['assessment_title']) ?></td>
                    <td><?= esc($grade['score']) ?>%</td>
                    <td>
                        <?php
                            $fb = json_decode($grade['feedback'], true);
                            if (is_array($fb)) {
                                foreach ($fb as $f) {
                                    echo "<div>• " . esc($f) . "</div>";
                                }
                            } else {
                                echo esc($fb);
                            }
                        ?>
                    </td>
                    <td><?= esc($grade['graded_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No reports available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
