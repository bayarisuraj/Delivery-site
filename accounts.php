<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require 'db.php';
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_account'])) {
    $type = $_POST['type'] ?? 'savings';
    $acc_num = 'AC' . time() . rand(100,999);
    $stmt = $conn->prepare('INSERT INTO accounts (user_id, account_number, balance, currency, type) VALUES (?, ?, ?, ?, ?)');
    $bal = floatval($_POST['balance'] ?? 0);
    $currency = $_POST['currency'] ?? 'USD';
    $stmt->bind_param('isdss', $user_id, $acc_num, $bal, $currency, $type);
    $stmt->execute();
    header('Location: accounts.php');
    exit;
}
$acc_q = $conn->prepare('SELECT id, account_number, balance, currency, type, created_at FROM accounts WHERE user_id = ?');
$acc_q->bind_param('i', $user_id);
$acc_q->execute();
$acc_r = $acc_q->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accounts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<main class="container mt-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>Your Accounts</h4>
    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createModal">Create Account</button>
  </div>
  <div class="row">
    <?php while ($a = $acc_r->fetch_assoc()): ?>
      <div class="col-md-6 col-lg-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <h6><?php echo htmlspecialchars($a['account_number']); ?></h6>
            <p class="mb-1 small text-muted"><?php echo htmlspecialchars($a['type']); ?></p>
            <h5><?php echo number_format($a['balance'],2); ?> <?php echo htmlspecialchars($a['currency']); ?></h5>
            <p class="small text-muted">Created <?php echo $a['created_at']; ?></p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Create Account</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label>Type</label><select name="type" class="form-select"><option value="savings">Savings</option><option value="current">Current</option></select></div>
        <div class="mb-3"><label>Initial balance</label><input name="balance" class="form-control" value="0"></div>
        <div class="mb-3"><label>Currency</label><input name="currency" class="form-control" value="USD"></div>
      </div>
      <div class="modal-footer"><button name="create_account" class="btn btn-primary">Create</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>