<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
get_header(); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

	<div id="grid">
		<!-- width of .grid-sizer used for columnWidth -->
 		<div class="grid-sizer"></div>
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

			<div class="grid-item">
					<div class="streamium-plans">
				<?php
					/**
					 * woocommerce_shop_loop hook.
					 *
					 * @hooked WC_Structured_Data::generate_product_data() - 10
					 */
					do_action( 'woocommerce_shop_loop' );
				?>

				<?php wc_get_template_part( 'content', 'product' ); ?>
				</div>
			</div>

			<?php endwhile; // end of the loop. ?>

			</div> <!-- end the row -->

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php
				/**
				 * woocommerce_no_products_found hook.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<script type="text/javascript">

		jQuery(document).ready(function($) {
			var $grid = $('#grid');

		    $grid.isotope({
		      	itemSelector: '.grid-item',
		      	percentPosition: true,
		      	initLayout: false
		    });
		    
		    $grid.on( 'layoutComplete',
			  function( event, laidOutItems ) {

			  	if(laidOutItems.length === 1){
			  		$('.grid-item').css({
			  			position: "relative"
			  		});
			  	}

			    $('.woocommerce span.onsale').fadeIn();
			  }
			);

		    setTimeout(function(){
		    	$('.grid-item').addClass('init');
		    },500);
		   
		    $grid.isotope();

		});

	</script>

<?php get_footer(); ?>
