<?php
  include("../db_connect.php");

  if (isset($_GET['username'])) {
    $query = pg_prepare($dbconn, "userid_by_username", 'SELECT id FROM users WHERE username = $1');
    $result = pg_execute($dbconn, "userid_by_username", array($_GET['username']));
    $row = pg_fetch_row($result);
    $userid = $row[0];

    if ($userid) {
      header("Location: /user/index.php?id=$userid");
    } else {
      header("Location: /user/index.php?id=-1");
    }
  }
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
    <div class="notification is-primary">
      <h1 class="title">Search and edit users</h1>
    </div>

    <div class="notification is-info">
      <h2 class="subtitle">Edit user by exact username</h2>
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
    </div>

    <div class="notiifcation is-info is-light mt-2">
      <input class="input" type="text" id="searchInput" placeholder="Search user">
    </div>

    <table class="table is-fullwidth" id="userTable">
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
        //TODO: Search

        $query = "SELECT * FROM users ORDER BY username ASC";
        $result = pg_query($dbconn, $query);
        echo "<p>Fetched " . pg_num_rows($result) . " users</p>";
        while ($row = pg_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td><a href='/user/edit.php?id=" . $row['id'] . "'>" . $row['username'] . "</a></td>";
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . ($row['external_email'] ? $row['external_email'] : ($row['username'] . "@pvv.ntnu.no")) . "</td>";
          echo "<td><a class='button is-primary' href='/user/edit.php?id=" . $row['id'] . "'>Edit</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <?php
    // The site functions entirely without JavaScript, but it's nice to have search without reloading the page
  ?>
  <script src="/js/fuse.js"></script>
  <script src="/js/searchUserTable.js"></script>
</body>
</html>
