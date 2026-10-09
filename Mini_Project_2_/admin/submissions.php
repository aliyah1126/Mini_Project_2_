<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("admin");

$sql = "SELECT p.*, u.full_name, u.email, c.category_name 
        FROM projects p 
        JOIN users u ON p.user_id = u.id 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);

$page_title = "All Submissions";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-list-task"></i> All Student Submissions</h3>
    <span class="badge bg-primary fs-6">Total: <?= $result->num_rows ?> Projects</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Student Name</th>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>Submitted File</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No submissions yet.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($row["full_name"]) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($row["email"]) ?></small>
                            </td>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($row["title"]) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row["category_name"]) ?></span></td>
                            <td><small><?= htmlspecialchars($row["tech_stack"]) ?></small></td>
                            <td>
                                <a href="../download.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-arrow-down"></i> File
                                </a>
                            </td>
                            <td><small><?= date("d M Y, h:i A", strtotime($row["created_at"])) ?></small></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>