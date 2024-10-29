<?php
/**
 * The Template for displaying all alusta reference posts
 *
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div class="container" role="main">
			<div class="alusta_references_content">
				<?php
				while ( have_posts() ) :
					the_post(); 
				?>
				<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');  ?>
				<?php if($featured_img_url){ ?>
				<div class="a_reference_banner_image">
					<img src="<?php echo $featured_img_url; ?>">
				</div>
				<?php } ?>
				
				<h1 class="a_reference_title"><?php the_title(); ?></h1>
				
				<div class="a_reference_featured_info">
					<ul>
						<li><label><?php _e( 'Asunnon tyyppi', 'alusta-references' ); ?></label><span><?php echo get_field('apartment_type'); ?></span></li>
						<li><label><?php _e( 'Rakennusvuosi', 'alusta-references' ); ?></label><span><?php echo get_field('construction_year'); ?></span></li>
						<li><label><?php _e( 'Remontin tyyppi', 'alusta-references' ); ?></label><span><?php echo get_field('repair_type'); ?></span></li>
						<li><label><?php _e( 'Sijainti', 'alusta-references' ); ?></label><span><?php echo get_field('location'); ?></span></li>
					</ul>
				</div>
				<div class="a_reference_short_desc"><?php the_excerpt(); ?></div>	
				
				<?php if( get_field('why_did_you_decide_to_do_the_renovation')){ ?>
				<div class="a_reference_qas a_reference_qas_1">
					<h2><?php echo get_field('why_did_you_decide_to_do_the_renovation_heading_fie'); ?></h2>
					<?php echo get_field('why_did_you_decide_to_do_the_renovation'); ?>
					<div class="before_images_scr a_reference_slider owl-carousel owl-theme">
					<?php 
						$project_before_image = get_field('project_before_image');
						if($project_before_image){
							foreach($project_before_image as $img){
								if($img){
							?>
								<div class="image_item"><img src="<?php echo $img; ?>"></div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					</div>	
				</div>	
				<?php } ?>
				
				
				<?php if( get_field('on_what_basis_did_you_choose')){ ?>
				<div class="a_reference_qas a_reference_qas_2">
					<h2><?php echo get_field('on_what_basis_did_you_choose_heading_field'); ?></h2>
					<?php echo get_field('on_what_basis_did_you_choose'); ?>
				</div>	
				<?php } ?>
				
				
				<?php if( get_field('how_did_the_renovation_go')){ ?>
				<div class="a_reference_qas a_reference_qas_3">
					<h2><?php echo get_field('how_did_the_renovation_go_heading_field'); ?></h2>
					<?php echo get_field('how_did_the_renovation_go'); ?>
					<div class="during_images_scr a_reference_slider owl-carousel owl-theme">
					<?php 
						$project_during_images = get_field('project_during_images');
						if($project_during_images){
							foreach($project_during_images as $img){
								if($img){
							?>
								<div class="image_item"><img src="<?php echo $img; ?>"></div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					</div>	
				</div>	
				<?php } ?>
				
				
				<?php if( get_field('are_you_satisfied_with_the_outcome')){ ?>
				<div class="a_reference_qas a_reference_qas_4">
					<h2><?php echo get_field('are_you_satisfied_with_the_outcome_heading_field'); ?></h2>
					<?php echo get_field('are_you_satisfied_with_the_outcome'); ?>
					<div class="after_images_scr a_reference_slider owl-carousel owl-theme">
					<?php 
						$project_after_image = get_field('project_after_image');
						if($project_after_image){
							foreach($project_after_image as $img){
								if($img){
							?>
								<div class="image_item"><img src="<?php echo $img; ?>"></div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					</div>	
				</div>	
				<?php } ?>
				
				
				<?php if( get_field('call_to_action_button_text')){ ?>
				<div class="a_reference_call_to_action_btn">
					<a href="<?php echo get_field('call_to_action_button_link'); ?>"><?php echo get_field('call_to_action_button_text'); ?></a>
				</div>	
				<?php } ?>
				

				<?php endwhile; // end of the loop. ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
