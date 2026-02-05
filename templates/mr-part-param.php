<h3 class="pt-4 pb-4">Параметры ОПОП</h3>


<div class="container">
    <div class="row">
        
        <div class="col-10 p-0">
            





<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="container mt-3">

    <?php // mif_mr_the_link_edit(); ?>

    <!-- <div class="row mt-4 bg-primary-subtle border border-primary-subtle fw-semibold text-center"> -->
    <div class="row mt-4 border bg-body-secondary fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Списки контроля доступа</div>
        <div class="col-1 p-2"></div>
    </div>

    <?php mif_mr_show_explanation('users'); ?>
    
    <div class="row">
        <div class="col-11 bg-light p-2 mt-4 border border-light fw-semibold">Полный доступ (администраторы)</div>
        <div class="col-1 bg-light p-2 mt-4 border border-light fw-semibold text-center text-black-50"><?php mif_mr_the_from_id('admins'); ?></div>
        <?php mif_mr_the_user('admins'); ?>
        <div class="col-11 bg-light p-2 mt-4 border border-light fw-semibold">Просмотр материалов</div>
        <div class="col-1 bg-light p-2 mt-4 border border-light fw-semibold text-center text-black-50"><?php mif_mr_the_from_id('users'); ?></div>
        <?php mif_mr_the_user('users'); ?>
    </div>
    
    <div class="row mt-4 border bg-body-secondary fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Родительские ОПОП</div>
        <div class="col-1 p-2 text-black-50"><?php mif_mr_the_from_id('parents'); ?></div>
    </div>
    
    <?php mif_mr_show_explanation('parents'); ?>
    <div class="row"><?php mif_mr_the_parents(); ?></div>
    
    <div class="row mt-4 border bg-body-secondary fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Параметры для атрибутов</div>
        <div class="col-1 p-2 text-black-50"><?php mif_mr_the_from_id('attributes'); ?></div>
    </div>
    
    <?php mif_mr_show_explanation('attributes'); ?>
    <div class="row"><?php mif_mr_the_text('attributes'); ?></div>
    
    <!-- <div class="row mt-4 bg-primary-subtle border border-primary-subtle fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Справочники</div>
        <div class="col-1 p-2 text-black-50">< ?php mif_mr_the_from_id('references'); ?></div>
    </div>
    
    < ?php mif_mr_show_explanation('references'); ?>
    <div class="row">< ?php mif_mr_the_text('references'); ?></div>
    
    <div class="row mt-4 bg-primary-subtle border border-primary-subtle fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Параметры для метаданных</div>
        <div class="col-1 p-2 text-black-50">< ?php mif_mr_the_from_id('specifications'); ?></div>
    </div>
    
    < ?php mif_mr_show_explanation('specifications'); ?>
    <div class="row">< ?php mif_mr_the_text('specifications'); ?></div> -->

</div>

<?php mif_mr_the_form_end(); ?>



        </div>
                
        <div class="sidebar col-2 pl-5">
            
            <div class="fiksa">

                <div class="container">

                    <?php mif_mr_the_link_edit_easy(); ?>
                    <?php // mif_mr_the_link_edit(); ?>
                    <?php mif_mr_the_link_edit_advanced(); ?>

                </div>
                
            </div>

        </div>
        
    </div>
</div>
