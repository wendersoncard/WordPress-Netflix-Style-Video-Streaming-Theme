<!-- Preloader -->
<div class="loader-mask">
	
	<div class="loader-wrap">
		<?php if ( get_theme_mod( 'streamium_logo' ) && filter_var(get_theme_mod( 'streamium_logo' ), FILTER_VALIDATE_URL) ) { ?>

		    <img src='<?php echo esc_url( get_theme_mod_ssl( 'streamium_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>

		<?php } ?>
        <div class="loader">
            "Loading..."
        </div>
    </div>
</div>