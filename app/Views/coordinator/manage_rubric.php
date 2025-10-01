<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Manage Rubric<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Manage Rubrics & Question</h2>

    <!-- Flash messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Upload Question Form -->
    <div class="mb-4">
        <form action="<?= base_url('coordinator/assessment/upload-question/'.$assessmentId) ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="question_file">Upload Question File</label>
                <input type="file" name="question_file" id="question_file" class="form-control" accept=".pdf,.doc,.docx,.txt" required>
            </div>
            <button type="submit" class="btn btn-success mt-2">Upload Question</button>
        </form>
    </div>

    <!-- Upload Rubric Excel Form -->
<div class="mb-4">
    <form action="<?= base_url('coordinator/rubric/upload-excel/'.$assessmentId) ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="rubric_file">Upload Rubric Excel</label>
            
            <div class="input-group">
                <input type="file" name="rubric_file" id="rubric_file" class="form-control" accept=".xls,.xlsx" required>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#rubricExampleModal">?</button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Upload Rubric</button>
    </form>
</div>

<!-- Modal for Rubric Example -->
<div class="modal fade" id="rubricExampleModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Format Rubric Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Pastikan Excel mengikut format berikut supaya sistem boleh baca dengan betul:</p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Criteria</th>
                            <th>Weight</th>
                            <th>Scale 5</th>
                            <th>Scale 4</th>
                            <th>Scale 3</th>
                            <th>Scale 2</th>
                            <th>Scale 1</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Code Efficiency</td>
                            <td>20</td>
                            <td>Excellent</td>
                            <td>Good</td>
                            <td>Average</td>
                            <td>Poor</td>
                            <td>Very Poor</td>
                        </tr>
                        <tr>
                            <td>Code Readability</td>
                            <td>10</td>
                            <td>Excellent</td>
                            <td>Good</td>
                            <td>Average</td>
                            <td>Poor</td>
                            <td>Very Poor</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Rubrics Table -->
<div class="mb-3 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Rubrics</h4>
    <form action="<?= base_url('coordinator/rubric/deleteAll/'.$assessmentId) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete all rubrics?');">
        <button type="submit" class="btn btn-danger">Delete All Rubrics</button>
    </form>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Criteria</th>
            <th>Weight</th>
            <th>Scale 5</th>
            <th>Scale 4</th>
            <th>Scale 3</th>
            <th>Scale 2</th>
            <th>Scale 1</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($rubrics)): ?>
            <?php foreach($rubrics as $rubric): ?>
                <tr>
                    <td><?= esc($rubric['criteria']) ?></td>
                    <td><?= esc($rubric['weight']) ?></td>
                    <td><?= esc($rubric['scale_5']) ?></td>
                    <td><?= esc($rubric['scale_4']) ?></td>
                    <td><?= esc($rubric['scale_3']) ?></td>
                    <td><?= esc($rubric['scale_2']) ?></td>
                    <td><?= esc($rubric['scale_1']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No rubrics found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>

<?= $this->endSection() ?>
