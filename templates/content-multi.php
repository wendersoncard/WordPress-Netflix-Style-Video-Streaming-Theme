<main class="cd-main-content video-player-streaming">

<?php
	
	$mainImage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' );
	$mainImage = isset( $mainImage[0] ) ? $mainImage[0] : "http://placehold.it/350x150";
	$streamiumVideoTrailer = get_post_meta( $post->ID, 'streamium_video_trailer_meta_box_text', true );
	$epInd = (isset($_GET['trailer']) && isset($streamiumVideoTrailer)) ? 1 : 0;
	$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);

	if(!empty($episodes)) :

		// Order the list
		$positions = array();
		foreach ($episodes as $key => $row){
		    $positions[$key] = $row['positions'];
		}
		array_multisort($positions, SORT_ASC, $episodes);

		// Sort the seasons
		$result = array();
		foreach ($episodes as $v) {
		    $seasons = $v['seasons'];
		    if (!isset($result[$seasons])) $result[$seasons] = array();
		    $result[$seasons][] = $v;
		}

		$buildNav = "";
		$buildList = '<div class="tab-content">'; 
		foreach (array_reverse($result) as $key => $value) :

			$active = ($key == 0)? "active" : "fade"; 
			$buildNav .= '<li class="streamium-season-filter ' . $active . '"><a data-target="#vtab' . $key . '" data-toggle="tab">Season ' . $value[0]['seasons'] . '</a></li>'; 

			$buildList .= '<div class="tab-pane ' . $active . '" id="vtab' . $key . '">';
			foreach ($value as $key2 => $value2) :

				$thumbnail = !isset($value2['thumbnails']) ? "http://placehold.it/260x146" : esc_url($value2['thumbnails']);
				$title = !isset($value2['titles']) ? "No Title" : esc_html($value2['titles']); 
				$description = !isset($value2['descriptions']) ? "No Description" : esc_html($value2['descriptions']);

				$buildList .= '<div class="media episodes">
					  	<a class="media-left media-top ' . (($epInd === 0) ? "selected" : "") . '" data-id="' . $epInd . '" style="background-image: url(' . $thumbnail . ');">
					  	</a>
					  	<div class="media-body">
					    	<h4 class="media-heading">' . $title . '</h4>
					    	<p>' . $description . '</p>
					  	</div>
					</div>';

			$epInd++; 

			endforeach;

			$buildList .= "</div>"; 

		endforeach;

	endif;

?>

<div id="s3bubble-<?php echo get_the_ID(); ?>" class="program-default-height"></div>

<section class="programs">

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 video-header-archive">
				<h3><?php the_title(); ?></h3>
				<div class="dropdown video-header-archive-dropdown bs-dark">
				  	<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    	FILTER
				    	<span class="caret"></span>
				  	</button>
				  	<ul class="dropdown-menu dropdown-menu-right ">
				 		<?php echo $buildNav; ?>
				  	</ul>
				</div>
			</div><!--/.col-sm-12-->
		</div><!--/.row-->
	</div><!--/.container-->
	<div class="container-fluid">
		<div class="row">
		    <div class="col-md-4 programs-synopis">
		    	<img src="<?php echo esc_url($mainImage); ?>" class="img-responsive" />
		    	<p><?php the_content(); ?></p>
		    	<ul>
					<?php 
						do_action('synopis_multi_meta'); 
					?>
				</ul>
		    </div>
		    <div class="col-md-8">
				<?php echo $buildList; ?>
			</div>
		</div><!--/.row-->
	</div><!--/.container-->
</section><!--/.videos-->