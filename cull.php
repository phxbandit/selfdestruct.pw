<?php

// cull.php - removes db entries older than 24 hours

include('includes/config.php');

// debug db connection
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// prepared query
//DEBUG: $sql = "SELECT * FROM hashpass WHERE created < timestampadd(hour, -12, now())";
$sql = "DELETE FROM hashpass WHERE created < timestampadd(hour, -24, now())";
$q = $conn->prepare($sql);
$q->execute();

/*
DEBUG: display results
$result = $q->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
*/

?>
