<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Rubrics<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Rubrics for Assessment #<?= esc($assessmentId) ?></h2>

    <?php if(!empty($rubrics)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Criteria</th>
                        <th>Weight (%)</th>
                        <th>Scale 5</th>
                        <th>Scale 4</th>
                        <th>Scale 3</th>
                        <th>Scale 2</th>
                        <th>Scale 1</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rubrics as $r): ?>
                        <tr>
                            <td><?= esc($r['criteria']) ?></td>
                            <td class="text-center"><?= esc($r['weight']) ?></td>
                            <td><?= esc($r['scale_5']) ?></td>
                            <td><?= esc($r['scale_4']) ?></td>
                            <td><?= esc($r['scale_3']) ?></td>
                            <td><?= esc($r['scale_2']) ?></td>
                            <td><?= esc($r['scale_1']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No rubrics added yet.</div>
    <?php endif; ?>

    <!-- Add Rubric Form -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <strong>Add New Rubric</strong>
        </div>
        <div class="card-body">
            <form action="<?= base_url('coordinator/addRubric/'.$assessmentId) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Criteria</label>
                    <input type="text" name="criteria" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Weight (%)</label>
                    <input type="number" name="weight" step="0.01" class="form-control" required>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Scale 5</label>
                        <textarea name="scale_5" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Scale 4</label>
                        <textarea name="scale_4" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Scale 3</label>
                        <textarea name="scale_3" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Scale 2</label>
                        <textarea name="scale_2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Scale 1</label>
                        <textarea name="scale_1" class="form-control"></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Save Rubric</button>
                    <a href="<?= base_url('coordinator/rubric') ?>" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
