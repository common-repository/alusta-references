<?php
/**
 * The template for references listins
 */
get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<div class="references_listing_archive">	
				<div class="container">
					<div class='row'>
						<div class='col-md-12'>
							<h2 class='projec_archive_heading'>
								<?php echo __('Referenssit', 'alusta-references'); ?>
							</h2>
						</div>
					</div>	
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<div class='project_listing_inner'>
						<div class='row'>
							<div class='col-md-12'>
								<div class='projec_box'>
									<div class='row align-items-center'>

										<div class='col-md-7'>
											<div class='proj_desc'>
												<div class='proj_title'><?php echo get_the_title() ?></div>
												<div class='proj_expe'><?php echo get_the_excerpt(); ?></div>
												 <a href='<?php echo get_permalink(); ?>' class='proj_link'><?php echo __('Tutustu tarkemmin', 'alusta-references'); ?></a>
											</div>
										</div>

										<div class='col-md-5'>
											<div class='proj_image'>
												<div class='proj_image_with_desc'>

													<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); ?>
													<img src='<?php echo $featured_img_url; ?>'>
													<ul><li><span><?php echo get_field('location'); ?></span></li></ul>							
												</div>
												<div class='image_bottom_frame'></div>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; // End of the loop. ?>
				</div>
			</div>	
		</div>
	</div>
<?php get_footer(); ?>
