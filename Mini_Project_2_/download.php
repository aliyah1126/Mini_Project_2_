<?php
require "config/db.php";
require "includes/auth.php";
require_login();

$id = (int)($_GET["id"] ?? 0);

if ($_SESSION["role"] === "admin") {
    $st = $conn->prepare("SELECT file_path FROM projects WHERE id = ?");
    $st->bind_param("i", $id);
} else {
    $st = $conn->prepare("SELECT file_path FROM projects WHERE id = ? AND user_id = ?");
    $st->bind_param("ii", $id, $_SESSION["user_id"]);
}

$st->execute();
$r = $st->get_result()->fetch_assoc();

if (!$r) {
    http_response_code(404);
    exit("File not found or permission denied.");
}

$path = __DIR__ . "/" . $r["file_path"];

if (!is_file($path)) {
    http_response_code(404);
    exit("File missing on server.");
}

$filename = basename($path);
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Content-Length: " . filesize($path));
readfile($path);
exit;
?>