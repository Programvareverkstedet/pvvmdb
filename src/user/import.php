<?php

include("../db_connect.php");

$upload_state = "none";
$users_added = 0;
$users_updated = 0;
$users_skipped = 0;

# Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($_FILES['passwdfile']['error'] == UPLOAD_ERR_OK               //checks for errors
        && is_uploaded_file($_FILES['passwdfile']['tmp_name'])) { //checks that file is uploaded
    $filedata = file_get_contents($_FILES['passwdfile']['tmp_name']);

    $query_select = pg_prepare($dbconn, "select_user_by_username", 'SELECT * FROM users WHERE username = $1');
    $query_insert = pg_prepare($dbconn, "insert_user", 'INSERT INTO users (username, name, comment) VALUES ($1, $2, $3)');
    $query_update = pg_prepare($dbconn, "update_user", 'UPDATE users SET name = $1, comment = $2 WHERE username = $3');


    # For every line in the file
    foreach (explode("\n", $filedata) as $line) {
      # Split the line into username and password
      $line = explode(":", $line);

      # Check that the line is valid length (7 colon separated fields)
      if (count($line) != 7) {
        $users_skipped++;
        continue;
      }

      $username = $line[0];
      $userid = $line[2];
      $name = $line[4];
      $shell = $line[6];

      # If the name contains a comma, split it into name and comment
      $comma_pos = strpos($name, ",");
      if ($comma_pos !== false) {
        $comment = substr($name, $comma_pos + 1);
        $name = substr($name, 0, $comma_pos);
      } else {
        $comment = "";
      }

      # System users with uids under 1000 are skipped
      if ($userid < 1000) {
        $users_skipped++;
        continue;
      }

      # Users with nologin or /bin/false shells are skipped
      if ($shell == "/usr/sbin/nologin" || $shell == "/bin/false") {
        $users_skipped++;
        continue;
      }

      # Check if the user already exists
      # If they do, update their name
      # If they don't, create them

      $result = pg_execute($dbconn, "select_user_by_username", array($username));

      if (pg_num_rows($result) > 0) {
        $result = pg_execute($dbconn, "update_user", array($name, $comment, $username));

        if (pg_affected_rows($result) > 0) {
          $users_updated++;
        } else {
          $users_skipped++;
        }
      } else {
        $result = pg_execute($dbconn, "insert_user", array($username, $name, $comment));

        if (pg_affected_rows($result) > 0) {
          $users_added++;
        } else {
          $users_skipped++;
        }
      }
    }

    $upload_state = "success";
  } else {
    $upload_state = "error";
  }
}

?>

<!DOCTYPE html>
<html lang="en" class="has-background-light">
<head>
  <?php
    $title = "PVVMDB - Import User";
    include "../includes/head.php";
  ?>
</head>
<body>
  <?php
    include "../includes/nav.php";
  ?>

  <div class="container box">
      <?php if ($upload_state == "success"): ?>
        <div class="notification is-success">
          <h1 class="title">Import successful</h1>
          <p><?php echo "$users_added users added, $users_updated users updated, $users_skipped users skipped" ?></p>
        </div>
      <?php elseif ($upload_state == "error"): ?>
        <div class="notification is-danger">
          <h1 class="title">Import failed</h1>
          <p>There was an error uploading the file</p>
        </div>
      <?php endif; ?>

      <div class="notification is-info">
        <h1 class="title">Import users</h1>
        <h3 class="subtitle">Import users by uploading a passwd file</h3>
      </div>

      <div class="content">
        <p class="is-size-5">
          Load new users by importing the file found at /etc/passwd.
          <br>
          Duplicate usernames will be detected and their names will be updated.
          <br>
          System users with uids under 1000 and users with nologin or /bin/false shells will automatically be skipped.
        </p>
      </div>

      <form action="<?php $_PHP_SELF ?>" method="POST" enctype="multipart/form-data">
        <div class="file mb-4">
          <label class="file-label">
            <input class="file-input" type="file" name="passwdfile">
            <span class="file-cta">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Choose a file…
              </span>
            </span>
          </label>
        </div>

        <div class="columns mx-2">
          <button class="button column is-fullwidth mx-2 is-primary">Submit</button>
          <a class="button column is-fullwidth mx-2 is-warning" href="/user/index.php">Return to user list</a>
        </div>
      </form>
  </div>
</body>
</html>
