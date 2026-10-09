<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $category_id = (int)($_POST["category_id"] ?? 0);
    $tech_stack = trim($_POST["tech_stack"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($title === "" || $category_id === 0 || $tech_stack === "" || $description === "") {
        $error = "Please fill in all required fields.";
    } elseif (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Please select a file to upload.";
    } else {
        $file = $_FILES["file"];
        $maxSize = 5 * 1024 * 1024; // 5MB
        $allowedExts = ["pdf", "docx", "txt", "zip"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if ($file["size"] > $maxSize) {
            $error = "File size exceeds the 5MB limit.";
        } elseif (!in_array($ext, $allowedExts)) {
            $error = "Invalid file type. Allowed: PDF, DOCX, TXT, ZIP.";
        } else {
            $uploadDir = "../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newName = uniqid("proj_") . "." . $ext;
            $destination = $uploadDir . $newName;
            $dbPath = "uploads/" . $newName;

            if (move_uploaded_file($file["tmp_name"], $destination)) {
                $stmt = $conn->prepare("INSERT INTO projects (user_id, category_id, title, description, tech_stack, file_path) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissss", $_SESSION["user_id"], $category_id, $title, $description, $tech_stack, $dbPath);
                
                if ($stmt->execute()) {
                    $success = "Project submitted successfully!";
                } else {
                    $error = "Database operation failed.";
                }
            } else {
                $error = "Failed to upload file.";
            }
        }
    }
}

// Fetch categories
$catRes = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

$page_title = "Submit Project";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="card p-4 mx-auto border-0 shadow-sm" style="max-width: 700px;">
    <h3 class="text-primary fw-bold mb-3"><i class="bi bi-cloud-arrow-up"></i> Submit Portfolio Project</h3>

    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="client-validate" novalidate>
        <div class="mb-3">
            <label class="form-label">Project Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $catRes->fetch_assoc()): ?>
                    <option value="<?= $cat["id"] ?>"><?= htmlspecialchars($cat["category_name"]) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tech Stack (Comma Separated)</label>
            <input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap 5, AJAX" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Project Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Documentation (PDF, DOCX, TXT, or ZIP - Max 5MB)</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-send"></i> Submit Entry</button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>