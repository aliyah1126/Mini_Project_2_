<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("admin");

$error = "";
$success = "";

// 1. Padam Kategori (Delete)
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $success = "Category deleted successfully!";
    } else {
        $error = "Failed to delete category.";
    }
}

// 2. Tambah Kategori Baru (Create)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["category_name"] ?? "");
    $desc = trim($_POST["description"] ?? "");

    if ($name === "" || $desc === "") {
        $error = "Please fill in all category fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $desc);
        if ($stmt->execute()) {
            $success = "Category created successfully!";
        } else {
            $error = "Failed to create category or category name already exists.";
        }
    }
}

// 3. Ambil Senarai Kategori
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");

$page_title = "Manage Categories";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-primary mb-3"><i class="bi bi-folder-plus"></i> Create Category</h4>

            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Category Name</label>
                    <input type="text" name="category_name" class="form-control" placeholder="e.g. Web Development" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Brief description..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-plus-lg"></i> Add Category</button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-4 border-0 shadow-sm">
            <h4 class="fw-bold mb-3">Existing Categories</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($categories && $categories->num_rows > 0): ?>
                            <?php while ($c = $categories->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($c["category_name"]) ?></td>
                                    <td><small><?= htmlspecialchars($c["description"]) ?></small></td>
                                    <td class="text-center">
                                        <a href="categories.php?delete_id=<?= $c['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this category?');">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No categories created yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>