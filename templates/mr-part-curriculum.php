<h3 class="pt-4 pb-4">Учебный план</h3>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="curriculum container mt-3 overflow-auto">
    
    <div class="mt-5 mb-5">
    
        <?php mif_mr_the_link_edit(); ?>
    
    </div>  


    <?php mif_mr_show_explanation('curriculum'); ?>

    <div class="row">

        <?php mif_mr_show_panel( 'curriculum' ); ?>    
        <?php mif_mr_the_curriculum(); ?>
    
    </div>
 
</div>

<?php mif_mr_the_form_end(); ?>

