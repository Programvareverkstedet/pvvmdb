<?php

include('./db_connect.php');


$res = pg_query($dbconn, "SELECT * FROM users");
if (!$res) {
    echo "An error occurred.\n";
    exit;
}

while ($row = pg_fetch_row($res)) {
  echo "id: $row[0]  username: $row[1]";
}

?>
