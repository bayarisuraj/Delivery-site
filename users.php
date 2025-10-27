<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require 'db.php';
// simple user list and add user (admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $full = $_POST['full_name'] ?? '';
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $ins = $conn->prepare('INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)');
    $ins->bind_param('sss', $username, $hash, $full);
    $ins->execute();
    header('Location: users.php');
    exit;
}
$res = $conn->query('SELECT id, username, full_name, role, created_at FROM users ORDER BY id DESC');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<main class="container mt-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>Users</h4>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">Add user</button>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>ID</th><th>Username</th><th>Full name</th><th>Role</th><th>Created</th></tr></thead>
      <tbody>
      <?php while ($u = $res->fetch_assoc()): ?>
        <tr><td><?php echo $u['id']; ?></td><td><?php echo htmlspecialchars($u['username']); ?></td><td><?php echo htmlspecialchars($u['full_name']); ?></td><td><?php echo htmlspecialchars($u['role']); ?></td><td><?php echo $u['created_at']; ?></td></tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modal -->
<div class="modal fade" id="addUser" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Create User</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control" required></div>
        <div class="mb-3"><label>Full name</label><input name="full_name" class="form-control"></div>
      </div>
      <div class="modal-footer"><button name="create_user" class="btn btn-primary">Create</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>