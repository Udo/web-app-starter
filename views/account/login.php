<?php

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = User::AuthWithPassword($_POST['password'] ?? '');
    if ($res['result']) {
        header('Location: '.URL::link('account/profile'));
        exit;
    } else {
        $errors[] = $res['message'];
    }
}

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<?php if ($errors): ?>
    <div style="color: red"><?php echo htmlspecialchars(implode(', ', $errors)); ?></div>
<?php endif; ?>
<form method="post">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
<p><a href="register.php">Register</a></p>
</body>
</html>
