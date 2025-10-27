<?php if(!isset($_SESSION)) session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">BankUI</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="accounts.php">Accounts</a></li>
        <li class="nav-item"><a class="nav-link" href="transfer.php">Transfer</a></li>
        <li class="nav-item"><a class="nav-link" href="transactions.php">Transactions</a></li>
        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
      </ul>
      <div class="d-flex">
        <span class="navbar-text me-2">Hello, <?php echo htmlspecialchars(\$_SESSION['username'] ?? ''); ?></span>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>