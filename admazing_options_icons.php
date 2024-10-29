<?php
//Security for CSRF attacks
$admazing_nonce_action='admazing-icons';
$admazing_nonce_name='_admazingIcons';
if (!empty($_POST)) $w=check_admin_referer($admazing_nonce_action, $admazing_nonce_name);

global $Admazing, $AdmazingOptions;

if ($Admazing->post_safe('action')=='admazing-save-icons') {
	$AdmazingOptions['dashboard_pos'] = $Admazing->post_safe('admazing_position');
	$AdmazingOptions['icons_size'] = $Admazing->post_safe('admazing_size');
	$AdmazingOptions['icons_skin'] = $Admazing->post_safe('admazing_skin');
	$AdmazingOptions['dashboard_title'] = trim($Admazing->post_safe('admazing_title'));
	update_option('admazing_opts', $AdmazingOptions);
	
	echo '<div class="updated"><p>' . __('Options saved','admazing') . '</p></div>';
}
?>
	<form action="options-general.php?page=admazing-options" method="post">

	<h3>The Toolbar Icons</h3>
	<p>Please, save the changes (if you done it) before click the below link:</p>
	<p><a href="index.php?editAdmazingDash=1"><span class="fa fa-share" style="color:#0074a2;"></span> Live editor of the Dashboard Admazing Icons</a></p>

	<h3>Toolbar box position</h3>
	<div>
		<a href="#" class="admazing-pos-preview <?php if ($AdmazingOptions['dashboard_pos']=='off') echo 'admazing-pos-preview-on'; ?>">
			<img src="<?php echo ADMAZING_URL; ?>/images/icons_off.jpg" width="170" height="98" alt="" />
			<input type="radio" name="admazing_position" value="off" <?php if ($AdmazingOptions['dashboard_pos']=='off') echo 'checked="checked"'; ?> /> Hide Admazing
		</a>
		<a href="#" class="admazing-pos-preview <?php if ($AdmazingOptions['dashboard_pos']=='top') echo 'admazing-pos-preview-on'; ?>">
			<img src="<?php echo ADMAZING_URL; ?>/images/icons_top.jpg" width="170" height="98" alt="" />
			<input type="radio" name="admazing_position" value="top" <?php if ($AdmazingOptions['dashboard_pos']=='top') echo 'checked="checked"'; ?> /> Show over widgets
		</a>
		<a href="#" class="admazing-pos-preview <?php if ($AdmazingOptions['dashboard_pos']=='inside') echo 'admazing-pos-preview-on'; ?>">
			<img src="<?php echo ADMAZING_URL; ?>/images/icons_widget.jpg" width="170" height="98" alt="" />
			<input type="radio" name="admazing_position" value="inside" <?php if ($AdmazingOptions['dashboard_pos']=='inside') echo 'checked="checked"'; ?> /> As another widget
		</a>
	</div>
	<h3>Toolbar box title</h3>
	<p><input type="text" name="admazing_title" value="<?php echo $AdmazingOptions['dashboard_title']; ?>" class="regular-text" /></p>
	<h3>Icon style preview</h3>
	<div class="admazing-icons admazing-icons-preview">
		<ul style="margin:0; <?php if ($AdmazingOptions['dashboard_pos']=='off') echo 'display:none;'; ?>" class="<?php echo $AdmazingOptions['icons_size'] . ' ' . $AdmazingOptions['icons_skin']; ?>">
			<li id="icon-preview"><a onclick="return false;" class="admazing_holder genericon genericon-github" href="#"></a><span>Name</span></li>
		</ul>
	</div>
	<div class="admazing_spacer">
		<p><label style="width:80px; display:inline-block;">Icon size*:</label> <select name="admazing_size" class="custom">
			<option value="admazing-size-100" <?php if ($AdmazingOptions['icons_size']=='admazing-size-100') echo 'selected="selected"'; ?>>100x100 px</option>
			<option value="admazing-size-75" <?php if ($AdmazingOptions['icons_size']=='admazing-size-75') echo 'selected="selected"'; ?>>75x75 px</option>
			<option value="admazing-size-50" <?php if ($AdmazingOptions['icons_size']=='admazing-size-50') echo 'selected="selected"'; ?>>50x50 px</option>
		</select></p>
		<p><label style="width:80px; display:inline-block;">Icon skin:</label> <select name="admazing_skin" class="custom">
			<option value="admazing-clear-gray" <?php if ($AdmazingOptions['icons_skin']=='admazing-clear-gray') echo 'selected="selected"'; ?>>Clear Gray</option>
			<option value="admazing-dark" <?php if ($AdmazingOptions['icons_skin']=='admazing-dark') echo 'selected="selected"'; ?>>Dark</option>
		</select></p>
		<p>* The size will be set to 50x50px in mobile devices in any case.</p>
	</div>
	
	<input type="submit" value="Save Changes" class="button button-primary" />
	<?php wp_nonce_field($admazing_nonce_action, $admazing_nonce_name); ?>
	<input type="hidden" value="admazing-save-icons" name="action" />
	</form>
	
