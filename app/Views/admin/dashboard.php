<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    /* Info Cards */
    .small-box {
        background: #4a6cf7; /* Biru penuh */
        color: #ffffff;
        text-align: center;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.25s ease-in-out;
    }
    .small-box:hover {
        background: #3754d1; /* Biru lebih gelap bila hover */
        transform: translateY(-4px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.2);
    }
    .small-box h2 {
        color: #ffffff;
    }

    /* Subject Section */
    .subject-box {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e0e6f5;
        box-shadow: 0 6px 12px rgba(0,0,0,0.05);
    }
    .subject-item {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #f9fbff;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 12px;
        border: 1px solid #e3eafc;
        font-weight: 500;
        color: #2b2b2b;
        transition: all 0.2s ease-in-out;
    }
    .subject-item:hover {
        background: #eef3ff;
        transform: translateX(4px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .subject-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #4a6cf7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Search box */
    .search-box input {
        border-radius: 12px 0 0 12px;
    }
    .search-box button {
        border-radius: 0 12px 12px 0;
    }
</style>

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard</h3>
            <p class="text-muted">Welcome, <?= session('username') ?></p>
        </div>
        <form action="<?= base_url('/dashboard/search') ?>" method="get">
            <div class="input-group search-box" style="max-width:300px">
                <input type="text" class="form-control" name="query" placeholder="Search...">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <!-- Info Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="small-box">
                <h6 class="text-uppercase mb-2">Plagiarism</h6>
                <h2 class="fw-bold">90%</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="small-box">
                <h6 class="text-uppercase mb-2">AI Tool Usage</h6>
                <h2 class="fw-bold">97%</h2>
            </div>
        </div>
    </div>

    <!-- Subject List Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="subject-box">
                <h6 class="fw-bold text-left mb-3">List of Subjects</h6>
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="subject-item">
                            <div class="subject-icon"><i class="bi bi-journal-text"></i></div>
                            <span><?= esc($course['course_name']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted">No subjects yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
