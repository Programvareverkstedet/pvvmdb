<?php

include_once 'db_config.php';
$dbconn = pg_connect("host=$db_host dbname=$db_name user=$db_user password=$db_pass")
    or die('Could not connect: ' . pg_last_error());

?>
