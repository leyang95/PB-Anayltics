<?php // Example 26-6: checkuser.php
  require_once '../connectivity.php';

  $error = $user = $pass = "";

  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['email']))
  {
    $user = sanitizeString($_POST['email']);
    $pass = sanitizeString($_POST['password']);
    $firstName = sanitizeString($_POST['firstName']);
    $lastName = sanitizeString($_POST['lastName']);
    $zipCode = sanitizeString($_POST['zipCode']);
    $phoneNumber = sanitizeString($_POST['phoneNumber']);
    $hearAbout = sanitizeString($_POST['hearAbout']);
    $confirmPass = sanitizeString($_POST['confirmPassword']);
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    $token = md5("$salt1$pass$salt2");

    if ($confirmPass != $pass )
      $error = "Password not match";
    else
    {
      $result = queryMysql("SELECT * FROM members WHERE user='$user'");

      if ($result->num_rows)
        $error = "That username already exists<br><br>";
      else
      {
        queryMysql("INSERT INTO members VALUES('$user', '$token', '$firstName', '$lastName', '$zipCode', '$phoneNumber', '$hearAbout', '')");
        die(header("Location:../index.html"));
      }
    }
  }
?>
