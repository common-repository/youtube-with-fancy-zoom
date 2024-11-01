<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$ywfz_errors = array();
$ywfz_success = '';
$ywfz_error_found = FALSE;

// Preset the form fields
$form = array(
	'ywfz_title' => '',
	'ywfz_watch' => '',
	'ywfz_code' => '',
	'ywfz_img' => '',
	'ywfz_size' => '',
	'ywfz_imglink' => '',
	'ywfz_status' => '',
	'ywfz_sidebar' => '',
	'ywfz_expire' => '',
	'ywfz_id' => ''
);

// Form submitted, check the data
if (isset($_POST['ywfz_form_submit']) && $_POST['ywfz_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ywfz_form_add');
	
	$form['ywfz_title'] = isset($_POST['ywfz_title']) ? sanitize_text_field($_POST['ywfz_title']) : '';
	if ($form['ywfz_title'] == '')
	{
		$ywfz_errors[] = __('Please enter the title', 'youtube-with-fancy-zoom');
		$ywfz_error_found = TRUE;
	}
	
	$form['ywfz_watch'] = isset($_POST['ywfz_watch']) ? esc_url_raw($_POST['ywfz_watch']) : '';
	if ($form['ywfz_watch'] == '')
	{
		$ywfz_errors[] = __('Please enter the video link.', 'youtube-with-fancy-zoom');
		$ywfz_error_found = TRUE;
	}
	
	$form['ywfz_code'] = isset($_POST['ywfz_code']) ? sanitize_text_field($_POST['ywfz_code']) : '';
	if ($form['ywfz_code'] == '')
	{
		$ywfz_errors[] = __('Please enter the video code.', 'youtube-with-fancy-zoom');
		$ywfz_error_found = TRUE;
	}
	
	$form['ywfz_img'] = isset($_POST['ywfz_img']) ? sanitize_text_field($_POST['ywfz_img']) : '';
	if ($form['ywfz_img'] == '')
	{
		$ywfz_errors[] = __('Please select the display image.', 'youtube-with-fancy-zoom');
		$ywfz_error_found = TRUE;
	}
	if($form['ywfz_img'] != "Youtube Thumbnail" && $form['ywfz_img'] != "My Thumbnail")
	{
		$form['ywfz_img'] = "Youtube Thumbnail";
	}  
	
	$form['ywfz_size'] = isset($_POST['ywfz_size']) ? sanitize_text_field($_POST['ywfz_size']) : '';
	if(!is_numeric($form['ywfz_size'])) { $form['ywfz_size'] = 0; }
	
	$form['ywfz_imglink'] = isset($_POST['ywfz_imglink']) ? esc_url_raw($_POST['ywfz_imglink']) : '';
	
	$form['ywfz_status'] = isset($_POST['ywfz_status']) ? sanitize_text_field($_POST['ywfz_status']) : '';
	if ($form['ywfz_status'] == '')
	{
		$ywfz_errors[] = __('Please select the display status.', 'youtube-with-fancy-zoom');
		$ywfz_error_found = TRUE;
	}
	if($form['ywfz_status'] != "Yes" && $form['ywfz_status'] != "No")
	{
		$form['ywfz_status'] = "Yes";
	}
	
	$form['ywfz_sidebar'] = isset($_POST['ywfz_sidebar']) ? sanitize_text_field($_POST['ywfz_sidebar']) : '';
	if ($form['ywfz_sidebar'] == '')
	{
		$ywfz_errors[] = __('Please select the sidebar display.', 'youtube-with-fancy-zoom');
		$ywfz_error_found = TRUE;
	}
	if($form['ywfz_sidebar'] != "Yes" && $form['ywfz_sidebar'] != "No")
	{
		$form['ywfz_sidebar'] = "Yes";
	}
	//$form['ywfz_expire'] = isset($_POST['ywfz_expire']) ? $_POST['ywfz_expire'] : '';

	//	No errors found, we can add this Group to the table
	if ($ywfz_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_G_YWFZ_TABLE."`
			(ywfz_title, ywfz_watch, ywfz_code, ywfz_img, ywfz_size, ywfz_imglink, ywfz_status, ywfz_sidebar )
			VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",
			array($form['ywfz_title'], $form['ywfz_watch'], $form['ywfz_code'], $form['ywfz_img'], 
						$form['ywfz_size'], $form['ywfz_imglink'], $form['ywfz_status'], $form['ywfz_sidebar'])
		);
		$wpdb->query($sql);
		
		$ywfz_success = __('New details was successfully added.', 'youtube-with-fancy-zoom');
		
		// Reset the form fields
		$form = array(
			'ywfz_title' => '',
			'ywfz_watch' => '',
			'ywfz_code' => '',
			'ywfz_img' => '',
			'ywfz_size' => '',
			'ywfz_imglink' => '',
			'ywfz_status' => '',
			'ywfz_sidebar' => '',
			'ywfz_expire' => '',
			'ywfz_id' => ''
		);
	}
}

if ($ywfz_error_found == TRUE && isset($ywfz_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $ywfz_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($ywfz_error_found == FALSE && strlen($ywfz_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $ywfz_success; ?> 
		<a href="<?php echo WP_G_YWFZ_ADMIN_URL; ?>"><?php _e('Click here to view the details', 'youtube-with-fancy-zoom'); ?></a></strong></p>
	</div>
	<?php
}
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var img_imageurl = uploaded_image.toJSON().url;
			var img_imagetitle = uploaded_image.toJSON().title;
            // Let's assign the url value to the input field
            $('#ywfz_imglink').val(img_imageurl);
			//$('#cf_title').val(img_imagetitle);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery');
wp_enqueue_media();
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Youtube with fancy zoom', 'youtube-with-fancy-zoom'); ?></h2>
	<form name="ywfz_form" method="post" action="#" onsubmit="return ywfz_submit()"  >
      <h3><?php _e('Add details', 'youtube-with-fancy-zoom'); ?></h3>
      
		<label for="tag-title"><?php _e('Title:', 'youtube-with-fancy-zoom'); ?></label>
		<input name="ywfz_title" type="text" id="ywfz_title" value="" size="50" maxlength="150" /></td>
		<p><?php _e('Please enter the title', 'youtube-with-fancy-zoom'); ?></p>
		
		<label for="tag-title"><?php _e('Youtube watch link:', 'youtube-with-fancy-zoom'); ?></label>
		<input name="ywfz_watch" type="text" id="ywfz_watch" value="" size="70" maxlength="1000" />
		<p><?php _e('Please enter the video link.', 'youtube-with-fancy-zoom'); ?> (Ex: http://www.youtube.com/watch?v=5TSPlykcdYk)</p>
		
		<label for="tag-title"><?php _e('Youtube code:', 'youtube-with-fancy-zoom'); ?></label>
		<input name="ywfz_code" type="text" id="ywfz_code" value=""  size="30" maxlength="70" />
		<p><?php _e('If (http://www.youtube.com/watch?v=5TSPlykcdYk) this is your youtube video, then &ldquo;5TSPlykcdYk&rdquo; is your youtube code.', 'youtube-with-fancy-zoom'); ?></p>
		
		<label for="tag-title"><?php _e('Youtube thumbnail size:', 'youtube-with-fancy-zoom'); ?></label>
		<select name="ywfz_size" id="ywfz_size">
            <option value='0'>Big</option>
            <option value='1'>Small</option>
          </select>
		 <p><?php _e('Select thumbnail size.', 'youtube-with-fancy-zoom'); ?></p>
		 
		<label for="tag-title"><?php _e('Display Image:', 'youtube-with-fancy-zoom'); ?></label>
		<select name="ywfz_img" id="ywfz_img">
            <option value='Youtube Thumbnail'>Youtube Thumbnail</option>
			<option value='My Thumbnail'>My Thumbnail</option>
         </select>
		 <p><?php _e('Which image you want to display?', 'youtube-with-fancy-zoom'); ?></p>
		 
		<label for="tag-title"><?php _e('Thumbnail Image link (My Thumbnail):', 'youtube-with-fancy-zoom'); ?></label>
		<input name="ywfz_imglink" type="text" id="ywfz_imglink" value=""  size="70" maxlength="1000" />
		<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
		<p><?php _e('Please enter your image link.', 'youtube-with-fancy-zoom'); ?></p>
		 
		<label for="tag-title"><?php _e('Display Status:', 'youtube-with-fancy-zoom'); ?></label>
		<select name="ywfz_status" id="ywfz_status">
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>
         </select>
		 <p><?php _e('Do you want the viceo to show in front end?', 'youtube-with-fancy-zoom'); ?></p>
		 
		<label for="tag-title"><?php _e('Sidebar Display:', 'youtube-with-fancy-zoom'); ?></label>
		<select name="ywfz_sidebar" id="ywfz_sidebar">
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>
         </select>
		 <p><?php _e('Do you want the viceo to show in sidebar widget?', 'youtube-with-fancy-zoom'); ?></p>
	  	  
      <input name="ywfz_id" id="ywfz_id" type="hidden" value="">
      <input type="hidden" name="ywfz_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Insert Details', 'youtube-with-fancy-zoom'); ?>" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="ywfz_redirect()" value="<?php _e('Cancel', 'youtube-with-fancy-zoom'); ?>" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="ywfz_help()" value="<?php _e('Help', 'youtube-with-fancy-zoom'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('ywfz_form_add'); ?>
    </form>
</div>
</div>