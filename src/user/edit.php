
<?php

include("../db_connect.php");

if (isset($_GET['username'])) {
  $query = pg_prepare($dbconn, "userid_by_username", 'SELECT id FROM users WHERE username = $1');
  $result = pg_execute($dbconn, "userid_by_username", array($_GET['username']));
  $row = pg_fetch_row($result);
  $userid = $row[0];

  if ($userid) {
    header("Location: /user/edit.php?id=$userid");
  } else {
    header("Location: /user/edit.php?id=-1");
  }
}

$id = $_GET['id'];

$query = pg_prepare($dbconn, "user_by_id", 'SELECT * FROM users WHERE id = $1');
$result = pg_execute($dbconn, "user_by_id", array($id));
$user = pg_fetch_assoc($result);

$update_status = "none";
$update_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $orig_username = $user['username'];

  if (isset($_POST['username'])) $user['username'] = $_POST['username'];
  if (isset($_POST['name'])) $user['name'] = $_POST['name'];
  if (isset($_POST['external_email'])) $user['external_email'] = $_POST['external_email'];
  if (isset($_POST['phone'])) $user['phone'] = $_POST['phone'];
  if (isset($_POST['comment'])) $user['comment'] = $_POST['comment'];
  if (isset($_POST['locked'])) {
    $user['locked'] = $_POST['locked'] == "yes" ? "t" : "f";
  }

  do {
    // TODO: Validate input

    // Check that username is unique
    if ($user['username'] != $orig_username) {
      $query = pg_prepare($dbconn, "user_by_username", 'SELECT id FROM users WHERE username = $1');
      $result = pg_execute($dbconn, "user_by_username", array($user['username']));
      if (pg_num_rows($result) > 0) {
        $update_status = "error";
        $update_message = "Username already exists";
        break;
      }
    }

    $update_query = pg_prepare($dbconn, "update_user", 'UPDATE users SET username = $1, name = $2, external_email = $3, phone = $4, comment = $5, locked = $6 WHERE id = $7');
    $update_result = pg_execute($dbconn, "update_user", array($user['username'], $user['name'], $user['external_email'], $user['phone'], $user['comment'], $user['locked'], $id));

    if (pg_affected_rows($result) > 0) {
      $update_status = "success";
    } else {
      $update_status = "error";
    }
  } while (false);
}

$result = pg_execute($dbconn, "user_by_id", array($id));
$user = pg_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en" class="has-background-light">
<head>
  <?php
    $title = "PVVMDB - User";
    include "../includes/head.php";
  ?>
</head>
<body>
  <?php
    include "../includes/nav.php";
  ?>

  <div class="container box">
    <?php if (isset($_GET['id']) && $user != null): ?>
      <div class="notification is-primary">
        <h1 class="title">Show / edit <?php echo $user['username']; ?></h1>
      </div>

      <form action="" method="POST">

        <label class="label">Username</label>
        <input class="input" type="text" name="username" value="<?php echo $user['username']; ?>">

        <label class="label">Name</label>
        <input class="input" type="text" name="name" value="<?php echo $user['name']; ?>">

        <label class="label">Email</label>
        <input class="input" type="text" name="external_email" value="<?php echo $user['external_email']; ?>">

        <label class="label">Phone</label>
        <input class="input" type="text" name="phone" value="<?php echo $user['phone']; ?>">

        <label class="label">Comment</label>
        <textarea class="textarea" name="comment"><?php echo $user['comment']; ?></textarea>

        <label class="label">Locked</label>
        <input type="hidden" name="locked" value="no">
        <input type="checkbox" name="locked" <?php if ($user['locked']=="t") echo "checked"; ?> value="yes">

        <label class="label">Created at</label>
        <input class="input" type="text" name="created_at" value="<?php echo $user['created_at']; ?>" disabled>

        <hr />

        <div class="columns">
          <input class="button is-primary column m-2" type="submit" value="Save Changes" />

          <!-- <a class="button is-danger column m-2" href="/user.php?id=<?php echo $user['id']; ?>&delete=true">Delete</a> -->
        </div>
      </form>

    <?php else: ?>

      <?php if(isset($_GET['id'])): ?>
        <div class="notification is-warning">
          <h2 class="title">User not found</h2>
          <p>The user you are looking for does not exist.</p>
          <p>Please select a user from the list or enter a username.</p>
          <a href="/user/index.php" class="button is-large is-error is-outlined">Back to user list</a>
        </div>
      <?php else: ?>
        <div class="notification is-info">
          <h2 class="title">No user selected.</h2>
          <p>Please select a user from the list or enter a username.</p>
          <a href="/user/index.php" class="button is-large is-primary">Back to user list</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
</body>
</html>
