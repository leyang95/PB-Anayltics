<?php // Example 26-6: checkuser.php
  require_once '../connectivity.php';

  if (isset($_POST['email']))
  {
    $user   = sanitizeString($_POST['email']);
    $result = queryMysql("SELECT * FROM members WHERE user='$user'");

    if ($result->num_rows)
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; " .
           "</span>";
  }
?>
