
	<main class="cd-main-content">
		
		<div class="container-fluid">
			<div class="row">

				<?php if ( ! is_active_sidebar( 'post-sidebar' ) ) : ?>
					<div class="col-sm-12 col-xs-12">
						
						<h3><?php the_title(); ?></h3>
						<?php the_content(); ?>	

					</div>
				<?php else : ?>
					<div class="col-sm-9 col-xs-12">
						
						<h3><?php the_title(); ?></h3>
						<?php the_content(); ?>	

					</div>
					<div class="col-sm-3 col-xs-12">
						<?php dynamic_sidebar('post-sidebar'); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>

	</main><!--/.main content-->