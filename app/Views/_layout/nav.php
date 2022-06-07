<nav class="navbar navbar-expand-md mb-4">
  <div class="container">
    <span class="navbar-brand">Store</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="<?= base_url()  ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('product')  ?>">Product</a>
        </li>
      </ul>

      <ul class="navbar-nav mb-2 mb-md-0 d-flex">
        <?php if (!session()->auth): ?>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= base_url('login')  ?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('register')  ?>">Register</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <span class="nav-link">
              Hello <b><?= session('auth')['username']; ?></b>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-danger me-2" aria-current="page" href="<?= base_url('logout')  ?>">Logout</a>
          </li>
        <?php endif ?>
      </ul>

    </div>
  </div>
</nav>