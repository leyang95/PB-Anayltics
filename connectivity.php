<?php
	 $dbhost  = 'localhost';    // Unlikely to require changing
	 $dbname  = 'feedlot';   // Modify these...
	 $dbuser  = 'nishatech';   // ...variables according
	 $dbpass  = 'nishatech';   // ...to your installation
	 $appname = "Feedlot Pro"; // ...and preference
		
	$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
    	exit();
	}
	if(!isset($_SESSION['user_id'])) 
		session_start();

	function SignIn(){
		
		if(isset($_POST['user'])){
			$user = sanitizeString($_POST['user']);
    		$pass = sanitizeString($_POST['pass']);
    		$salt1 = "qm&h*";
    		$salt2 = "pg!@";
    		$token = md5("$salt1$pass$salt2");

			$query = queryMysql("SELECT * FROM members where user = '$user' AND pass = '$token'");
			
			if ($query->num_rows > 0){
				$row = $query->fetch_assoc();
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['lastName'] = $row['lastname'];
				die(header("Location:home/index.php"));
			}
			else{
				die(header("Location:index.html?loginFailed=true&reason=password"));
			}	
		}
		else{
			die(header("Location:index.html"));
		}
	}

	if(isset($_POST['submit'])){
		SignIn();
	}


	function createTable($name, $query)
	{
	    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
	    echo "Table '$name' created or already exists.<br>";
	}

  function queryMysql($query)
  {
    global $con;
    $result = $con->query($query);
    if (!$result) die($con->error);
    return $result;
  }

  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }

  function sanitizeString($var)
  {
    global $con;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $con->real_escape_string($var);
  }
?>