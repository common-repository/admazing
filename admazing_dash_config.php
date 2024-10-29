<div id="admazingPopup" style="display:none;">
	<div <?php /*style="width:600px; height:550px;"*/ ?>>
		<div class="admazing_step_1">
			<h1><span>1</span> Select an icon</h1>
			<div class="admazing-icons" style="height:400px; overflow:auto;">
				<?php
				global $wp_version;
				if ( $wp_version >= 3.8 ) {
				?>
					<h2>WP Dashicons</h2>
					<ul class="admazing-size-50 admazing-clear-gray">
					<?php
					for ($x=100; $x<=242; $x++) {
						
						if ($x==114) $x=115;
						if ($x==124) $x=125;
						if ($x==131) $x=132;
						if ($x==137) $x=138;
						if ($x==144) $x=145;
						if ($x==146) $x=147;
						if ($x==149) $x=153;
						if ($x==162) $x=163;
						if ($x==170) $x=171;
						if ($x==186) $x=200;
						if ($x==202) $x=203;
						if ($x==241) $x=242;
	
						$unicode = '\uf' . $x; $val = intval('0xf' . $x, 16);
						echo '<li><a href="#" onclick="admazingPhase2(' . $val . ',\'dashicons\'); return false;" class="dashicons">' . json_decode('"'.$unicode.'"') . '</a></li>';
					}
					?>
					</ul>
				<?php
				}
				?>
				<h2>Font GenerIcons</h2>
				<ul class="admazing-size-50 admazing-clear-gray">
				<?php
				for ($x=100; $x<=507; $x++) {

					if ($x==110) $x=200;
					if ($x==227) $x=300;
					if ($x==309) $x=400;
					if ($x==477) $x=500;
					
					$unicode = '\uf' . $x; $val = intval('0xf' . $x, 16);
					echo '<li><a href="#" onclick="admazingPhase2(' . $val . ',\'genericon\'); return false;" class="genericon">' . json_decode('"'.$unicode.'"') . '</a></li>';
				}
				?>
				</ul>
				<h2>Font Awesome Icons</h2>
				<ul class="admazing-size-50 admazing-clear-gray">
				<?php
				for ($x=0xf000; $x<=0xf20c; $x++) {

					if ($x==0xf00f) $x=0xf010;
					if ($x==0xf01f) $x=0xf021;
					if ($x==0xf03f) $x=0xf040;
					if ($x==0xf04f) $x=0xf050;
					if ($x==0xf05f) $x=0xf060;
					if ($x==0xf06f) $x=0xf070;
					if ($x==0xf07f) $x=0xf080;
					if ($x==0xf08f) $x=0xf090;
					if ($x==0xf09f) $x=0xf0a0;
					if ($x==0xf0af) $x=0xf0b0;
					if ($x==0xf0b3) $x=0xf0c0;
					if ($x==0xf0cf) $x=0xf0d0;
					if ($x==0xf0df) $x=0xf0e0;
					if ($x==0xf0ef) $x=0xf0f0;
					if ($x==0xf0ff) $x=0xf100;
					if ($x==0xf10f) $x=0xf110;
					if ($x==0xf11f) $x=0xf120;
					if ($x==0xf12f) $x=0xf130;
					if ($x==0xf13f) $x=0xf140;
					if ($x==0xf14f) $x=0xf150;
					if ($x==0xf15f) $x=0xf160;
					if ($x==0xf16f) $x=0xf170;
					if ($x==0xf17f) $x=0xf180;
					if ($x==0xf18f) $x=0xf190;
					if ($x==0xf19f) $x=0xf1a0;
					if ($x==0xf1af) $x=0xf1b0;
					if ($x==0xf1bf) $x=0xf1c0;
					if ($x==0xf1cf) $x=0xf1d0;
					if ($x==0xf1df) $x=0xf1e0;
					if ($x==0xf1ef) $x=0xf1f0;
					if ($x==0xf1ff) $x=0xf200;

					$unicode = '\u' . dechex($x);
					echo '<li><a href="#" onclick="admazingPhase2(' . $x . ',\'fa\'); return false;" class="fa">' . json_decode('"'.$unicode.'"') . '</a></li>';
				}
				?>
				</ul>
			</div>
			<p style="text-align:center;"><a href="#" class="button cancel">Cancel</a>
		</div>
		<div class="admazing_step_2" style="display:none;">
			<h1><span>2</span> Name it & set the action / url</h1>
			<div class="admazing-icons" style="width:135px; position:absolute;">
				<ul class="admazing-size-100 admazing-clear-gray" style="margin:0;">
					<li style="margin:0 0 0 10px;" id="icon-preview"><a href="#" class="fa admazing_holder" onclick="return false;"></a><span>Name</span></li>
				</ul>
			</div>
			<div style="margin-left:135px;">
				<p><label>Name:</label> <input type="text" name="admazing_name" value="" placeholder="Name" /></p>
				<p style="margin-bottom:0;"><input type="radio" name="admazing-menu" value="0"> Choose a link from the WordPress Admin Menu<br />
				<select name="admazing-option-url[]" style="margin-left:16px;">
				<?php 
				global $menu;
				global $submenu;
					
				while ($m = current($menu)) {
					if ($m[0] != '') {
						echo '<option value="' . $m[2] . '">' . strip_tags($m[0]) . '</option>';
						if (isset($submenu[$m[2]])) {
							$sub=$submenu[$m[2]];
							while ($s = current($sub)) {
								echo '<option value="' . $s[2] . '"> &gt; ' . $s[0] . '</option>';
								next ($sub);
							}
						}
					}
					next ($menu);
				}
				?>
				</select></p>
				<?php
				$user_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
				$input = 0;
				foreach ($user_menus as $user_menu) {
					$input++;
					$menu_opts = wp_get_nav_menu_items($user_menu->term_taxonomy_id);

					if (count($menu_opts) != 0) {
						echo '<p><input type="radio" name="admazing-menu" value="' . $input . '"> Choose a link using the ' . $user_menu->name . ' Menu<br />';
						echo '<select name="admazing-option-url[]" style="margin-left:16px;">';
						$this->iterate_menus($menu_opts, 0, 0);
						echo '</select> <input type="checkbox" value="1" name="admazing-adminside[]" checked="checked" />Open in edition mode</p>';
					}
				}
		
				echo '<p style="margin-top:0;"><input type="radio" name="admazing-menu" value="custom" id="admazing-custom-link"> Insert a custom link<br />';
				echo '<input type="text" name="admazing-option-url-custom" class="large-text" style="margin-left:16px; width:80%" placeholder="' . get_bloginfo('url') . '" /></p>';
				echo '<p><input type="checkbox" name="admazing-blank" value="1"> Open in a new window</p>';
				echo '<p>Set color (will override the default icons color):<br><input type="text" class="my-color-field" value="#bbbbbb" data-default-color="#bbbbbb">';
				?>
			</div>
			<p style="text-align:center;"><a href="#" class="button cancel">Cancel</a> <a href="#" class="button-primary" style="color:#fff;" id="admazing-addicon">Add Icon</a></p>
		</div>
	</div>
</div>
<script type="text/javascript">
var admazing_url = '<?php echo ADMAZING_URL . $route; ?>';
</script>
