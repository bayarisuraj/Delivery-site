<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require 'db.php';
$user_id = $_SESSION['user_id'];
// fetch user's accounts
$acc_q = $conn->prepare('SELECT id, account_number FROM accounts WHERE user_id = ?');
$acc_q->bind_param('i', $user_id);
$acc_q->execute();
$acc_r = $acc_q->get_result();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = intval($_POST['from_account']);
    $to = intval($_POST['to_account']);
    $amount = floatval($_POST['amount']);
    $desc = $_POST['description'] ?? '';
    if ($amount <= 0) { $message = 'Invalid amount'; }
    else {
        // Check balance
        $bq = $conn->prepare('SELECT balance FROM accounts WHERE id = ? AND user_id = ?');
        $bq->bind_param('ii', $from, $user_id);
        $bq->execute();
        $bq->bind_result($from_balance);
        if ($bq->fetch()) {
            if ($from_balance < $amount) { $message = 'Insufficient funds'; }
            else {
                $conn->begin_transaction();
                try {
                    // debit from
                    $u1 = $conn->prepare('UPDATE accounts SET balance = balance - ? WHERE id = ?');
                    $u1->bind_param('di', $amount, $from);
                    $u1->execute();
                    // credit to
                    $u2 = $conn->prepare('UPDATE accounts SET balance = balance + ? WHERE id = ?');
                    $u2->bind_param('di', $amount, $to);
                    $u2->execute();
                    // Insert transactions
                    $t1 = $conn->prepare('INSERT INTO transactions (account_id, type, amount, description) VALUES (?, ?, ?, ?)');
                    $t1->bind_param('isds', $from, $type1, $amount, $desc);
                    $type1 = 'debit';
                    $t1->execute();
                    $t2 = $conn->prepare('INSERT INTO transactions (account_id, type, amount, description) VALUES (?, ?, ?, ?)');
                    $t2->bind_param('isds', $to, $type2, $amount, $desc);
                    $type2 = 'credit';
                    $t2->execute();
                    $conn->commit();
                    $message = 'Transfer completed';
                } catch (Exception $e) {
                    $conn->rollback();
                    $message = 'Transfer failed: ' . $e->getMessage();
                }
            }
        } else { $message = 'Source account not found'; }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transfer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<main class="container mt-4">
  <h4>Make a Transfer</h4>
  <?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <div class="col-md-4">
      <label>From</label>
      <select name="from_account" class="form-select">
        <?php foreach ($acc_r as $a): ?><option value="<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['account_number']); ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label>To (account id)</label>
      <input name="to_account" class="form-control" placeholder="Recipient account id" required>
    </div>
    <div class="col-md-2">
      <label>Amount</label>
      <input name="amount" class="form-control" required>
    </div>
    <div class="col-md-12">
      <label>Description</label>
      <input name="description" class="form-control">
    </div>
    <div class="col-12"><button class="btn btn-primary">Send</button></div>
  </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>