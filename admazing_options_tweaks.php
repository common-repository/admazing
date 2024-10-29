<?php
//Security for CSRF attacks
$admazing_nonce_action='admazing-icons';
$admazing_nonce_name='_admazingIcons';
if (!empty($_POST)) $w=check_admin_referer($admazing_nonce_action, $admazing_nonce_name);

global $Admazing, $AdmazingOptions;

if ($Admazing->post_safe('action')=='admazing-save-tweaks') {
	$AdmazingOptions['revisions'] = $Admazing->post_safe('admazing_revisions');
	$AdmazingOptions['autosave'] = $Admazing->post_safe('admazing_autosave');
	$AdmazingOptions['excerptpage'] = $Admazing->post_safe('admazing_excerptpage');
	update_option('admazing_opts', $AdmazingOptions);
	
	echo '<div class="updated"><p>' . __('Options saved','admazing') . '</p></div>';
}
?>
	<form action="options-general.php?page=admazing-options&tab=tweaks" method="post">

	<h3>Revisions and autosave</h3>
	<p>If you want to keep clean your database, and don't need to go back never, this is for you:</p>
	<p><label for="admazing_revisions"><input type="checkbox" name="admazing_revisions" value="1" <?php if ($AdmazingOptions['revisions']==1) echo 'checked="checked"'; ?> /> <span>Disable revisions on posts/pages edition</span></label></p>
	<p><label for="admazing_autosave"><input type="checkbox" name="admazing_autosave" value="1" <?php if ($AdmazingOptions['autosave']==1) echo 'checked="checked"'; ?> /> <span>Autosave posts</span></label></p>
	<hr />
	<h3>Page excerpt</h3>
	<p>Some themes show the excerpt pages, but by default WP don't allow you to add it manually.</p>
	<p><label for="admazing_excerptpage"><input type="checkbox" name="admazing_excerptpage" value="1" <?php if ($AdmazingOptions['excerptpage']==1) echo 'checked="checked"'; ?> /> <span>Add excerpt field to pages</span></label></p>
	<hr />
	
	<input type="submit" value="Save Changes" class="button button-primary" />
	<?php wp_nonce_field($admazing_nonce_action, $admazing_nonce_name); ?>
	<input type="hidden" value="admazing-save-tweaks" name="action" />
	</form>
	
