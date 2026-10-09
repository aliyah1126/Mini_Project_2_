<?php
require "config/db.php";

$search = trim($_GET['search'] ?? '');

// Semak nama kolom yang wujud dalam tabel projects
// Sambungkan tabel projects dengan users dan categories
$sql = "SELECT projects.*, users.full_name, categories.category_name 
        FROM projects 
        LEFT JOIN users ON (projects.user_id = users.id OR projects.student_id = users.id)
        LEFT JOIN categories ON (projects.category_id = categories.id OR projects.category_name = categories.category_name)";

if (!empty($search)) {
    $search_clean = $conn->real_escape_string($search);
    $sql .= " WHERE projects.title LIKE '%$search_clean%' 
              OR projects.tech_stack LIKE '%$search_clean%' 
              OR users.full_name LIKE '%$search_clean%'";
}

$sql .= " ORDER BY projects.id DESC";
$projects = $conn->query($sql);
?>