<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>

<main class="py-5 my-auto">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-12 col-md-4">

                <form id="form-login" method="POST">
                    <h1 class="h3 mb-3 fw-normal">Login</h1>

                    <div id="form-message">
                        <?php if (session()->getFlashdata('message')): ?>
                            <div class="alert alert-warning">
                                <?= session()->getFlashdata('message') ?>
                            </div>                    
                        <?php endif ?>                        
                    </div>

                    <div class="form-floating mb-3">
                        <input name="identity" type="text" class="form-control" id="input-identity" placeholder="foo...">
                        <label for="input-identity">Username / Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="password" type="password" class="form-control" id="input-password" placeholder="Password">
                        <label for="input-password">Password</label>
                    </div>

                    <div class="checkbox mb-3">
                        <label>
                            <input name="remember" type="checkbox" value="remember-me"> Remember me
                        </label>
                    </div>
                    
                    <button class="btn btn-lg btn-outline-dark submit-button" type="submit">Sign in</button>
                </form>

            </div>
        </div>

    </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script>
    $("#form-login").on("submit", function(e){
        e.preventDefault();

        let form = $(this);

        // animation
        $("input", form).prop("readonly",true); 
        $(".submit-button").prop("disabled",true);  
        $(".submit-button",form).html($(".submit-button",form).html() + xsetting.spinner);

        let buttonspinner = $(".button-spinner");   

        $.post(base_url + `/login`, form.serialize() , {}, 'json')
        .done(function(data){

            if (data.status) {

                $("#form-message").html(`<div class="alert alert-info text-break">${data.response}</div>`);

                setTimeout(() => {
                    window.location.href = data.redirect
                },1000);

            }else{

                // animation
                $("input", form).prop("readonly",false);    
                $(".submit-button").prop("disabled",false); 
                buttonspinner.remove();


                $("#form-message").html(`<div class="alert alert-warning text-break">${data.response}</div>`);
            }

        })
        .fail(function(xhr, statusText, errorThrown) {

            alert(xhr.responseText);

            // animation
            $("input", form).prop("readonly",false);    
            $(".submit-button").prop("disabled",false); 
            buttonspinner.remove();
        })        
    })
</script>

<?= $this->endSection() ?>