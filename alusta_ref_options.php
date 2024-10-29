<div class="wrap">
<h1 class="wp-heading-inline">Alusta References Options</h1>
<form method="post" action="options.php">
<?php settings_fields( 'register_alusta_ref_settings-group' ); ?>
<?php do_settings_sections( 'register_alusta_ref_settings-group' ); ?>	
<table width="100%" class="wp-list-table widefat fixed striped pages">

<tr>
	<td>Facebook Link</td>
    <td><input type="text" class="transla_alus" name="facebook_link" value="<?php echo get_option( 'facebook_link' ); ?>" style="width: 250px;"></td>
</tr>
<tr>
	<td>Google Business Link</td>
	<td><input type="text" class="transla_alus" name="google_business_link" value="<?php echo get_option( 'google_business_link' ); ?>" style="width: 250px;"></td>
</tr>
	
<tr>
	<td colspan="3">
  	<?php submit_button(); ?>
  </td>
</tr>	

</table>

</form>
</div>

