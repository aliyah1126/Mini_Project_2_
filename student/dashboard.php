<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$page_title = "Student Dashboard";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> Welcome back, <strong><?= htmlspecialchars($_SESSION["full_name"]) ?></strong>!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-0">Project Showcase Directory</h2>
        <p class="text-muted">Explore student portfolio submissions and FYP projects.</p>
    </div>
    <a href="submit.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Submit New Project</a>
</div>

<div class="card p-3 mb-4 border-0 shadow-sm">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Live search by project title, tech stack, or student name...">
    </div>
</div>

<div id="projectContainer" class="row g-4">
    <!-- Projects loaded via AJAX -->
</div>

<script>
function fetchProjects(query = '') {
    fetch('fetch_projects.php?q=' + encodeURIComponent(query))
        .then(res => res.text())
        .then(html => {
            document.getElementById('projectContainer').innerHTML = html;
        });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    fetchProjects(this.value);
});

// Initial Load
fetchProjects();
</script>

<?php include "../includes/footer.php"; ?>