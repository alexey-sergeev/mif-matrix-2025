<h3 class="pt-4 pb-4">Дисциплины</h3>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="courses container mt-3 overflow-auto">

    <div class="mt-5 mb-5">
    
        <?php mif_mr_the_link_edit(); ?>
    
    </div>  


    <?php mif_mr_show_explanation('courses'); ?>
    <div class="row">
        
            <?php mif_mr_show_panel( 'courses' ); ?>
            <?php mif_mr_the_courses(); ?>
    
    </div>
  
</div>

<?php mif_mr_the_form_end(); ?>

