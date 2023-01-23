<?php

include("db_connect.php");

if (isset($_GET['username'])) {
  $query = pg_prepare($dbconn, "userid_by_username", 'SELECT id FROM users WHERE username = $1');
  $result = pg_execute($dbconn, "userid_by_username", array($_GET['username']));
  $row = pg_fetch_row($result);
  $userid = $row[0];

  if ($userid) {
    header("Location: /user.php?id=$userid");
  } else {
    header("Location: /user.php?id=-1");
  }
}

?>

<!DOCTYPE html>
<html lang="en" class="has-background-light">
<head>
  <?php
    $title = "PVVMDB - User";
    include "includes/head.php";
  ?>
</head>
<body>
  <?php
    include "includes/nav.php";
  ?>

  <div class="container box">
    <?php
      if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = pg_prepare($dbconn, "user_by_id", 'SELECT * FROM users WHERE id = $1');
        $query = pg_execute($dbconn, "user_by_id", array($id));

        $user = pg_fetch_assoc($query);
      }
    ?>

    <?php if (isset($_GET['id']) && $user != null): ?>
      <div class="notification is-primary">
        <h1 class="title">Show / edit <?php echo $user['username']; ?></h1>
      </div>

      <form>

        <label class="label">Username</label>
        <input class="input" type="text" name="username" value="<?php echo $user['username']; ?>">

        <label class="label">Name</label>
        <input class="input" type="text" name="name" value="<?php echo $user['name']; ?>">

        <label class="label">Email</label>
        <input class="input" type="text" name="external_email" value="<?php echo $user['external_email']; ?>">

        <label class="label">Phone</label>
        <input class="input" type="text" name="phone" value="<?php echo $user['phone']; ?>">

        <label class="label">Locked</label>
        <input type="checkbox" name="locked" <?php if ($user['locked']=="t") echo "checked"; ?>>

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
        </div>
      <?php else: ?>
        <div class="notification is-info">
          <h2 class="title">No user selected.</h2>
          <p>Please select a user from the list or enter a username.</p>
        </div>
      <?php endif; ?>

      <form method="GET" class="field has-addons my-4">
        <div class="control is-expanded">
          <input class="input" name="username" type="text" placeholder="Find a user">
        </div>
        <div class="control">
          <a class="button is-link is-light">
            Edit user
          </a>
        </div>
      </form>

      <table class="table is-fullwidth">
        <thead>
          <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php
            //TODO: Pagination

            $query = "SELECT * FROM users";
            $result = pg_query($dbconn, $query);
            while ($row = pg_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td><a href='user.php?id=" . $row['id'] . "'>" . $row['username'] . "</a></td>";
              echo "<td>" . $row['name'] . "</td>";
              echo "<td>" . $row['external_email'] . "</td>";
              echo "<td><a class='button is-primary' href='user.php?id=" . $row['id'] . "'>Edit</a></td>";
              echo "</tr>";
            }
          ?>
        </tbody>

    <?php endif; ?>



</body>
</html>
