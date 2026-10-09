<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("admin");

$studentsCount = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE role='student'")->fetch_assoc()["cnt"];
$categoriesCount = $conn->query("SELECT COUNT(*) as cnt FROM categories")->fetch_assoc()["cnt"];
$submissionsCount = $conn->query("SELECT COUNT(*) as cnt FROM projects")->fetch_assoc()["cnt"];

$recentStmt = $conn->query("SELECT p.*, u.full_name, c.category_name 
                            FROM projects p 
                            JOIN users u ON p.user_id = u.id 
                            JOIN categories c ON p.category_id = c.id 
                            ORDER BY p.created_at DESC LIMIT 5");

$page_title = "Admin Dashboard";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="alert alert-success alert-dismissible fade show mb-4">
    <i class="bi bi-check-circle"></i> Welcome back, <strong>Admin</strong>!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<h3 class="fw-bold"><i class="bi bi-speedometer2"></i> Admin Overview</h3>
<p class="text-muted mb-4">Lecturer & Coordinator Evaluation Portal</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">Students</h6>
                    <h2 class="fw-bold mb-0"><?= $studentsCount ?></h2>
                </div>
                <i class="bi bi-people fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">Categories</h6>
                    <h2 class="fw-bold mb-0"><?= $categoriesCount ?></h2>
                </div>
                <i class="bi bi-layers fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">Submissions</h6>
                    <h2 class="fw-bold mb-0"><?= $submissionsCount ?></h2>
                </div>
                <i class="bi bi-folder-check fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Recent Submissions</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student Name</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $recentStmt->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($row["full_name"]) ?></td>
                        <td><?= htmlspecialchars($row["title"]) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row["category_name"]) ?></span></td>
                        <td><?= date("d M Y", strtotime($row["created_at"])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>