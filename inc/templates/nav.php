<?php
//session_start();
//$_SESSION['loggdin'] = $_SESSION['loggdin'] ?? false;
require_once ('function.php');
?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/index.php">CRUD Project</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="/index.php?task=report">All Student</a></li>
      <?php if(hasPrivilege()): ?>
      <li><a href="/index.php?task=add">Add New Student</a></li>
      <?php endif; ?>
      <?php if(isAdmin()): ?>
        <li><a href="/index.php?task=seed">Seed</a></li>
      <?php endif; ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    <?php if(isset($_SESSION['loggdin'])): ?>
      <?php if(isset($_SESSION['role']) && $_SESSION['loggdin'] = true): ?>
        <li><a href="/login.php?logout=true"><span class="glyphicon glyphicon-user"></span> Log Out <?php echo "( ". $_SESSION['role']. " )"; ?></a></li>
      <?php endif; ?>

      <?php else: ?>
      <li><a href="/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>