<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        .rubric { margin-top: 10px; }
    </style>
</head>
<body>
    <h3>Evaluation Reports</h3>

    <?php if (!empty($grades)): ?>
        <?php foreach ($grades as $grade): ?>
            <h4>Student(s): <?= esc($grade['group_members']) ?></h4>
            <p>
                <strong>Course:</strong> <?= esc($grade['course_name']) ?> (<?= esc($grade['course_code']) ?>) <br>
                <strong>Assessment:</strong> <?= esc($grade['assessment_title']) ?> <br>
                <strong>Score:</strong> <?= esc($grade['score']) ?>% <br>
                <strong>Graded At:</strong> <?= esc($grade['graded_at']) ?>
            </p>

            <h5>Rubric Evaluation:</h5>
            <table class="rubric">
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
                    <?php if (!empty($grade['rubric'])): ?>
                        <?php foreach ($grade['rubric'] as $r): ?>
                            <tr>
                                <td><?= esc($r['criteria']) ?></td>
                                <td><?= esc($r['weight']) ?></td>
                                <td><?= esc($r['scale_5']) ?></td>
                                <td><?= esc($r['scale_4']) ?></td>
                                <td><?= esc($r['scale_3']) ?></td>
                                <td><?= esc($r['scale_2']) ?></td>
                                <td><?= esc($r['scale_1']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No rubric defined.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h5>Feedback:</h5>
            <p>
                <?php
                    $fb = json_decode($grade['feedback'], true);
                    if (is_array($fb)) {
                        echo implode("; ", array_map('esc', $fb));
                    } else {
                        echo esc($fb);
                    }
                ?>
            </p>

            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">No reports available</p>
    <?php endif; ?>
</body>
</html>
