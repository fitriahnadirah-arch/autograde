<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Rubric Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="mb-4">
    Rubric Management
    <?php if (!empty($activeSession)): ?>
        
    <?php endif; ?>
</h1>

<div class="row g-3">
    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <!-- Course Name -->
                        <h5 class="card-title"><?= esc($course['course_name']) ?></h5>

                        <!-- Check assessments -->
                        <?php if (!empty($course['assessments'])): ?>
                            <ul class="list-unstyled">
                                <?php foreach ($course['assessments'] as $a): ?>
                                    <li class="d-flex align-items-center mb-4 p-2 border rounded">
                                        <span class="flex-grow-1 text-truncate" style="max-width: 70%;">
                                            <?= esc($a['title']) ?>
                                        </span>
                                        <a href="<?= base_url('coordinator/rubric/manage/' . $a['id']) ?>" 
                                             class="btn btn-sm btn-primary">
                                             Manage Rubric
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No assessments yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">No courses available for this session</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
