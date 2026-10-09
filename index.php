<?php
require "config/db.php";

$search = trim($_GET['search'] ?? '');

// Kueri SQL dengan LEFT JOIN supaya projek sentiasa muncul
$sql = "SELECT assignments.*, users.full_name, categories.category_name 
        FROM assignments 
        LEFT JOIN users ON assignments.student_id = users.id 
        LEFT JOIN categories ON assignments.category_id = categories.id";

if (!empty($search)) {
    $sql .= " WHERE assignments.title LIKE '%$search%' 
              OR assignments.tech_stack LIKE '%$search%' 
              OR users.full_name LIKE '%$search%'";
}

$sql .= " ORDER BY assignments.id DESC";
$projects = $conn->query($sql);
?>