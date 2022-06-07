<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>

<main class="py-5 my-auto">
    <div class="container">

        <?php if (!$products): ?>
            <div class="text-center fs-3">
                There are no products to display yet
            </div>
        <?php endif ?>

        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-3">

            <?php foreach ($products as $product): ?>
                <!-- make card same height with d-flex align-items-stretch -->
                <div class="col d-flex align-items-stretch">
                    <div class="card shadow-sm">

                        <div style="position: relative;">
                            <img class="bd-placeholder-img card-img-top" src="<?= $product['photo']  ?>">
                            <span class="badge bg-primary" style="position: absolute;right: 5px;top: 10px;">
                                <i class="bi bi-hash" style="font-size:10px"></i>
                                <?= $product['category'] ?>
                            </span>
                        </div>

                        <!-- make card same height with d-flex flex-column -->
                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title pb-2">
                                <a class="text-decoration-none" href="#">
                                    <?= $product['name'] ?>
                                </a>
                            </h5>

                            <!-- make this element always on bottom when height is not same -->
                            <div class="d-flex justify-content-between align-items-center mb-1 mt-auto">
                                <small class="text-muted"><?= $product['price'] ?></small>
                                <div>
                                    <i class="bi bi-person-circle"></i> <?= $product['owner']  ?>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>                
            <?php endforeach ?>
        </div>  
    </div>
</main>

<?= $this->endSection() ?>