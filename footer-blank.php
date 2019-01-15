	<?php wp_footer(); ?>
	
	<?php if ( !get_theme_mod( 'streamium_global_settings_enable_right_click' ) ) : ?>

		<script type="text/javascript">
		
			document.addEventListener("contextmenu", function(e){
			    e.preventDefault();
			}, false);
			
		</script>

	<?php endif; ?>
	
</body>
</html>