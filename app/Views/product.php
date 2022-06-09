<?= $this->extend('_layout') ?>

<?= $this->section('css') ?>

<!-- bootstrap-table -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.20.2/bootstrap-table.min.css" integrity="sha512-HIPiLbxNKmx+x+VFnDHICgl1nbRzW3tzBPvoeX0mq9dWP9H1ZGMRPXfYsHhcJS1na58bzUpbbfpQ/n4SIL7Tlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="py-5">
    <div class="container">

        <div class="card">
            <div class="card-header bg-light">
                <div class="row">
                    <div class="col-auto">
                        <h1 class="fs-4">
                            Data Product
                        </h1>
                    </div>

                    <div class="col">                   
                        <button id="create-product" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus"></i> Create
                        </button>
                    </div>

                </div>
            </div>

            <div class="card-body">

                <div id="toolbar" class="btn-group">
                    <button class="btn btn-danger btn-md" id="delete" disabled><i class="bi bi-trash"></i></button>
                </div>

                <table  
                id="table" 

                data-toolbar="#toolbar"       

                data-search="true"        
                data-show-refresh="true"
                data-show-columns="false"     
                data-minimum-count-columns="2"
                data-show-pagination-switch="false"

                data-detail-view="false"
                data-detail-formatter="detailFormatter"   

                data-pagination="true"
                data-page-list="[10, 25, 50, 100, all]"

                data-click-to-select="false"
                data-id-field="id"    

                data-height="auto"
                data-url="<?= base_url('product/read')  ?>"
                data-side-pagination="server"
                data-response-handler="responseHandler"       
                data-sort-name="id" 
                data-sort-order="desc"
                ></table>

            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<!-- bootstrap-table -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.20.2/bootstrap-table.min.js" integrity="sha512-9KY1w0S1bRPqvsNIxj3XovwFzZ7bOFG8u0K1LByeMVrzYhLu3v7sFRAlwfhBVFsHRiuMc6shv1lRLCTInwqxNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    let $table = $('#table'),
    $delete = $('#delete'),
    selections = []; 

    $table.bootstrapTable('destroy').bootstrapTable({
        responseHandler: function(res) {
            $.each(res.rows, function (i, row) {
                row.state = $.inArray(row.hash, selections) !== -1
            })
            return res
        },
        detailFormatter: function(index, row) {
            var html = []
            $.each(row, function (key, value) {
                html.push('<p><b>' + key + ':</b> ' + value + '</p>')
            })
            return html.join('')
        },
        columns: [
        {
            field: 'state',
            checkbox: true,
            align: 'center',
            valign: 'middle'
        },                 
        {
            title: 'Name',
            field: 'name',
            align: 'left',
            valign: 'middle',
            sortable: true,
        }, 
        {
            title: 'Price',
            field: 'price',
            align: 'left',
            valign: 'middle',
            sortable: true,
        },       
        {
            title: 'Action',
            align: 'right',
            valign: 'middle',
            width: 100,
            formatter: function(value, row, index) {
                var html = '';

                html += `
                <button data-hash="${row.hash}" class='btn btn-primary btn-sm me-1 action-edit'>
                    <i class='bi bi-pencil'></i>
                </button>`;

                html += `
                <button data-name="${row.name}" data-hash="${row.hash}" class='btn btn-danger btn-sm action-delete'>
                    <i class='bi bi-trash'></i>
                </button>`;

                return html;
            }
        }
        ]
    });

    $table.on('load-success.bs.table', function(){

        /**
        * Edit Product
        */
        $(".action-edit").on('click', function(){

            const hash = $(this).data('hash');

            var loading_dialog = bootbox.dialog({
                message: '<p class="text-center mb-0">Reading Data, Please Wait...</p>',
                centerVertical: true,
                closeButton: false,
                size: 'medium',
            }); 

            loading_dialog.init(function(){
                $.post(base_url + `/product/edit`, {hash : hash})
                .done(function(data){

                    loading_dialog.modal('hide');

                    if (data.status) {
                        // call bootbox
                        let form_html = '';
                        form_html += `
                        <form id="form-product" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="form-label text-capitalize">name</label>
                                <input value="${data.response.name}" name="name" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-capitalize">category</label>
                                <input value="${data.response.category}" name="category" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-capitalize">price</label>
                                <input value="${data.response.price}" name="price" type="number" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-capitalize">new photo</label>
                                <input value="${data.response.photo}" name="previous_photo" type="hidden" class="form-control">
                                <input name="photo" type="file" class="form-control">
                            </div>

                            <input value="${data.response.hash}" name="hash" type="hidden" class="form-control">
                            <button type="submit" class="btn btn-outline-primary btn-submit">Submit</button>

                        </form>
                        `;

                        var dialog = bootbox.dialog({
                            title: `Edit Product ${data.response.name}`,
                            message: form_html,
                            centerVertical: true,
                            closeButton: true,
                            size: 'medium',
                            onShown : function(){

                                $("input[name=name]",$("#form-product")).focus();

                                formProduct(dialog, 'update');
                            }
                        });

                    }else{
                        alert(data.response);
                    }
                }).fail(function(xhr, statusText, errorThrown) {   
                    alert(xhr.responseText);
                }); 
            });

        })

        /**
        * Delete Product
        */
        $(".action-delete").on('click', function(){

            const hash = $(this).data('hash'),
            name = $(this).data('name');

            // call bootbox
            let dialog = bootbox.confirm({
                centerVertical: true,
                closeButton: false,
                title: 'Confirm Delete',
                message: `Are you sure want to delete data ${name}`,
                buttons: {
                    confirm: {
                        label: '<i class="bi bi-check"></i> Yes',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: '<i class="bi bi-x"></i> No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {    

                    if (result) {                       

                        // animation
                        $(".bootbox-accept, .bootbox-cancel").prop("disabled",true);    
                        $(".bootbox-accept").html($(".bootbox-accept").html() + xsetting.spinner);  
                        let buttonspinner = $(".button-spinner");         

                        $.post(base_url + `/product/delete`, { hash: hash }, function(data) {}, 'json')
                        .done(function(data){

                            // animation
                            $(".bootbox-accept, .bootbox-cancel").prop("disabled",false);    
                            buttonspinner.remove();

                            if (data.status) {                            
                                $table.bootstrapTable('refresh'); 
                                dialog.modal('hide'); // hide modal after get success response
                            }
                        })
                        .fail(function(xhr, statusText, errorThrown) {
                            alert(xhr.responseText);

                            // animation
                            $(".bootbox-accept, .bootbox-cancel").prop("disabled",false);    
                            buttonspinner.remove();
                        }); 

                        // prevent hide modal
                        return false;                                
                    }
                }
            });
        });   

        // for checkbox
        $table.on('check.bs.table uncheck.bs.table ' + 'check-all.bs.table uncheck-all.bs.table', function () {
            $delete.prop('disabled', !$table.bootstrapTable('getSelections').length);
        });     
    })  

    /**
     * Delete Multiple
     */
     $delete.on('click', function(){

        var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
            return row.hash
        });

        // validate
        if (ids.length < 1) {return false;}

        // convert 
        ids = ids.join("','");

        let dialog = bootbox.confirm({
            centerVertical: true,
            closeButton: false,
            title: `Confirm Batch Delete`,
            message: `Are you sure want to delete selected data`,
            buttons: {
                confirm: {
                    label: '<i class="bi bi-check"></i> Yes',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '<i class="bi bi-x"></i> No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {                
                if (result) {        

                    // animation
                    $(".bootbox-accept, .bootbox-cancel").prop("disabled",true);    
                    $(".bootbox-accept").html($(".bootbox-accept").html() + xsetting.spinner);  
                    let buttonspinner = $(".button-spinner");    

                    $.post(base_url + `/product/delete_batch`, { ids:ids }, function(data) {}, 'json')
                    .done(function(data){

                        // animation
                        $(".bootbox-accept, .bootbox-cancel").prop("disabled",false);    
                        buttonspinner.remove();
                        dialog.modal('hide');

                        if (data.status) {
                            $table.bootstrapTable('refresh'); 
                        }

                    })
                    .fail(function(xhr, statusText, errorThrown) {
                        alert(xhr.responseText);

                        // animation
                        $(".bootbox-accept, .bootbox-cancel").prop("disabled",false);    
                        buttonspinner.remove();                        
                        dialog.modal('hide');
                    });      
                }

                return false;
            }
        });
    }); 
</script>

<script>

/**
* Create Product
*/
$("#create-product").on("click",function(e){

    const button = $(this);

    let form_html = '';
    form_html += `
    <form id="form-product" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label text-capitalize">name</label>
            <input name="name" type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label text-capitalize">category</label>
            <input name="category" type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label text-capitalize">price</label>
            <input name="price" type="number" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label text-capitalize">photo</label>
            <input name="photo" type="file" class="form-control">
        </div>

        <button type="submit" class="btn btn-outline-primary btn-submit">Submit</button>

    </form>
    `;

    var dialog = bootbox.dialog({
        title: `Create New Product`,
        message: form_html,
        centerVertical: true,
        closeButton: true,
        size: 'medium',
        onShown : function(){

            $("input[name=name]",$("#form-product")).focus();

            formProduct(dialog, 'create');
        }
    });
});

function formProduct(dialog,action) {
    let form = $("#form-product");
    form.on("submit",function(e){

        e.preventDefault();

        // animation
        $("input", form).prop("readonly",true); 
        $(".btn-submit").prop("disabled",true); 
        $(".btn-submit").html($(".btn-submit").html() + xsetting.spinner);
        $(".bootbox-close-button").hide();
        let buttonspinner = $(".button-spinner");             

        $.ajax({
            url: base_url + `/product/` + action,
            type: "POST",
            data: new FormData($(this)[0]),
            dataType: "json", 
            mimeTypes:"multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){

                // animation
                $("input", form).prop("readonly",false);    
                $(".btn-submit").prop("disabled",false);
                $(".bootbox-close-button").show();    
                buttonspinner.remove(); 

                if (data.status) {
                    dialog.modal('hide');
                    $table.bootstrapTable('refresh'); 
                }else{
                    alert(data.response);
                }               
            },error: function(xhr, statusText, errorThrown) {
                alert(xhr.responseText);

                // animation
                $("input", form).prop("readonly",false);    
                $(".btn-submit").prop("disabled",false);
                $(".bootbox-close-button").show();    
                buttonspinner.remove();
            }
        });     
    });     
}
</script>

<?= $this->endSection() ?>