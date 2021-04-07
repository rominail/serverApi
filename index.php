<?php
try {
    $config = require_once 'config/env.php';
} catch (Throwable $e) {
    echo 'error loading page, please refresh';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seedbox exercise</title>
    <script src="js/common.js"></script>
    <script src="js/index.js"></script>
    <script>
        var apiBaseUrl = '<?= $config['apiBaseUrl'] ?>';
    </script>
</head>
<body>
<form id="loginForm">
    <label for="name">Name</label><input type="text" id="name" required>
    <label for="password">Password</label><input type="password" id="password" value="password" required>
    <input type="submit" value="connect">
</form>
</body>
</html>