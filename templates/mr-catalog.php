<!-- <div>Hello</div>
<H1>Hello</H1> -->



<div class="catalog">
<form method="POST">

    <div class="no-gutters pb-4">

        <div class="row">
            <div class="col input-group mb-4 input-group-lg">
                <input type="text" name="opop_search" class="form-control">
            </div>
        </div>
        
        <div class="mb-6 category">
            <?php mif_mr_the_category(); ?>
        </div>
        
        <div class="row">
            <?php mif_mr_the_catalog(); ?>
        </div>

    </div>    

</form>
</div>    


