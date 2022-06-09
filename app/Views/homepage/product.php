<?php if (!$products): ?>
    <?php if ($search): ?>
        <div class="text-center fs-3">
            no product found with keyword : <?= $search  ?>
        </div>                
    <?php else: ?>
        <div class="text-center fs-3">
            There are no products to display yet
        </div>
    <?php endif ?>
<?php endif ?>

<?php if ($products): ?>
    <div id="row-product-title" class="row align-items-center mb-3">
        <div class="col-12 col-md">
            <?php if (!$products): ?>
                <?php if ($search): ?>
                    <h2 class="fs-4">
                        no product found with keyword : <?= $search  ?>
                    </h2>                
                <?php else: ?>
                    <h2 class="fs-4">
                        There are no products to display yet
                    </h2>
                <?php endif ?>
            <?php else: ?>
                <?php if ($search): ?>
                    <h2 class="fs-4 mb-3">
                        Search Product : <?= $search  ?>
                    </h2>
                <?php elseif($category): ?>
                    <h2 class="fs-4 mb-3">
                        Category : <?= $category  ?>
                    </h2>
                <?php else: ?>
                    <h2 class="fs-4">
                        Display All Product
                    </h2>
                <?php endif ?>
            <?php endif ?>
        </div> 
        <div class="col-12 col-md-auto">
            <div class="d-flex">
                <select id="category-product" name="category" class="form-select me-2">
                    <option value="" selected="">
                        All Category
                    </option>
                    <?php foreach ($categories as $filter): ?>
                        <option value="<?= $filter  ?>" <?= $category == $filter ? 'selected' : '' ?>>
                            <?= $filter  ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <select id="sort-product" name="sort" class="form-select">
                    <option value="latest" <?= $sort == 'latest' ? 'selected' : '' ?>>
                        Latest
                    </option>
                    <option value="oldest" <?= $sort == 'oldest' ? 'selected' : '' ?>>
                        Oldeset
                    </option>                        
                    <option value="high-price" <?= $sort == 'high-price' ? 'selected' : '' ?>>
                        High Price
                    </option>
                    <option value="low-price" <?= $sort == 'low-price' ? 'selected' : '' ?>>
                        Low Price
                    </option>
                </select>
            </div>
        </div>
    </div>         
<?php endif ?>  

<div id="row-product" class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4  g-3">

    <?php foreach ($products as $product): ?>
        <!-- make card same height with d-flex align-items-stretch -->
        <div class="col d-flex align-items-stretch">
            <div class="card w-100 shadow-sm">

                <div style="position: relative;">
                    <img class="bd-placeholder-img card-img-top" src="<?= $product['photo']  ?>">
                    <?php if (!$category): ?>
                        <span class="badge bg-primary" style="position: absolute;right: 5px;top: 10px;">
                            <i class="bi bi-hash" style="font-size:10px"></i>
                            <?= $product['category'] ?>
                        </span>
                    <?php endif ?>
                </div>

                <!-- make card same height with d-flex flex-column -->
                <div class="card-body d-flex flex-column">

                    <h5 class="card-title pb-2">
                        <a class="text-decoration-none" href="#">
                            <?= $product['name'] ?>
                        </a>
                    </h5>

                    <!-- make this element always on bottom when height is not same -->
                    <div class="d-flex justify-content-md-between flex-column flex-md-row align-items-start mb-1 mt-auto">
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
<div class="row">
    <div class="col-12 text-center">
        <?php if ($products): ?>        
            <?= $pager->links('product', 'bootstrap') ?>
        <?php endif ?>
    </div>
</div>