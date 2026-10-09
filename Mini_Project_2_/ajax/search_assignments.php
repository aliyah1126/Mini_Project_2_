<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("student");
$q=trim($_GET["q"]??"");
$like="%".$q."%";
$stmt=$conn->prepare("SELECT a.id,a.title,a.description,a.created_at,c.category_name,u.full_name FROM assignments a LEFT JOIN categories c ON a.category_id=c.id LEFT JOIN users u ON a.created_by=u.id WHERE a.title LIKE ? OR a.description LIKE ? OR c.category_name LIKE ? ORDER BY a.id DESC");
$stmt->bind_param("sss",$like,$like,$like); $stmt->execute(); $res=$stmt->get_result();
while($r=$res->fetch_assoc()): ?>
<div class="col-md-4"><div class="card project-card p-3">
<span class="badge bg-primary align-self-start"><?=htmlspecialchars($r["category_name"]??"General")?></span>
<h5 class="mt-2"><?=htmlspecialchars($r["title"])?></h5>
<p class="text-muted"><?=htmlspecialchars($r["description"])?></p>
<div class="small text-muted mt-auto">Created by <?=htmlspecialchars($r["full_name"]??"Admin")?></div>
<a class="btn btn-outline-primary btn-sm mt-2" href="../student/submit.php?assignment_id=<?=$r["id"]?>">Submit Project</a>
</div></div>
<?php endwhile; ?>