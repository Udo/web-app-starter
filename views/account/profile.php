<?php
if (!User::IsSignedIn()) {
    header('Location: login.php');
    exit;
}
$u = User::$current_profile;
?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile</title>
</head>
<body>
<h1>Profile</h1>
<p>Email: <?php echo htmlspecialchars($u['email']); ?></p>
<p>Roles: <?php echo htmlspecialchars(implode(', ', $u['roles'] ?? [])); ?></p>
<p>Created: <?php echo htmlspecialchars(date('c', $u['created'] ?? time())); ?></p>
<p><a href="<?= URL::link('account/logout') ?>">Logout</a></p>
</body>
</html>
