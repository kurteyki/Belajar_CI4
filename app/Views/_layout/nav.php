<nav class="navbar navbar-expand-md mb-md-4">
  <div class="container-md">
    <button class="d-block d-md-none btn border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-3 text-dark"></i>
    </button>
    <span class="navbar-brand">Store</span>
    
    <div class="d-block d-md-none">
      <button class="btn border-0 px-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-search fs-3 text-dark"></i>
      </button>
      <button class="btn border-0 px-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAuth" aria-controls="navbarAuth" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-box-arrow-in-right fs-3 text-dark"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="<?= base_url()  ?>">Home</a>
        </li>       
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('product')  ?>">Product</a>
        </li>
      </ul>
    </div>

    <div class="collapse navbar-collapse" id="navbarSearch">
      <form id="search-product" class="mx-md-5 w-100">
        <div class="input-group">
          <input name="q" required="" type="search" class="form-control border-end-0" placeholder="Search Product...">
          <button class="btn border px-3 border-start-0" type="submit" aria-label="Search Button">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div>

    <div class="collapse navbar-collapse justify-content-end" id="navbarAuth">
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