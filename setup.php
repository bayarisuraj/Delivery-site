<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, password, full_name FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id, $stored_password, $full);

    if ($stmt->fetch()) {
        // ✅ Compare plain text password
        if ($password === $stored_password) {
            session_regenerate_id();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['full_name'] = $full;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bank UI — Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
  <div class="row justify-content-center align-items-center vh-100">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="card-title mb-3 text-center">Bank Portal</h3>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>
          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required value="diana">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required value="123">
            </div>
            <div class="d-grid">
              <button class="btn btn-primary">Sign in</button>
            </div>
          </form>
        </div>
      </div>
      <p class="text-center mt-3 small text-muted">Default: diana / 123 — run <code>setup.php</code> first</p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
