<?php
//Security for CSRF attacks
$admazing_nonce_action='admazing-login';
$admazing_nonce_name='_admazingLogin';
if (!empty($_POST)) $w=check_admin_referer($admazing_nonce_action, $admazing_nonce_name);

global $Admazing, $AdmazingOptions;

if ($Admazing->post_safe('action')=='admazing-save-login') {

	$custom = array();
	
	$custom['active'] = $Admazing->post_safe('admazingActivateLogin');
	
	//Outer background
	$custom['obmode']=$Admazing->post_safe('obmode');
	$custom['obc1']=$Admazing->post_safe('obc1');
	$custom['obc2']=$Admazing->post_safe('obc2');

	//Dialog background
	$custom['dbmode']=$Admazing->post_safe('dbmode');
	$custom['dbc1']=$Admazing->post_safe('dbc1');
	$custom['dbc2']=$Admazing->post_safe('dbc2');
	
	// Label color
	$custom['lc']=$Admazing->post_safe('lc');

	// Input fields
	$custom['itc']=$Admazing->post_safe('itc');
	$custom['itbg']=$Admazing->post_safe('itbg');
	$custom['itbc']=$Admazing->post_safe('itbc');

	//Button background
	$custom['btc']=$Admazing->post_safe('btc');
	$custom['btbc']=$Admazing->post_safe('btbc');
	$custom['btmode']=$Admazing->post_safe('btmode');
	$custom['btc1']=$Admazing->post_safe('btc1');
	$custom['btc2']=$Admazing->post_safe('btc2');

	//Button background hover
	$custom['btch']=$Admazing->post_safe('btch');
	$custom['btmodeh']=$Admazing->post_safe('btmodeh');
	$custom['btc1h']=$Admazing->post_safe('btc1h');
	$custom['btc2h']=$Admazing->post_safe('btc2h');

	//External link
	$custom['loc']=$Admazing->post_safe('loc');
	$custom['loch']=$Admazing->post_safe('loch');

	//Logo
	$custom['logo']=$Admazing->post_safe('logo');
	$custom['url_logo']=$Admazing->post_safe('url_logo');

	$AdmazingOptions['login'] = $custom;
	
	if ($Admazing->post_safe('admazingResetLogin')=='1') $AdmazingOptions['login'] = $Admazing->defaultLogin;
	if ($AdmazingOptions['login']['url_logo']=='') $AdmazingOptions['login']['url_logo']=get_bloginfo('url');

	update_option('admazing_opts', $AdmazingOptions);
	
	echo '<div class="updated"><p>' . __('Options saved','admazing') . '</p></div>';
}

function gradient_selector($name, $val) {
?>
	<select style="width:250px" name="<?php echo $name; ?>" class="custom">
		<option data-image="<?php echo ADMAZING_URL; ?>/images/ico_solid.gif" value="solid" <?php if ($val=='solid') echo ' selected="selected"'; ?>>Solid color</option>
		<option data-image="<?php echo ADMAZING_URL; ?>/images/ico_vertical.gif" value="vertical" <?php if ($val=='vertical') echo ' selected="selected"'; ?>>Vertical gradient</option>
		<option data-image="<?php echo ADMAZING_URL; ?>/images/ico_horizontal.gif" value="horizontal" <?php if ($val=='horizontal') echo ' selected="selected"'; ?>>Horizontal gradient</option>
		<option data-image="<?php echo ADMAZING_URL; ?>/images/ico_diagonal_1.gif" value="diagonal1" <?php if ($val=='diagonal1') echo ' selected="selected"'; ?>>Diagonal 1 gradient</option>
		<option data-image="<?php echo ADMAZING_URL; ?>/images/ico_diagonal_2.gif" value="diagonal2" <?php if ($val=='diagonal2') echo ' selected="selected"'; ?>>Diagonal 2 gradient</option>
		<option data-image="<?php echo ADMAZING_URL; ?>/images/ico_diagonal_2.gif" value="radial" <?php if ($val=='radial') echo ' selected="selected"'; ?>>Radial gradient</option>
	</select>
<?php
}

$custom = $AdmazingOptions['login'];
?>
<script type="text/javascript">
var no_logo = "<?php echo ADMAZING_URL; ?>/images/w-logo-blue.png";
</script>
<div id="dynamic_css">
<style type="text/css">
.nothing {}
</style>
</div>
	
	<div id="login_preview">
		<h3 style="padding-left:20px;">Login page preview:</h3>
		<div id="body_preview">
			<div id="dialog_content">
				<div class="logo_preview" style="background-image:url(<?php if ($custom['logo'] != 'x' && $custom['logo'] != '') echo $custom['logo']; else echo ADMAZING_URL . '/images/w-logo-blue.png'; ?>);"></div>
				<form action="." method="post">
				<div id="dialog_preview">
					<p><label>Username</label><br />
					<input type="text" value="user_name" /></p>
					<p><label>Password</label><br />
					<input type="password" value="xxxxx" /></p>
					<p class="forgetmenot"><label for="rememberme"><input type="checkbox"> Remember me</label></p>
					<p class="submit"><input type="submit" value="Enter"></p>
				</div>
				</form>
				<p id="external_link"><a href="#">Remember password</a></p>
			</div>
		</div>
	</div>
	<div id="customize_login">
		<form action="options-general.php?page=admazing-options&tab=login" method="post">
			<p style="margin:20px 0 0 0;"><input type="checkbox" name="admazingActivateLogin" value="1" class="admazing_activate_login" <?php if ($custom['active']==1) echo 'checked="checked"'; ?> /> Customize the WordPress login through Admazing</p>
			<div id="accordion">
				<h3>Main</h3>
				<div>
					<p><label>Image Logo:</label> <a class="upload_image button" href="#">New logo</a> <a class="remove_image button" href="#">Default WP logo</a></p>
					<p><label>URL Logo:</label> <input type="text" name="url_logo" value="<?php echo $custom['url_logo']; ?>" class="regular-text" /></p>
					<input type="hidden" name="logo" value="<?php echo $custom['logo']; ?>" />
					<p><label>Label color:</label><span class="color_selector"><input type="text" value="<?php echo $custom['lc']; ?>" class="my-color-field" data-default-color="#777777" name="lc" /></span></p>
					<h4>Input fields:</h4>
					<p><label>Text:</label><span class="color_selector"><input type="text" value="<?php echo $custom['itc']; ?>" class="my-color-field" data-default-color="#333333" name="itc" /></span></p>
					<p><label>Background:</label><span class="color_selector"><input type="text" value="<?php echo $custom['itbg']; ?>" class="my-color-field" data-default-color="#fbfbfb" name="itbg" /></span></p>
					<p><label>Border:</label><span class="color_selector"><input type="text" value="<?php echo $custom['itbc']; ?>" class="my-color-field" data-default-color="#dddddd" name="itbc" /></span></p>
				</div>
				<h3>Backgrounds</h3>
				<div>
					<h4>Outer background:</h4>
					<div class="color_degradee">
						<p><?php echo gradient_selector('obmode', $custom['obmode']); ?></p>
						<p><label>Color #1:</label><span class="color_selector"><input type="text" value="<?php echo $custom['obc1']; ?>" class="my-color-field" data-default-color="#f1f1f1" name="obc1" /></span></p>
						<p class="color2"><label>Color #2:</label><span class="color_selector"><input type="text" value="<?php echo $custom['obc2']; ?>" class="my-color-field" data-default-color="#ffffff" name="obc2" /></span></p>
					</div>
					<h4>Dialog background:</h4>
					<div class="color_degradee">
						<p><?php echo gradient_selector('dbmode', $custom['dbmode']); ?></p>
						<p><label>Color #1:</label><span class="color_selector"><input type="text" value="<?php echo $custom['dbc1']; ?>" class="my-color-field" data-default-color="#ffffff" name="dbc1" /></span></p>
						<p class="color2"><label>Color #2:</label><span class="color_selector"><input type="text" value="<?php echo $custom['dbc2']; ?>" class="my-color-field" data-default-color="#aaaaaa" name="dbc2" /></span></p>
					</div>
				</div>
				<h3>Login button</h3>
				<div>
					<p><label>Text:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btc']; ?>" class="my-color-field" data-default-color="#ffffff" name="btc" /></span></p>
					<div class="color_degradee">
						<p><label><strong>Background:</strong></label><?php echo gradient_selector('btmode', $custom['btmode']); ?></p>
						<p><label>Color #1:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btc1']; ?>" class="my-color-field" data-default-color="#2ea2cc" name="btc1" /></span></p>
						<p class="color2"><label>Color #2:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btc2']; ?>" class="my-color-field" data-default-color="#178fbf" name="btc2" /></span></p>
					</div>
					<p><label>Border:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btbc']; ?>" class="my-color-field" data-default-color="#0074a2" name="btbc" /></span></p>
					<h4>Rollover</h4>
					<p><label>Text:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btch']; ?>" class="my-color-field" data-default-color="#ffffff" name="btch" /></span></p>
					<div class="color_degradee">
						<p><label><strong>Background:</strong></label><?php echo gradient_selector('btmodeh', $custom['btmodeh']); ?></p>
						<p><label>Color #1:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btc1h']; ?>" class="my-color-field" data-default-color="#2ea2cc" name="btc1h" /></span></p>
						<p class="color2"><label>Color #2:</label><span class="color_selector"><input type="text" value="<?php echo $custom['btc2h']; ?>" class="my-color-field" data-default-color="#178fbf" name="btc2h" /></span></p>
					</div>
				</div>
				<h3>Outer link</h3>
				<div>
					<p><label>Text:</label><span class="color_selector"><input type="text" value="<?php echo $custom['loc']; ?>" class="my-color-field" data-default-color="#999999" name="loc" /></span></p>
					<p><label>Rollover:</label><span class="color_selector"><input type="text" value="<?php echo $custom['loch']; ?>" class="my-color-field" data-default-color="#2ea2cc" name="loch" /></span></p>
				</div>
			</div>
			<p><input type="checkbox" name="admazingResetLogin" value="1" /> Reset default WP values</p>
			<p style="text-align:right;"><input type="submit" value="Save Changes" class="button button-primary" /></p>

			<?php wp_nonce_field($admazing_nonce_action, $admazing_nonce_name); ?>
			<input type="hidden" value="admazing-save-login" name="action" />
		</form>
	</div>

