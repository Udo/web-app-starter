<?php

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = User::Create(['email' => $email, 'password' => $password]);
    if ($res['result']) {
        $success = true;
    } else {
        $errors[] = $res['message'];
    }
}

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<?php if ($success): ?>
    <div style="color: green">Registration successful. <a href="<?= URL::link('account/login') ?>">Log in</a></div>
<?php endif; ?>
<?php if ($errors): ?>
    <div style="color: red"><?php echo htmlspecialchars(implode(', ', $errors)); ?></div>
<?php endif; ?>
<form method="post">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Register</button>
</form>
</body>
</html>
