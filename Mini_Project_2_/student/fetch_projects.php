<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$q = trim($_GET["q"] ?? "");

$sql = "SELECT p.*, c.category_name, u.full_name 
        FROM projects p 
        JOIN categories c ON p.category_id = c.id 
        JOIN users u ON p.user_id = u.id";

if ($q !== "") {
    $sql .= " WHERE p.title LIKE ? OR p.tech_stack LIKE ? OR u.full_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $q . "%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
} else {
    $sql .= " ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="col-12"><div class="alert alert-warning">No matching projects found.</div></div>';
    exit;
}

while ($row = $result->fetch_assoc()):
?>
<div class="col-md-4">
    <div class="card h-100 shadow-sm border-0 card-project">
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary"><?= htmlspecialchars($row["category_name"]) ?></span>
                <small class="text-muted"><i class="bi bi-clock"></i> <?= date("n/j/Y", strtotime($row["created_at"])) ?></small>
            </div>
            <h5 class="card-title fw-bold"><?= htmlspecialchars($row["title"]) ?></h5>
            <p class="card-text text-muted flex-grow-1" style="font-size: 0.9rem;">
                <?= htmlspecialchars(substr($row["description"], 0, 100)) ?>...
            </p>
            <div class="mb-3">
                <small class="fw-bold d-block text-secondary">Tech Stack:</small>
                <span class="badge bg-light text-dark border"><?= htmlspecialchars($row["tech_stack"]) ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <small class="text-muted"><i class="bi bi-person"></i> <?= htmlspecialchars($row["full_name"]) ?></small>
                <a href="../download.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Report</a>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>