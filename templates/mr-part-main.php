<h3 class="pt-4 pb-4">Главная</h3>

<?php // mif_mr_the_form_begin(); ?>
<?php // mif_mr_show_messages() ?>

<div class="main container mt-3 overflow-auto">
    
    <!-- <div class="row mt-5 mb-5">
    
        <?php // mif_mr_the_link_edit(); ?>
        
    </div>   -->
    
    
    <?php // mif_mr_show_explanation('courses'); ?>
    <div class="row">
        
        <div class="col-10 p-0">
            
            <?php mif_mr_the_competencies(); ?>
            
            <div class="container">
                <div class="row">
                    <?php mif_mr_show_panel( 'main' ); ?>
                    <?php mif_mr_the_main(); ?>
                </div>
            </div>


        </div>
        
        <div class="sidebar col-2 pl-5">

            <div class="fiksa">
            
                @    
                <?php echo wp_get_current_user()->user_login ; ?>
                
                <?php if ( ! is_user_logged_in() ) echo '<br><a href="' . wp_login_url(get_permalink()) . '">войти на сайт</a>'; ?>
                
                <?php if ( is_user_logged_in() ) echo '<br><a href="' . wp_logout_url(get_permalink()) . '">выйти</a>'; ?>
            
            </div>

        </div>

    </div>
  
</div>

<?php // mif_mr_the_form_end(); ?>

