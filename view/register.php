<?php
require_once(__DIR__ . '/../model/dblibrary.php');
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title> Register </title>
</head>

<header><h1>Account Registration</h1></header>

<nav>
    <a href="/book/index.php?action=library">Home</a> 
    <a href="#Contact Us">Contact Us</a>
    <a href="#Events">Events</a>
</nav>

<body>
<div id = "loginBox">
<form action="/book/index.php?action=registration_submission" method="post" id="register_form">
  <label for="fname">First name:</label><br>
  <input type="text" id="fname" name="fname" required><br>
  <label for="lname">Last name:</label><br>
  <input type="text" id="lname" name="lname" required><br>
  <label for="email">Email Address:</label><br>
  <input type="text" id="email" name="email" required><br> 
  <label for="phNum">Phone Number:</label><br>
  <input type="text" id="phNum" name="phNum" required><br>
  <label for="password">Choose a password:</label><br>
  <input type="password" id="password" name="password" required><br><br>
  <input type="submit" id="Register" name="Register" value="Register Now"><br><br>
</form>
</div>


  <button onclick="history.back()">Return to login</button>
</body>

</html>