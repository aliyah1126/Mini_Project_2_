<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

// Handle Delete
if (isset($_GET["delete_id"])) {
    $delId = (int)$_GET["delete_id"];
    
    // Fetch file path first
    $st = $conn->prepare("SELECT file_path FROM projects WHERE id = ? AND user_id = ?");
    $st->bind_param("ii", $delId, $_SESSION["user_id"]);
    $st->execute();
    $f = $st->get_result()->fetch_assoc();
    
    if ($f) {
        if (file_exists("../" . $f["file_path"])) {
            unlink("../" . $f["file_path"]);
        }
        $del = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
        $del->bind_param("ii", $delId, $_SESSION["user_id"]);
        $del->execute();
    }
    header("Location: my_submissions.php");
    exit;
}

$stmt = $conn->prepare("SELECT p.*, c.category_name 
                        FROM projects p 
                        JOIN categories c ON p.category_id = c.id 
                        WHERE p.user_id = ? 
                        ORDER BY p.created_at DESC");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

$page_title = "My Submissions";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-briefcase"></i> My Portfolio Submissions</h3>
    <a href="submit.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Submission</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>File</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No submissions found.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($row["title"]) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row["category_name"]) ?></span></td>
                            <td><small><?= htmlspecialchars($row["tech_stack"]) ?></small></td>
                            <td>
                                <a href="../download.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </td>
                            <td><?= date("d M Y", strtotime($row["created_at"])) ?></td>
                            <td class="text-center">
                                <a href="my_submissions.php?delete_id=<?= $row["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this submission?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>