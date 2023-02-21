<?php
include("../db_connect.php");
?>
<!DOCTYPE html>
<html lang="en" class="has-background-light">
<head>
  <?php
    $title = "PVVMDB - Payments";
    include "../includes/head.php";
  ?>
</head>
<body>
  <?php
    include "../includes/nav.php";
  ?>

  <div class="container box">
    <h1 class="title">Add Payments</h1>
    <div class="columns">
      <div class="column card has-background-info-light">
        <div class="card-content">
          <div class="content">
            <h2 class="subtitle">Import GNUCash</h2>
            <p>Import a series of payments from a GNUCash file.</p>
          </div>
        </div>
        <footer class="card-footer">
          <a href="#" class="card-footer-item">Save</a>
          <a href="#" class="card-footer-item">Edit</a>
          <a href="#" class="card-footer-item">Delete</a>
        </footer>
      </div>

      <div class="column card has-background-warning-light">
        <div class="card-content">
          <div class="content">
            <h2 class="subtitle">Enter manually</h2>
            <p>Fill a submission form to manually register a payment.</p>
          </div>
        </div>
        <footer class="card-footer">
          <a href="#" class="card-footer-item">Save</a>
          <a href="#" class="card-footer-item">Edit</a>
          <a href="#" class="card-footer-item">Delete</a>
        </footer>
      </div>
  </div>

  <div class="container box">
    <h1 class="title">Payments by user</h1>
    <?php if (isset($_GET['username'])): ?>

      <?php
      // TODO: 404 if user not found
      $username = $_GET['username'];
      $userquery = pg_prepare($dbconn, "userquery", "SELECT * FROM users WHERE username = $1");
      $userquery = pg_execute($dbconn, "userquery", array($username));
      $user = pg_fetch_assoc($userquery);

      $mtype_result = pg_query($dbconn, "SELECT * FROM membership_types");
      $mtypes = pg_fetch_all($mtype_result);
      ?>
      <h2 class="subtitle">Membership payments by <?php echo $_GET['username']; ?></h2>

      <table class="table is-fullwidth is-striped">
        <colgroup>
          <col span="1" style="width: 15%;">
          <col span="1" style="width: 15%;">
          <col span="1" style="width: 30%;">
          <col span="1" style="width: 30%;">
          <col span="1" style="width: 10%;">
        </colgroup>
        <thead>
          <tr>
            <th>Payment date</th>
            <th>Amount Paid</th>
            <th>Comment</th>
            <th>Membership type</th>
            <th>Valid periods</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = pg_prepare($dbconn, "membershippurchases", "SELECT membership_purchases.*, membership_types.name AS membership_type_name, membership_types.price FROM membership_purchases LEFT JOIN membership_types ON membership_purchases.membership_type = membership_types.id WHERE membership_purchases.user_id = $1");
          $query = pg_execute($dbconn, "membershippurchases", array($user['id']));

          while ($row = pg_fetch_assoc($query)) {

            echo "<tr>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['amount_paid'] . " kr</td>";
            echo "<td>" . $row['comment'] . "</td>";
            if ($row['membership_type'] == 1) {
              echo "<td class='has-background-success' colspan=2>" . $row['membership_type_name'] . "</td>";
            } else {
              echo "<td>" . $row['membership_type_name'] . "</td>";
              echo "<td>" . round($row['amount_paid'] / $row['price'], 2) . "</td>";
            }
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>


    <h2 class="subtitle">Disk Quota payments by <?php echo $_GET['username']; ?></h2>

    <table class="table is-fullwidth is-striped">
        <colgroup>
          <col span="1" style="width: 15%;">
          <col span="1" style="width: 15%;">
          <col span="1" style="width: 30%;">
          <col span="1" style="width: 30%;">
          <col span="1" style="width: 10%;">
        </colgroup>
      <thead>
        <tr>
          <th>Payment date</th>
          <th>Amount Paid</th>
          <th>Comment</th>
          <th>Price per GiB</th>
          <th>MiB</th>
        </tr>
      </thead>
      <tbody>

    <?php
      $quotaquery = pg_prepare($dbconn, "quotaquery", "SELECT *,(1024*amount_paid/size_mb) AS price_per_gib FROM disk_purchases WHERE user_id = $1");
      $quotaquery = pg_execute($dbconn, "quotaquery", array($user['id']));
      $total_quota = 0;

      while ($row = pg_fetch_assoc($quotaquery)) {
        $total_quota += $row['size_mb'];
        echo "<tr>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td>" . $row['amount_paid'] . "</td>";
        echo "<td>" . $row['comment'] . "</td>";
        echo "<td>" . round($row['price_per_gib'] , 2) . " kr</td>";
        echo "<td>" . round($row['size_mb'] , 2) . "</td>";
        echo "</tr>";
      }
    ?>
      <tr>
        <td colspan=4>Total</td>
        <td><?php echo $total_quota ?> MiB</td>
      </tr>

      </tbody>
    </table>

    <?php else: ?>

    <div class="notification is-info">
      <h1 class="title">Select a user</h1>
      <form method="get" class="columns">
        <div class="column is-10">
        <select name="username" class="input select">
          <?php
            $users = pg_query($dbconn, "SELECT username FROM users");
            while ($user = pg_fetch_result($users, 0)) {
              echo "<option value='$user'>$user</option>";
            }
          ?>
        </select>
        </div>
        <input type="submit" value="Select" class="column is-2 is-primary button py-2 is-fullwidth is-large">
      </form>
    </div>

    <?php endif; ?>


  </div>
</body>
</html>
