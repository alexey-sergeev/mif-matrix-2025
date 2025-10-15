<h3 class="pt-4 pb-4">Дисциплины</h3>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="courses container mt-3">

    <!-- <div class="row mt-5 mb-5">
        <div class="col-12 p-0"><a href="?edit">Редактировать</a></div>
    </div> -->
    
    <!-- <div class="row mt-5 mb-5">
        <div class="col-12 p-0"><a href="?new">Создать</a></div>
    </div> -->
    
    <?php mif_mr_the_link_edit(); ?>

    <!-- <div class="row mt-4 bg-primary-subtle border border-primary-subtle fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Списки контроля доступа</div>
        <div class="col-1 p-2"></div>
    </div> -->

    
    <?php mif_mr_show_explanation('courses'); ?>
    <div class="row"><?php mif_mr_the_courses(); ?></div>



    
  
</div>

<?php mif_mr_the_form_end(); ?>

