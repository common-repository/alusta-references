<?php
/*
Plugin Name: Alusta References
Description: Reference gathering made super easy in a way that supports sales, marketing and business in the most efficient way.
Version: 1.0
Author: Alustatalo
Author URI: https://alustatalo.com
Text Domain: alusta-references
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class ALUSTAR_alusta_references_feedback {
	
	private static $instance = null;
	private $version;
	private $plugin_dir;
	private $plugin_url;
	
	
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		
		$this->version = '1.0';
		$this->plugin_dir = plugin_dir_path(__FILE__);
		$this->plugin_url = plugin_dir_url(__FILE__);
		$this->depth_limit = 1;
	
		add_action( 'admin_menu', array($this, 'alustar_admin_menu') );
		add_action( 'init', array($this, 'alustar_load_textdomain') ); 
		add_action( 'admin_init', array($this, 'alustar_ref_register_settings') );
		add_action( 'wp_enqueue_scripts', array($this, 'alustar_referenes_scripts') );
		add_action( 'init', array($this, 'alustar_create_references_posttype') );
		add_filter('single_template', array($this, 'alustar_posttype_template') );
		add_filter( 'archive_template', array($this, 'alustar_posttype_archive_template') ) ;
		add_shortcode("alusta_references_form", array($this, 'alustar_ref_form_shortcode') );
		add_action( 'init', array($this, 'alustar_posttype_template_save_form_fields') );
		add_shortcode("alusta_work_listing", array($this, 'alustar_work_listing_shortcode') );
		
		
	}
	
	
	//Add plugin admin menu.
	public function alustar_admin_menu() {
		add_menu_page("Alusta References Options", "Alusta References Options", 10, "alusta_ap_page", array( $this, "alustar_ref_options") );
	}
	
	
	public function alustar_ref_options(){
		include $this->plugin_dir . '/alusta_ref_options.php';
	}
	
	
	//Load plugin textdomain.
	public function alustar_load_textdomain() {
		load_plugin_textdomain( 'alusta-references', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	
	
	//register settings
	public function alustar_ref_register_settings() {
		register_setting( 'register_alusta_ref_settings-group', 'facebook_link' );
		register_setting( 'register_alusta_ref_settings-group', 'google_business_link' );
	}
	
	
	//Enqueue scripts and styles.
	public function alustar_referenes_scripts() {
		wp_enqueue_style( 'alusta-ref-carousel-style', $this->plugin_url . 'css/owl.carousel.min.css' );
		wp_enqueue_style( 'alusta-ref-carousel-default-style', $this->plugin_url . 'css/owl.theme.default.min.css' );
		wp_enqueue_style( 'alusta-ref-style', $this->plugin_url . 'css/style.css' );
		wp_enqueue_script ( 'alusta-ref-year-script', $this->plugin_url. 'js/yearpicker.js', array('jquery'), $this->version );
		wp_enqueue_script ( 'alusta-ref-carousel-script', $this->plugin_url . 'js/owl.carousel.min.js', array('jquery'), $this->version );
		wp_enqueue_script ( 'alusta-ref-script', $this->plugin_url . 'js/custom.js', array('jquery'), $this->version );
	}
	
	
	// alusta references post type function
	public function alustar_create_references_posttype() {
		register_post_type( 'alusta-references',
		// CPT Options
			array(
				'labels' => array(
					'name' => __( 'References' ),
					'singular_name' => __( 'Reference', 'alusta-references' )
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'references', 'alusta-references'),
				'show_in_rest' => true,
					'supports' => array(
					'title', 
					'editor', 
					'excerpt', 
					'custom-fields', 
					'thumbnail',
					'page-attributes'
				),
			)
		);
	}
	
	
	/* Filter the single_template with our custom function*/
	public function alustar_posttype_template($single) {
		global $post;
		/* Checks for single template by post type */
		if ( $post->post_type == 'alusta-references' ) {
			if ( file_exists( $this->plugin_dir . 'single-alusta-references.php' ) ) {
				return $this->plugin_dir . 'single-alusta-references.php';
			}
		}
		return $single;
	}


	/* Filter the archive_template with our custom function*/
	public function alustar_posttype_archive_template( $archive_template ) {
		 global $post;

		 if ( is_post_type_archive ( 'alusta-references' ) ) {
			 if ( file_exists( $this->plugin_dir . 'archive-alusta-references.php' ) ) {
				$archive_template = $this->plugin_dir . '/archive-alusta-references.php';
			 }
		 }
		 return $archive_template;
	}
	
	
	
	// Reference Form Shortcode
	public function alustar_ref_form_shortcode($atts){
		extract(shortcode_atts( array("lang"=>'fi'),$atts ) );
	
		$form_listing .= "<div class='al_ref_outer'>";
			if(isset($_POST['alus_form_sbt_btn'])){ $form_listing .= "<div class='save_msg'>".esc_html(__('Kiitos tiedoista!', 'alusta-references'))."</div>"; }
			$form_listing .= "<form class='al_ref_form' name='al_ref_form' method='post' enctype='multipart/form-data' action='".esc_url( get_page_link() )."'>";
				$form_listing .= "<div class='al_ref_row al_ref_row_heading'>".esc_html(__('Kuvia projektista', 'alusta-references'))."</div>";
				$form_listing .= "<div class='al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Ennen', 'alusta-references'))."</label>";
					$form_listing .= "<input type='file' name='alus_before_img[]' class='alus_input_f alus_input_file' multiple>";
				$form_listing .= "</div>";
				$form_listing .= "<div class='al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Aikana', 'alusta-references'))."</label>";
					$form_listing .= "<input type='file' name='alus_during_img[]' class='alus_input_f alus_input_file' multiple>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Jälkeen', 'alusta-references'))."</label>";
					$form_listing .= "<input type='file' name='alus_after_img[]' class='alus_input_f alus_input_file' multiple>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row al_ref_row_heading'>".esc_html(__('Perustiedot projektista', 'alusta-references'))."</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Etunimi', 'alusta-references'))."</label>";
					$form_listing .= "<input type='text' name='alus_first_name' class='alus_input_f' required>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Sukunimi', 'alusta-references'))."</label>";
					$form_listing .= "<input type='text' name='alus_last_name' class='alus_input_f' required>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Asunnon tyyppi', 'alusta-references'))."</label>";
					$form_listing .= "<select name='alus_app_type' class='alus_input_f'>
<option value='".esc_html(__('Rivitalo', 'alusta-references'))."'>".esc_html(__('Rivitalo', 'alusta-references'))."</option><option value='".esc_html(__('Omakotitalo', 'alusta-references'))."'>".esc_html(__('Omakotitalo', 'alusta-references'))."</option><option value='".esc_html(__('Paritalo'))."'>".esc_html(__('Paritalo', 'alusta-references'))."</option><option value='".esc_html(__('Kerrostalo', 'alusta-references'))."'>".esc_html(__('Kerrostalo', 'alusta-references'))."</option></select>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Rakennusvuosi', 'alusta-references'))."</label>";
					$form_listing .= "<input type='text' name='alus_cons_year' id='alus_cons_year' class='alus_input_f' required>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Sijainti', 'alusta-references'))."</label>";
					$form_listing .= "<input type='text' name='alus_location' class='alus_input_f' required>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Remontin tyyppi', 'alusta-references'))."</label>";
					$form_listing .= "<select name='alus_repair_type' class='alus_input_f'>
<option value='".esc_html(__('Kattoremontti', 'alusta-references'))."'>".esc_html(__('Kattoremontti', 'alusta-references'))."</option><option value='".esc_html(__('Ikkunaremontti', 'alusta-references'))."'>".esc_html(__('Ikkunaremontti'))."</option><option value='".esc_html(__('Ulkovuoriremontti', 'alusta-references'))."'>".esc_html(__('Ulkovuoriremontti', 'alusta-references'))."</option><option value='".esc_html(__('Maalausremontti', 'alusta-references'))."'>".esc_html(__('Maalausremontti', 'alusta-references'))."</option><option value='".esc_html(__('Putkiremontti', 'alusta-references'))."'>".esc_html(__('Putkiremontti', 'alusta-references'))."</option><option value='".esc_html(__('Lämmitysremontti', 'alusta-references'))."'>".esc_html(__('Lämmitysremontti', 'alusta-references'))."</option><option value='".esc_html(__('Salaojaremontti', 'alusta-references'))."'>".esc_html(__('Salaojaremontti', 'alusta-references'))."</option><option value='".esc_html(__('Sisäremontti', 'alusta-references'))."'>".esc_html(__('Sisäremontti', 'alusta-references'))."</option></select>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row al_ref_row_heading'>".esc_html(__('Kerro tarkemmin remontista', 'alusta-references'))."</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Miksi päätitte tehdä remontin?', 'alusta-references'))."</label>";
					$form_listing .= "<textarea name='alus_why_reno' class='alus_textarea_f'></textarea>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Millä perusteella valitsitte?', 'alusta-references'))."</label>";
					$form_listing .= "<textarea name='alus_what_basis_choose' class='alus_textarea_f'></textarea>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Miten remontti meni?', 'alusta-references'))."</label>";
					$form_listing .= "<textarea name='alus_how_reno_go' class='alus_textarea_f'></textarea>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<label>".esc_html(__('Oletteko tyytyväisiä lopputulokseen?', 'alusta-references'))."</label>";
					$form_listing .= "<textarea name='alus_are_you_satisfied' class='alus_textarea_f'></textarea>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row al_ref_rdio_row'>";
					$form_listing .= "<label>".esc_html(__('Voisitteko antaa työstä julkisen arvion?', 'alusta-references'))."</label>";
					$form_listing .= "<span><a href='".esc_html( get_option( 'facebook_link' ) )."' target='_blank'><img src='".plugin_dir_url( __FILE__ )."images/fb.png'></a></span>";
					$form_listing .= "<span><a href='".esc_html( get_option( 'google_business_link' ) )."' target='_blank'><img src='".plugin_dir_url( __FILE__ )."images/gbusiness.png'></a></span>";
				$form_listing .= "</div>";
				$form_listing .= "<div class=' al_ref_row'>";
					$form_listing .= "<input type='submit' name='alus_form_sbt_btn' class='alus_form_sbt' value='".esc_html(__('Lähetä', 'alusta-references'))."'>";
				$form_listing .= "</div>";
			$form_listing .= "</form>";
		$form_listing .= "</div>";
	
		return $form_listing;
	
	}
	
	
	public function alusta_image_upload( $files = array(), $post_id, $field_key){
	if ( !function_exists('wp_handle_upload') ) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}
	
		$images_count = 1;
		foreach ( $files['name'] as $key => $value ) {
			if ( $files['name'][ $key ] ) {	
				
				$file = array(
					'name' => sanitize_file_name($files['name'][ $key ]),
					'type' => $files['type'][ $key ],
					'tmp_name' => $files['tmp_name'][ $key ],
					'error' => $files['error'][ $key ],
					'size' => $files['size'][ $key ]
				);
				
				// Move file to media library
				$before_img_movefile = wp_handle_upload( $file, array('test_form' => false) );
				
				// If move was successful, insert WordPress attachment
				if ( $before_img_movefile && !isset($before_img_movefile['error']) ) {
					$wp_upload_dir = wp_upload_dir();
					$attachment = array(
						'guid' => $wp_upload_dir['url'] . '/' . basename($before_img_movefile['file']),
						'post_mime_type' => $before_img_movefile['type'],
						'post_title' => preg_replace( '/\.[^.]+$/', "", basename($before_img_movefile['file']) ),
						'post_content' => "",
						'post_status' => 'inherit'
					);
					$before_img_attach_id = wp_insert_attachment($attachment, $before_img_movefile['file']);
				}
			}
			update_field($field_key, array( 'image_'.$images_count => $before_img_attach_id ), $post_id);
			//return $before_img_attach_id;
			$images_count++;
		}
	}
	
	
	public function alustar_posttype_template_save_form_fields() {
	
		if(isset($_POST['alus_form_sbt_btn'])){

			$alus_first_name 		= sanitize_text_field($_POST['alus_first_name']);
			$alus_last_name 		= sanitize_text_field($_POST['alus_last_name']);
			$alus_app_type 			= sanitize_text_field($_POST['alus_app_type']);
			$alus_cons_year 		= sanitize_text_field($_POST['alus_cons_year']);
			$alus_location 			= sanitize_text_field($_POST['alus_location']);
			$alus_repair_type 		= sanitize_text_field($_POST['alus_repair_type']);
			$alus_why_reno 			= sanitize_textarea_field($_POST['alus_why_reno']);
			$alus_what_basis_choose = sanitize_textarea_field($_POST['alus_what_basis_choose']);
			$alus_how_reno_go 		= sanitize_textarea_field($_POST['alus_how_reno_go']);
			$alus_are_you_satisfied = sanitize_textarea_field($_POST['alus_are_you_satisfied']);
			$post_title = $alus_app_type.' '.$alus_last_name;

			// Gather post data.
			$post_args = array(
				'post_title'    => $post_title,
				'post_content'  => '',
				'post_status'   => 'draft',
				'post_type' => 'alusta-references', 
			);

			// Insert the post into the database.
			$post_id = wp_insert_post( $post_args );

			if($_FILES['alus_before_img']['name']){
				$this->alusta_image_upload( $_FILES['alus_before_img'], $post_id, 'project_before_image');
			}
			if($_FILES['alus_during_img']['name']){
				$this->alusta_image_upload( $_FILES['alus_during_img'], $post_id, 'project_during_images');
			}
			if($_FILES['alus_before_img']['name']){
				$this->alusta_image_upload( $_FILES['alus_after_img'], $post_id, 'project_after_image');
			}

			update_field('first_name', $alus_first_name, $post_id);
			update_field('last_name', $alus_last_name, $post_id);
			update_field('apartment_type', $alus_app_type, $post_id);
			update_field('construction_year', $alus_cons_year, $post_id);
			update_field('location', $alus_location, $post_id);
			update_field('repair_type', $alus_repair_type, $post_id);
			update_field('why_did_you_decide_to_do_the_renovation', $alus_why_reno, $post_id);
			update_field('on_what_basis_did_you_choose', $alus_what_basis_choose, $post_id);
			update_field('how_did_the_renovation_go', $alus_how_reno_go, $post_id);
			update_field('are_you_satisfied_with_the_outcome', $alus_are_you_satisfied, $post_id);
		}
	}
	
	
	
	public function alustar_work_listing_shortcode($atts){
		extract(shortcode_atts( array("number"=>12, "project_link"=>'yes', "button_text"=> __('Näytä kaikki', 'alusta-references'), "button_link"=> "" ),$atts ) );

		$args = array(
			'post_type' => array('alusta-references'), 
			'posts_per_page' => $number,
			//'orderby' => 'id',
			//'order'   => 'DESC',
		);

		// the query
		$pro_count = 1;
		$proj_query = new WP_Query( $args );
		$project_listing = "";

		if ( $proj_query->have_posts() ) :
			$project_listing .= "<div class='project_listing_outer'>";
				while ( $proj_query->have_posts() ) : $proj_query->the_post();
					$order_class = '';	
					//if( ($pro_count % 2) == 0) { $order_class = 'order-md-2'; }

					$project_listing .= "<div class='project_listing_inner'>";
						$project_listing .= "<div class='row'>";
							$project_listing .= "<div class='col-md-12'>";
								$project_listing .= "<div class='projec_box'>";
									$project_listing .= "<div class='row align-items-center'>";

										$project_listing .= "<div class='col-md-7'>";
											$project_listing .= "<div class='proj_desc'>
												<div class='proj_title'>".get_the_title()."</div>
												<div class='proj_expe'>".get_the_excerpt()."</div>";
												 $project_listing .= "<a href='".get_permalink()."' class='proj_link'>".__('Tutustu tarkemmin', 'alusta-references')."</a>";
											$project_listing .= "</div>";
										$project_listing .= "</div>";

										$project_listing .= "<div class='col-md-5'>";
											$project_listing .= "<div class='proj_image'>";
												$project_listing .= "<div class='proj_image_with_desc'>";
													//@$image = get_field('project_after_image')['image_1'];
													$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
													$project_listing .= "<img src='".$featured_img_url."'>";
													$project_listing .="<ul><li><span>".get_field('location')."</span></li></ul>";							
												$project_listing .=	"</div>";
												$project_listing .=	"<div class='image_bottom_frame'></div>";
											$project_listing .=	"</div>";
										$project_listing .= "</div>";

									$project_listing .= "</div>";
								$project_listing .= "</div>";
							$project_listing .= "</div>";
						$project_listing .= "</div>";
					$project_listing .= "</div>";
				$pro_count ++ ;
				endwhile;
				if($button_text){
					$project_listing .= "<a href='".$button_link."' class='more_news'>".$button_text."</a>";
				}
			$project_listing .= "</div>";
			endif;
		wp_reset_postdata();

		return $project_listing;

	}
	
	
}
add_action( 'plugins_loaded', array( 'ALUSTAR_alusta_references_feedback', 'get_instance' ) );







/**
	Create ACF Field Group
**/	
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5f337a268c1d4',
	'title' => 'References Options',
	'fields' => array(
		array(
			'key' => 'field_5f33c04190031',
			'label' => 'First Name',
			'name' => 'first_name',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c05590032',
			'label' => 'Last Name',
			'name' => 'last_name',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c07090033',
			'label' => 'Apartment Type',
			'name' => 'apartment_type',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c07b90034',
			'label' => 'Construction Year',
			'name' => 'construction_year',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c09990035',
			'label' => 'Location',
			'name' => 'location',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c0a990036',
			'label' => 'Repair Type',
			'name' => 'repair_type',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Kattoremontti' => 'Kattoremontti',
				'Ikkunaremontti' => 'Ikkunaremontti',
				'Ulkovuoriremontti' => 'Ulkovuoriremontti',
				'Maalausremontti' => 'Maalausremontti',
				'Putkiremontti' => 'Putkiremontti',
				'Lämmitysremontti' => 'Lämmitysremontti',
				'Salaojaremontti' => 'Salaojaremontti',
				'Sisäremontti' => 'Sisäremontti',
			),
			'default_value' => false,
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5f3d2712fbd80',
			'label' => 'Why did you decide to do the renovation? (Heading Field)',
			'name' => 'why_did_you_decide_to_do_the_renovation_heading_field',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Why did you decide to do the renovation?',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c0d790037',
			'label' => 'Why did you decide to do the renovation?',
			'name' => 'why_did_you_decide_to_do_the_renovation',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 3,

			'new_lines' => 'br',
		),
		array(
			'key' => 'field_5f3d27abcc9f2',
			'label' => 'On what basis did you choose? (Heading Field)',
			'name' => 'on_what_basis_did_you_choose_heading_field',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'On what basis did you choose?',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c10790038',
			'label' => 'On what basis did you choose?',
			'name' => 'on_what_basis_did_you_choose',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 3,
			'new_lines' => 'br',
		),
		array(
			'key' => 'field_5f3d27d3cc9f3',
			'label' => 'How did the renovation go? (Heading Field)',
			'name' => 'how_did_the_renovation_go_heading_field',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'How did the renovation go?',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33c11990039',
			'label' => 'How did the renovation go?',
			'name' => 'how_did_the_renovation_go',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 3,
			'new_lines' => 'br',
		),
		array(
			'key' => 'field_5f33c1279003a',
			'label' => 'Are you satisfied with the outcome?',
			'name' => 'are_you_satisfied_with_the_outcome',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 3,
			'new_lines' => 'br',
		),
		array(
			'key' => 'field_5f3d280acc9f4',
			'label' => 'Are you satisfied with the outcome? (Heading Field)',
			'name' => 'are_you_satisfied_with_the_outcome_heading_field',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Are you satisfied with the outcome?',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f33d21ba4a71',
			'label' => 'Project Before Image',
			'name' => 'project_before_image',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '33',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_5f3be6b162085',
					'label' => 'Image 1',
					'name' => 'image_1',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed450b140',
					'label' => 'Image 2',
					'name' => 'image_2',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed4a0b141',
					'label' => 'Image 3',
					'name' => 'image_3',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed4f0b142',
					'label' => 'Image 4',
					'name' => 'image_4',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed510b143',
					'label' => 'Image 5',
					'name' => 'image_5',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed530b144',
					'label' => 'Image 6',
					'name' => 'image_6',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed640b145',
					'label' => 'Image 7',
					'name' => 'image_7',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed670b146',
					'label' => 'Image 8',
					'name' => 'image_8',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed690b147',
					'label' => 'Image 9',
					'name' => 'image_9',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
		),
		array(
			'key' => 'field_5f3bee05b11c8',
			'label' => 'Project During Images',
			'name' => 'project_during_images',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '33',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_5f3bee05b11c9',
					'label' => 'Image 1',
					'name' => 'image_1',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11ca',
					'label' => 'Image 2',
					'name' => 'image_2',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11cb',
					'label' => 'Image 3',
					'name' => 'image_3',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11cc',
					'label' => 'Image 4',
					'name' => 'image_4',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11cd',
					'label' => 'Image 5',
					'name' => 'image_5',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11ce',
					'label' => 'Image 6',
					'name' => 'image_6',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11cf',
					'label' => 'Image 7',
					'name' => 'image_7',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11d0',
					'label' => 'Image 8',
					'name' => 'image_8',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bee05b11d1',
					'label' => 'Image 9',
					'name' => 'image_9',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
		),
		array(
			'key' => 'field_5f3bed750b148',
			'label' => 'Project After Image',
			'name' => 'project_after_image',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '33',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_5f3bed750b149',
					'label' => 'Image 1',
					'name' => 'image_1',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed750b14a',
					'label' => 'Image 2',
					'name' => 'image_2',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed750b14b',
					'label' => 'Image 3',
					'name' => 'image_3',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),

				array(
					'key' => 'field_5f3bed750b14c',
					'label' => 'Image 4',
					'name' => 'image_4',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed7d0b152',
					'label' => 'Image 5',
					'name' => 'image_5',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed750b14e',
					'label' => 'Image 6',
					'name' => 'image_6',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed750b14f',
					'label' => 'Image 7',
					'name' => 'image_7',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed750b150',
					'label' => 'Image 8',
					'name' => 'image_8',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_5f3bed750b151',
					'label' => 'Image 9',
					'name' => 'image_9',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
		),
		array(
			'key' => 'field_5f3d0fec410bd',
			'label' => 'Call To Action Button Text',
			'name' => 'call_to_action_button_text',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f3d1000410bf',
			'label' => 'Call To Action Button Link',
			'name' => 'call_to_action_button_link',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'alusta-references',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;
