<!DOCTYPE html>
<html>
<head>
<title>Reduced Risk Password Transmission | selfdestruct.pw</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="resources/style.css" type="text/css" />
</head>
<body>

<div class="logo"><a href="http://selfdestruct.pw/"><img src="resources/selfdestruct-logo.png" alt="selfdestruct.pw" /></a></div>

<p>
selfdestruct.pw generates a unique URL for each password entered,<br>and that URL and associated password only live for 24 hours.<br>
After 24 hours, the password is <b>permanently deleted</b>.
</p>


<?php

// index.php - main selfdestruct.pw php file

// include godaddy-compliant password and hash generators
include('includes/godaddyPass.php');
include('includes/getHash.php');
include('includes/config.php');

// closing html
function closeHtml() {
    echo "<h5>selfdestruct.pw | Reduced Risk Password Transmission</h5>\n\n";
    echo "</body>\n";
    echo "</html>";
}

// create godaddy and hash objects
$obj1 = new godaddyPass;
$randPass = $obj1->genpass();
$obj2 = new getHash;

// do work
if ( isset($_POST['submit']) && isset($_POST['password']) ) {
    // get and store password
    $password = $_POST['password'];

    // create and store hash
    $hash = $obj2->hashPass($password);

    // (DEBUG: db connection
    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepared query
    $sql = "INSERT INTO hashpass (password,hash,created) VALUES (:password,:hash,now())";
    $q = $conn->prepare($sql);
    $q->bindParam(':password', $password, PDO::PARAM_STR);
    $q->bindParam(':hash', $hash, PDO::PARAM_STR);
    $q->execute(array(':password'=>$password, ':hash'=>$hash));

    // provide user feedback
    echo "<br>The URL for the entered password is:<br><br>\n";
    echo "<a href=\"http://selfdestruct.pw/?" . $hash . "\">http://selfdestruct.pw/?" . $hash . "</a><br><br><br>\n";
    closeHtml();
    die();
}

// retrieve password
$sha256regex = '/^[0-9a-f]{64}$/';
if ( isset($_SERVER['QUERY_STRING']) && preg_match($sha256regex, $_SERVER['QUERY_STRING']) ) {
    $passedHash = $_SERVER['QUERY_STRING'];

    // create database connection
    $lookupConn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);

    // DEBUG: db connection
    //$lookupConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepared query
    $lookupSql = "SELECT password FROM hashpass WHERE hash = :passedHash";
    $lookupQ = $lookupConn->prepare($lookupSql);
    $lookupQ->bindParam(':passedHash', $passedHash, PDO::PARAM_STR);
    $lookupQ->execute(array(':passedHash'=>$passedHash));

    // provide user feedback
    $row =$lookupQ->fetchObject();
    if ( !empty($row) ) {
        echo "<br><b>PASSWORD:</b> " . htmlentities($row->password) . "<br><br><br>\n\n";
        closeHtml();
        die();
    } else {
        echo "<br><b>RESULT:</b> Sorry, the password expired and is unrecoverable.<br><br><br>\n\n";
        closeHtml();
        die();
    }
}

?>

<form method="POST">
<p>Please enter a password...</p>
<p><input type="text" name="password" value="<?php echo $randPass ?>" /></p>
<p><input type="submit" name="submit" value="Submit" /></p>
</form>

<?php closeHtml(); ?>
