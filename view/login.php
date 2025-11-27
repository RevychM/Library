<?php
require_once(__DIR__ . '/../model/dblibrary.php');
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title> Login </title>
</head>

<nav>
</nav>

<body>
  <h2>Sign in</h2>
<div id = "loginBox"> 
<form action = "/book/index.php?action=validate_login" method="post">

  <label for="email">Email Address:</label><br>
  <input type="text" id="email" name="email"><br><br>
  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" id="submit" value="login"><br><br>
  <a href="/book/index.php?action=register">Register for an account</a><br>
</form>
</div>

<button onclick="history.back()">Return to homepage</button>
</body>

</html>