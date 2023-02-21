<nav class="navbar mb-2" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://bulma.io">
      <img src="/assets/logo_black_thicc.png" class="m-0 image is-32x32">
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbar">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbar" class="navbar-menu">
    <div class="navbar-start">
      <a href="/" class="navbar-item">
        Home
      </a>

      <a href="/me.php" class="navbar-item">
        Your Profile
      </a>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Admin
        </a>

        <div class="navbar-dropdown">
          <a href="/user/index.php" class="navbar-item">
            User Details
          </a>
          <a href="/user/import.php" class="navbar-item">
            Import Users
          </a>
          <a href="/payment/index.php" class="navbar-item">
            Payments
          </a>
          <a href="/quota/index.php" class="navbar-item">
            Quota Management
          </a>
          <hr class="navbar-divider">
          <a href="/adminer-4.8.1.php" class="navbar-item">
            Adminer SQL
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a href="/login.php" class="button is-link">
            <?php
              // TODO: Change this to a logout button if the user is logged in
            ?>
            Log in
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>
<script>
// https://bulma.io/documentation/components/navbar/
document.addEventListener('DOMContentLoaded', () => {
  // Get all "navbar-burger" elements
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Add a click event on each of them
  $navbarBurgers.forEach( el => {
    el.addEventListener('click', () => {

      // Get the target from the "data-target" attribute
      const target = el.dataset.target;
      const $target = document.getElementById(target);

      // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
      el.classList.toggle('is-active');
      $target.classList.toggle('is-active');

    });
  });
});
</script>
