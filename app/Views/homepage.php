<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>

<main id="main-page" class="pt-3 pb-5 my-auto">

    <div class="container-md">    
        <div id="main-product">
            <?= view('homepage/product');  ?>
        </div>
    </div>

</main>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script type='text/javascript'>


    function productPlaceholder(){

        let placeholder = '';

        for (var i = 1; i <= 12 ; i++) {
            placeholder += `
            <div class="col">
                <div class="card w-100" aria-hidden="true">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="150px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#83b5ff"></rect></svg>
                    <div class="card-body">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder col-12 bg-primary"></span>
                        </h5>
                        <p class="card-text placeholder-glow d-flex justify-content-between">
                            <span class="placeholder col-4 bg-primary"></span>
                            <span class="placeholder col-4 bg-primary"></span>
                        </p>
                    </div>
                </div>
            </div>`;
        }

        $("#row-product").html(placeholder);
    }


    function PaginationAjax() {

        let click_status = false;
        $('#pagination').on('click','a',function(e){

            // reverse link action
            e.preventDefault(); 

            // get href data
            var href = $(this).attr('href');

            // disable click
            if (click_status) {return false;}
            click_status = true;

            // disable pagination
            $('#pagination li.page-item').addClass('disabled');

            // go to top
            $('html, body').animate({ scrollTop: 0 }, 0);            

            // placeholder
            productPlaceholder();

            $.ajax({
                url: href,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {

                    // change title
                    document.title = data.title;

                    // change url history
                    history.pushState({}, data.title, href);

                    // refresh data
                    $('#main-product').html(data.content);

                    // re-init because its refresh DOM 
                    sortProduct();
                    categoryProduct();
                    PaginationAjax();

                    // enable click
                    click_status = false;
                },error: function(xhr, statusText, errorThrown) {
                    alert(statusText);

                    // enable pagination
                    $('#pagination li.page-item').removeClass('disabled');

                    // enable click
                    click_status = false;                        
                }
            });

        });
    }

    // init for first DOM
    PaginationAjax(); 

    // for search
    function searchProduct(){
        $("#search-product").on("submit", function(e){
            e.preventDefault();

            const form = $(this),
            search  = $("#search-product input[name=q]").val();        
            search_url = (search.length > 0) ? base_url + '?q=' + search : base_url;

            $.ajax({
                url: search_url,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {

                    // change title
                    document.title = data.title;

                    // change url history
                    history.pushState({}, data.title, search_url);

                    // refresh data
                    $('#main-product').html(data.content);

                    // re-init because its refresh DOM 
                    sortProduct();
                    categoryProduct();
                    PaginationAjax();                

                },error: function(xhr, statusText, errorThrown) {
                    alert(statusText);
                }
            });

        }); 

        // nav search key up
        function debounce(callback, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(function () { callback.apply(this, args); }, wait);
            };
        }

        $("#search-product input[name=q]").on("keyup search", debounce(() => {
            $('#search-product').submit();  
        },2000));        
    }

    // init nav search
    searchProduct();   

    function sortProduct(){
        $('#sort-product').on('change', function() {
            const sort = this.value;
            const category  = $('#category-product').val();            
            const search  = $("#search-product input[name=q]").val();        

            // build url
            let build_url;
            if (search.length > 0) {
                build_url = base_url + '?q=' + search +'&sort=' + sort;
            }else if (category.length > 0) {
                build_url = base_url + '?category=' + category +'&sort=' + sort;
            }else{
                build_url = base_url + '?sort=' + sort;
            }

            // placeholder
            productPlaceholder();
            
            $.ajax({
                url: build_url,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {

                    // change title
                    document.title = data.title;

                    // change url history
                    history.pushState({}, data.title, build_url);

                    // refresh data
                    $('#main-product').html(data.content);

                    // re-init because its refresh DOM 
                    sortProduct();
                    categoryProduct();
                    PaginationAjax();

                },error: function(xhr, statusText, errorThrown) {
                    alert(statusText);
                }
            });
        });        
    }

    // init sortProduct
    sortProduct();

    function categoryProduct(){
        $('#category-product').on('change', function() {
            const category = this.value;
            const sort  = $('#sort-product').val();
            const build_url = (category.length > 0) ? base_url + '?category=' + category +'&sort=' + sort : base_url + '?sort=' + sort;

            // placeholder
            productPlaceholder();
            
            $.ajax({
                url: build_url,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {

                    // change title
                    document.title = data.title;

                    // change url history
                    history.pushState({}, data.title, build_url);

                    // refresh data
                    $('#main-product').html(data.content);

                    // re-init because its refresh DOM 
                    sortProduct();
                    categoryProduct();
                    PaginationAjax();

                },error: function(xhr, statusText, errorThrown) {
                    alert(statusText);
                }
            });
        });        
    }

    // init categoryProduct
    categoryProduct();    
</script>

<?= $this->endSection() ?>