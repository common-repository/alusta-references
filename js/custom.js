jQuery(document).ready(function($) {
	
	$("#alus_cons_year").yearpicker({
	  startYear: 1900,
	});
	
	$('.a_reference_slider').owlCarousel({
		loop:true,
		margin:10,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			600:{
				items:1,
				nav:false
			},
			1000:{
				items:1,
				nav:true,
				loop:false
			}
		}
	});
});