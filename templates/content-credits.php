	
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 video-header-archive">
				<h3 class="text-brand"><?php the_title(); ?></h3>
				<div class="dropdown dropdown-filter">
				  	<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    	<?php _e( 'FILTER', 'streamium' ); ?>
				  	</button>
				  	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				  		<a class="dropdown-item tax-search-filter" data-type="all" href="?sort=all"><?php _e( 'View All', 'streamium' ); ?></a>
					    <a class="dropdown-item tax-search-filter" data-type="reviewed" href="?sort=reviewed"><?php _e( 'Most Reviews', 'streamium' ); ?></a>
					    <a class="dropdown-item tax-search-filter" data-type="newest" href="?sort=newest"><?php _e( 'Recently Added', 'streamium' ); ?></a>
					    <a class="dropdown-item tax-search-filter" data-type="oldest" href="?sort=oldest"><?php _e( 'Oldest First', 'streamium' ); ?></a>
				  	</div>
				</div>

			</div>
			<div class="col-12">
				<p><?php the_content(); ?></p>
			</div>
		</div>
	</div>

	<div id="credits" class="carousels-blocks-wrapper"></div>