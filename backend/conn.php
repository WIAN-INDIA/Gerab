<?php
$mysqli = new mysqli("localhost","<dbuser>","<dbpass>","<dbname>");

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

$mysqli -> select_db("<dbname>");

?>