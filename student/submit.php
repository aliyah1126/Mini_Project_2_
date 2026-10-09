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
    $description = trim($_POST["description"] ?? "");
    $student_id = $_SESSION["user_id"];

    if (empty($title) || empty($category_id)) {
        $error = "Sila isi semua ruang wajib (Tajuk Projek dan Kategori).";
    } else {
        // Tentukan nilai category_id (sama ada nombor ID atau NULL jika pilihan biasa)
        $cat_val = is_numeric($category_id) ? intval($category_id) : NULL;

        // Menggunakan nama jadual 'projects'
        $stmt = $conn->prepare("INSERT INTO projects (student_id, category_id, title, tech_stack, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $student_id, $cat_val, $title, $tech_stack, $description);

        if ($stmt->execute()) {
            header("Location: ../index.php?status=submitted");
            exit;
        } else {
            $error = "Gagal menyimpan projek: " . $conn->error;
        }
    }
}

// Ambil senarai kategori dari database untuk pilihan dropdown
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

            <form method="POST">
                <!-- Project Title -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: FYP" required>
                </div>

                <!-- Category Dropdown -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        
                        <!-- Dynamic Categories dari Database -->
                        <?php if ($categories_query && $categories_query->num_rows > 0): ?>
                            <?php while ($cat = $categories_query->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>

                        <!-- Standard Default Options -->
                        <option value="Web Application">Web Application</option>
                        <option value="Mobile Application">Mobile Application</option>
                        <option value="System Administration">System Administration</option>
                        <option value="Database & Analytics">Database & Analytics</option>
                        <option value="Networking & Security">Networking & Security</option>
                    </select>
                </div>

                <!-- Tech Stack -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Technologies Used</label>
                    <input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap 5">
                </div>

                <!-- Project Description (Optional) -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Project Description <span class="text-muted fw-normal">(Optional)</span></label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Penerangan ringkas mengenai projek..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary fw-bold w-100"><i class="bi bi-send"></i> Submit Entry</button>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>