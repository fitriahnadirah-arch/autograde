<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Manage Test Cases</h2>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Add New Test Case -->
    <div class="card mb-4">
        <div class="card-header">Add New Test Case</div>
        <div class="card-body">
            <form method="post" action="<?= base_url('coordinator/saveTestcase') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="assessment_id" class="form-label">Assessment</label>
                    <select name="assessment_id" id="assessment_id" class="form-select" required>
                        <option value="">-- Select Assessment --</option>
                        <?php foreach ($assessments as $assessment): ?>
                            <option value="<?= $assessment['id'] ?>">
                                <?= esc($assessment['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="input" class="form-label">Input</label>
                    <textarea name="input" id="input" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="expected_output" class="form-label">Expected Output</label>
                    <textarea name="expected_output" id="expected_output" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Test Case</button>
            </form>
        </div>
    </div>

    <!-- List of Test Cases -->
    <div class="card">
        <div class="card-header">Existing Test Cases</div>
        <div class="card-body">
            <?php if (!empty($testcases)): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Assessment</th>
                            <th>Input</th>
                            <th>Expected Output</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testcases as $testcase): ?>
                            <tr>
                                <td><?= $testcase['id'] ?></td>
                                <td><?= esc($testcase['assessment_title']) ?></td>
                                <td><pre><?= esc($testcase['input']) ?></pre></td>
                                <td><pre><?= esc($testcase['expected_output']) ?></pre></td>
                                <td>
                                    <a href="<?= base_url('coordinator/deleteTestcase/'.$testcase['id']) ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this test case?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No test cases found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
