<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_ywfz_display']) && $_POST['frm_ywfz_display'] == 'yes')
{
	$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$ywfz_success = '';
	$ywfz_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_G_YWFZ_TABLE."
		WHERE `ywfz_id` = %d",
		array($did)
	);

	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'youtube-with-fancy-zoom'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('ywfz_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_G_YWFZ_TABLE."`
					WHERE `ywfz_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$ywfz_success_msg = TRUE;
			$ywfz_success = __('Selected record was successfully deleted.', 'youtube-with-fancy-zoom');
		}
	}
	
	if ($ywfz_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $ywfz_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Youtube with fancy zoom', 'youtube-with-fancy-zoom'); ?>
	<a class="add-new-h2" href="<?php echo WP_G_YWFZ_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'youtube-with-fancy-zoom'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_G_YWFZ_TABLE."` order by ywfz_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_ywfz_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <!--<th class="check-column" scope="col" style="width:15px;"><input type="checkbox" name="ywfz_group_item[]" /></th>-->
			<th scope="col"><?php _e('Video ID', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Title', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Code', 'youtube-with-fancy-zoom'); ?></th>
            <th scope="col"><?php _e('Image', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Size', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Display Image', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Display', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Sidebar', 'youtube-with-fancy-zoom'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <!--<th class="check-column" scope="col" style="height:15px;"><input type="checkbox" name="ywfz_group_item[]" /></th>-->
			<th scope="col"><?php _e('Video ID', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Title', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Code', 'youtube-with-fancy-zoom'); ?></th>
            <th scope="col"><?php _e('Image', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Size', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Display Image', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Display', 'youtube-with-fancy-zoom'); ?></th>
			<th scope="col"><?php _e('Sidebar', 'youtube-with-fancy-zoom'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<!--<td align="left"><input type="checkbox" value="<?php //echo $data['ywfz_id']; ?>" name="ywfz_group_item[]"></td>-->
						<td><?php echo $data['ywfz_id']; ?></td>
						<td><?php echo stripslashes($data['ywfz_title']); ?>
						<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_G_YWFZ_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['ywfz_id']; ?>"><?php _e('Edit', 'youtube-with-fancy-zoom'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:ywfz_delete('<?php echo $data['ywfz_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'youtube-with-fancy-zoom'); ?></a></span> 
						</div>			
						</td>
						<td><?php echo stripslashes($data['ywfz_code']); ?></td>
						<td><img src="http://img.youtube.com/vi/<?php echo(stripslashes($data['ywfz_code'])); ?>/2.jpg"</td>
						<td><?php if($data['ywfz_size'] == '0') { echo 'Big'; } else { echo 'Small'; } ?></td>
						<td><?php echo stripslashes($data['ywfz_img']); ?></td>
						<td><?php echo stripslashes($data['ywfz_status']); ?></td>
						<td><?php echo stripslashes($data['ywfz_sidebar']); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="8" align="center"><?php _e('No records available.', 'youtube-with-fancy-zoom'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('ywfz_form_show'); ?>
		<input type="hidden" name="frm_ywfz_display" value="yes"/>
      </form>	
	<div class="tablenav bottom">
		<a href="<?php echo WP_G_YWFZ_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'youtube-with-fancy-zoom'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_G_YWFZ_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'youtube-with-fancy-zoom'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_G_YWFZ_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'youtube-with-fancy-zoom'); ?>" /></a>
	</div>
	</div>
</div>