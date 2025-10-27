<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require 'db.php';
$user_id = $_SESSION['user_id'];
// Fetch user's accounts and recent transactions
$acc_q = $conn->prepare('SELECT id, account_number, balance, currency, type FROM accounts WHERE user_id = ?');
$acc_q->bind_param('i', $user_id);
$acc_q->execute();
$acc_r = $acc_q->get_result();
$transactions = [];
$tx_q = $conn->prepare('SELECT t.id,t.type,t.amount,t.description,t.created_at,a.account_number FROM transactions t JOIN accounts a ON t.account_id = a.id WHERE a.user_id = ? ORDER BY t.created_at DESC LIMIT 10');
$tx_q->bind_param('i', $user_id);
$tx_q->execute();
$tx_r = $tx_q->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<main class="container mt-4">
  <div class="row">
    <div class="col-lg-4 mb-3">
      <div class="card h-100">
        <div class="card-body">
          <h5>Hello, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h5>
          <p class="text-muted">Summary of your accounts</p>
          <?php while ($row = $acc_r->fetch_assoc()): ?>
            <div class="d-flex justify-content-between border rounded p-2 mb-2">
              <div>
                <strong><?php echo htmlspecialchars($row['account_number']); ?></strong><br>
                <small><?php echo htmlspecialchars($row['type']); ?></small>
              </div>
              <div class="text-end">
                <div><?php echo number_format($row['balance'],2); ?> <?php echo htmlspecialchars($row['currency']); ?></div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h5>Recent transactions</h5>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead><tr><th>Account</th><th>Type</th><th>Amount</th><th>Description</th><th>Date</th></tr></thead>
              <tbody>
              <?php while ($tx = $tx_r->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($tx['account_number']); ?></td>
                  <td><?php echo htmlspecialchars($tx['type']); ?></td>
                  <td><?php echo number_format($tx['amount'],2); ?></td>
                  <td><?php echo htmlspecialchars($tx['description']); ?></td>
                  <td><?php echo $tx['created_at']; ?></td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>