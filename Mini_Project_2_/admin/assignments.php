<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("admin");
$error="";$success="";$cats=$conn->query("SELECT id,category_name FROM categories ORDER BY category_name");
if($_SERVER["REQUEST_METHOD"]==="POST"){
 $title=trim($_POST["title"]??"");$desc=trim($_POST["description"]??"");$cat=(int)($_POST["category_id"]??0);
 if($title===""||$desc===""||!$cat)$error="Please fill title, description and category.";
 else{$st=$conn->prepare("INSERT INTO assignments(title,description,category_id,created_by) VALUES(?,?,?,?)");$st->bind_param("ssii",$title,$desc,$cat,$_SESSION["user_id"]);$success=$st->execute()?"Assignment created successfully.":"Unable to create assignment.";}
}
$list=$conn->query("SELECT a.*,c.category_name FROM assignments a LEFT JOIN categories c ON a.category_id=c.id ORDER BY a.id DESC");
$page_title="Create Assignment";$asset_prefix="../";include "../includes/header.php";
?>
<div class="row g-3"><div class="col-md-5"><div class="card p-3"><h4 class="text-primary">+ Create Assignment</h4><?php if($error):?><div class="alert alert-danger"><?=$error?></div><?php endif;?><?php if($success):?><div class="alert alert-success"><?=$success?></div><?php endif;?>
<form method="POST" class="client-validate" novalidate><label>Assignment Title</label><input name="title" class="form-control mb-3" required><label>Category</label><select name="category_id" class="form-select mb-3" required><option value="">-- Select Category --</option><?php while($c=$cats->fetch_assoc()):?><option value="<?=$c["id"]?>"><?=htmlspecialchars($c["category_name"])?></option><?php endwhile;?></select><label>Description</label><textarea name="description" class="form-control mb-3" rows="5" required></textarea><button class="btn btn-primary w-100">+ Create Assignment</button></form></div></div>
<div class="col-md-7"><div class="table-card"><h5>Assignments</h5><table class="table"><thead><tr><th>Title</th><th>Category</th><th>Description</th></tr></thead><tbody><?php while($r=$list->fetch_assoc()):?><tr><td><?=htmlspecialchars($r["title"])?></td><td><?=htmlspecialchars($r["category_name"]??"")?></td><td><?=htmlspecialchars($r["description"])?></td></tr><?php endwhile;?></tbody></table></div></div></div>
<?php include "../includes/footer.php"; ?>