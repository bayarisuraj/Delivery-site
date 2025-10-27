<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require 'db.php';
$user_id = $_SESSION['user_id'];
$tx_q = $conn->prepare('SELECT t.*, a.account_number FROM transactions t JOIN accounts a ON t.account_id = a.id WHERE a.user_id = ? ORDER BY t.created_at DESC');
$tx_q->bind_param('i', $user_id);
$tx_q->execute();
$tx_r = $tx_q->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transactions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<main class="container mt-4">
  <h4>Your transactions</h4>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Account</th><th>Type</th><th>Amount</th><th>Description</th><th>Date</th></tr></thead>
      <tbody>
      <?php while ($t = $tx_r->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($t['account_number']); ?></td>
          <td><?php echo htmlspecialchars($t['type']); ?></td>
          <td><?php echo number_format($t['amount'],2); ?></td>
          <td><?php echo htmlspecialchars($t['description']); ?></td>
          <td><?php echo $t['created_at']; ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>