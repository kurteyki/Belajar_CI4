<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>

<main class="py-5 my-auto">
    <div class="container">

        <?php if (!$products): ?>
            <div class="text-center fs-3">
                There are no products to display yet
            </div>
        <?php endif ?>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
            
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card shadow-sm">

                        <img class="bd-placeholder-img card-img-top" src="<?= $product['photo']  ?>">

                        <div class="card-body">

                            <h5 class="card-title">
                                <a class="text-decoration-none" href="#">
                                    <?= $product['name'] ?>
                                </a>
                            </h5>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= $product['price'] ?></small>
                                <span class="badge bg-dark">
                                    <?= $product['category'] ?>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>                
            <?php endforeach ?>
        </div>  
    </div>
</main>

<?= $this->endSection() ?>