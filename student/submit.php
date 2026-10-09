<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $category_id = trim($_POST["category_id"] ?? "");
    $tech_stack = trim($_POST["tech_stack"] ?? "");
    $description = trim($_POST["description"] ?? ""); // Deskripsi bersifat opsional

    if (empty($title) || empty($category_id)) {
        $error = "Please fill in the required fields (Title and Category).";
    } else {
        // Logika simpan data / muat naik fail
        // Masukkan ke pangkalan data mengikut keperluan sistem anda
        $success = "Project entry submitted successfully!";
    }
}

// Ambil kategori dari database jika ada
$categories_query = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

$page_title = "Submit Entry";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-primary mb-3"><i class="bi bi-upload"></i> Submit Project Entry</h4>

            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data" novalidate>
                <!-- Project Title -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. FYP" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                </div>

                <!-- Category Dropdown dengan Opsi Pilihan Standard -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <option value="Web Application">Web Application</option>
                        <option value="Mobile Application">Mobile Application</option>
                        <option value="System Administration">System Administration</option>
                        <option value="Database & Analytics">Database & Analytics</option>
                        <option value="Networking & Security">Networking & Security</option>
                        
                        <?php if ($categories_query && $categories_query->num_rows > 0): ?>
                            <?php while ($cat = $categories_query->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>">
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Tech Stack -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Technologies Used</label>
                    <input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap 5" value="<?= htmlspecialchars($_POST['tech_stack'] ?? '') ?>">
                </div>

                <!-- Project Description (Opsional / Optional) -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Project Description <span class="text-muted fw-normal">(Optional)</span></label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Brief explanation of your project (optional)..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <!-- Upload Documentation -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Upload Documentation (PDF, DOCX, TXT, or ZIP - Max 5MB)</label>
                    <input type="file" name="document" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-send"></i> Submit Entry</button>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>