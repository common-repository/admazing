<?php
if (!class_exists("Admazing")) {
	class Admazing {
		
		//Init functions, defaults and constants
		var $AdmazingOptions = array();
		var $isDashboard = false;
		var $editDashboard = false;

		var $dashIcons = array(
			'0' => array (
				'title' => 'New Page',
				'url' => 'post-new.php?post_type=page',
				'icon' => 61525,
				'font' => 'fa',
				'blank' => 0,
				'color' => '#81d742'
				),
			'1' => array (
				'title' => 'New Post',
				'url' => 'post-new.php',
				'icon' => 61705,
				'font' => 'dashicons',
				'blank' => 0,
				'color' => '#1e73be'
				),
			'2' => array (
				'title' => 'Categories',
				'url' => 'edit-tags.php?taxonomy=category',
				'icon' => 62209,
				'font' => 'genericon',
				'blank' => 0,
				'color' => ''
				),
			'3' => array (
				'title' => 'Tags',
				'url' => 'edit-tags.php?taxonomy=post_tag',
				'icon' => 62210,
				'font' => 'genericon',
				'blank' => 0,
				'color' => ''
				),
			'4' => array (
				'title' => 'Edit icons',
				'url' => 'index.php?editAdmazingDash=1',
				'icon' => 61613,
				'font' => 'fa',
				'blank' => 0,
				'color' => ''
				),
			);
		
		var $defaultLogin = array(
			'active' => 0,
			'obmode' => 'solid',
			'obc1' => '#f1f1f1',
			'obc2' => '#ffffff',
			'dbmode' => 'solid',
			'dbc1' => '#ffffff',
			'dbc2' => '#aaaaaa',
			'lc' => '#777777',
			'itc' => '#333333',
			'itbg' => '#fbfbfb',
			'itbc' => '#dddddd',
			'btmode' => 'solid',
			'btc' => '#ffffff',
			'btbc' => '#0074a2',
			'btc1' => '#2ea2cc',
			'btc2' => '#178fbf',
			'btmodeh' => 'solid',
			'btch' => '#ffffff',
			'btc1h' => '#2ea2cc',
			'btc2h' => '#178fbf',
			'loc' => '#999999',
			'loch' => '#2ea2cc',
			'logo' => 'x',
			'url_logo' => ''
		);
			
		function getAdminOptions() {
			
			$AdmazingOptions = array (
				'dashboard_pos' => 'top',
				'icons_size' => 'admazing-size-75',
				'icons_skin' => 'admazing-clear-gray',
				'dashboard_title' => __('The Admazing Menu','admazing'),
				'dashboard_menu' => $this->dashIcons,
				'dashboard_menu_edit' => $this->dashIcons,
				'login' => $this->defaultLogin,
				'revisions' => 1,
				'autosave' => 1,
				'excerptpage' => 0,
				'last_version' => ADMAZING_VERSION

			);

			$devOptions = get_option('admazing_opts');
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option) $AdmazingOptions[$key] = $option;
			} else {
				update_option('admazing_opts', $AdmazingOptions);
			}
			if ($AdmazingOptions['login']['url_logo']=='') $AdmazingOptions['login']['url_logo']=get_bloginfo('url');
			return $AdmazingOptions;
		}
	
		function init($ajax=false) {
			
			if (defined('ADMAZING_URL')) return;
			
			global $AdmazingOptions;

			$url = plugins_url();
			define('ADMAZING_URL', $url . '/admazing');
			
			if ($this->get_safe('editAdmazingDash')==1) $this->editDashboard=true;
			
			//$this->knews_load_plugin_textdomain();

			define('ADMAZING_DIR', dirname(__FILE__));
			//$AdmazingOptions = $this->getAdminOptions();
			
			if ($ajax) return;
			
			if ($AdmazingOptions['dashboard_pos']=='top') {
				add_action('admin_notices', array($this, 'print_dashboard_top') );

			} elseif ($AdmazingOptions['dashboard_pos']=='inside') {
				$admazingTitle = $AdmazingOptions['dashboard_title'];
				if ($admazingTitle=='') $admazingTitle = __('The Admazing Menu','admazing');
				add_meta_box('admazing-widget', $admazingTitle, array($this, 'print_dashboard_menu'), 'dashboard', 'normal', 'high');
			}
			
			add_action('admin_head-index.php', array($this, 'dashboard_detection') );
			add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts') );

			if ($this->editDashboard) add_action('in_admin_footer', array($this, 'dash_footer') );
			
			//remove_action('admin_head', 'in_admin_header'); No funciona
			//add_filter('in_admin_header', array($this, 'in_admin_header'));

			add_options_page('Admazing options', 'Admazing', 'manage_options', 'admazing-options', array($this, 'show_options') );
			
			add_action('login_enqueue_scripts', array ($this, 'login_styles') );
			add_filter('login_headerurl', array($this, 'login_logo_url') );
			
			add_action( 'admin_print_footer_scripts', array($this, 'add_pointer_scripts') );
			
		}

		function my_add_excerpts_to_pages() {
			 add_post_type_support( 'page', 'excerpt' );
		}

		function disable_autosave() {
			wp_deregister_script('autosave');
		}

		function show_options() {
			echo '<div class="wrap">';
			?>
			<h2 class="nav-tab-wrapper">
				<a href="options-general.php?page=admazing-options" class="nav-tab <?php if ($this->get_safe('tab')=='') echo 'nav-tab-active'; ?>">Dashboard Icons</a>
				<a href="options-general.php?page=admazing-options&tab=login" class="nav-tab <?php if ($this->get_safe('tab')=='login') echo 'nav-tab-active'; ?>">Login Screen</a>
				<a href="options-general.php?page=admazing-options&tab=tweaks" class="nav-tab <?php if ($this->get_safe('tab')=='tweaks') echo 'nav-tab-active'; ?>">WP Tweaks</a>
			</h2>
			<?php
			switch ($this->get_safe('tab')) {
			
				case 'login' :
					require 'admazing_options_login.php';
					break;

				case 'tweaks' :
					require 'admazing_options_tweaks.php';
					break;

				default :
					require 'admazing_options_icons.php';
					break;
			}
			echo '</div>';
		}
		
		function menu_support() {
			register_nav_menu( 'admazing-top', 'Admazing top menu' );
		}

		function in_admin_header () {
			?>
			<style type="text/css">html { padding-top:29px !important;}html.wp-toolbar { padding-top:57px !important;}</style>
			<?php
			wp_nav_menu( array( 'theme_location' => 'admazing-top', 'container_class' => 'top-admazing-menu' ) );
		}
		
		function dashboard_detection() {

			$this->isDashboard=true;

			wp_enqueue_style( 'admanzing-css', ADMAZING_URL . '/css/dashboard.css', array(), ADMAZING_VERSION, 'all' );
			wp_enqueue_style( 'font-awesome', ADMAZING_URL . '/font-awesome/font-awesome.min.css', array(), ADMAZING_VERSION, 'all' );
			wp_enqueue_style( 'font-genericons', ADMAZING_URL . '/genericons/genericons.css', array(), ADMAZING_VERSION, 'all' );
		}

		function enqueue_scripts() {

			if ($this->editDashboard) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( 'admanzing-configurator', ADMAZING_URL . '/js/dashboard.js', array(), ADMAZING_VERSION, false );				
			};
			
			if ($this->get_safe('page')=='admazing-options') {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script('jquery-ui-accordion');
				wp_enqueue_media( array( 'post' => 1 ) );
				wp_enqueue_script( 'admanzing-dropdown', ADMAZING_URL . '/js/jquery.dd.js', array(), ADMAZING_VERSION, false );				
				wp_enqueue_style( 'admanzing-options', ADMAZING_URL . '/css/options.css', array(), ADMAZING_VERSION, 'all' );
				wp_enqueue_script( 'admanzing-options', ADMAZING_URL . '/js/options.js', array(), ADMAZING_VERSION, false );				

				wp_enqueue_style( 'admanzing-css', ADMAZING_URL . '/css/dashboard.css', array(), ADMAZING_VERSION, 'all' );
				wp_enqueue_style( 'font-awesome', ADMAZING_URL . '/font-awesome/font-awesome.min.css', array(), ADMAZING_VERSION, 'all' );
				wp_enqueue_style( 'font-genericons', ADMAZING_URL . '/genericons/genericons.css', array(), ADMAZING_VERSION, 'all' );
			}

			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
		}
		
		function add_pointer_scripts() {
			
			$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );	
			if ( !in_array( 'admazing_welcome', $dismissed ) ) {
				$content = '<h3>Welcome to Admazing</h3>';
				$content .= '<p>Configure your Dashboard icons <a href="index.php?editAdmazingDash=1">here</a>,<br>your Dashboard look & feel <a href="options-general.php?page=admazing-options">here</a>,<br> or your login screen <a href="options-general.php?page=admazing-options&tab=login">here</a>.</p>';
				$this->admazing_add_pointer_scripts_js($content, '#menu-settings', 'admazing_welcome');
			}
		}
		
		function admazing_add_pointer_scripts_js($content, $handler, $pointer) {
			?>
			<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function($) {
				$('<?php echo $handler; ?>').pointer({
					content: '<?php echo $content; ?>',
					position: {
						edge: 'left',
						align: 'center'
					},
					close:  function() {
						$.post( ajaxurl, {
							pointer: '<?php echo $pointer; ?>',
							action: 'dismiss-wp-pointer'
						});
					}
				}).pointer('open');
			});
			//]]>
			</script>
			<?php
		}

		// Common functions
		function get_safe($field, $un_set='', $mode='paranoid') {
			$value = ((isset($_GET[$field])) ? $_GET[$field] : $un_set);
			if ( get_magic_quotes_gpc()) $value = stripslashes_deep($value);
			if ($mode=='unsafe') return $value;
			if ($mode=='int') return intval($value);
			if ($mode=='paranoid') return mysql_real_escape_string(htmlspecialchars(strip_tags($value)));
		}
		function post_safe($field, $un_set='', $mode='paranoid') {
			$value = ((isset($_POST[$field])) ? $_POST[$field] : $un_set);
			if ( get_magic_quotes_gpc()) $value = stripslashes_deep($value);
			if ($mode=='unsafe') return $value;
			if ($mode=='int') return intval($value);
			if ($mode=='paranoid') return mysql_real_escape_string(htmlspecialchars(strip_tags($value)));
		}
		function iterate_menus($menu_opts, $parent, $deep) {
			foreach ($menu_opts as $s) {
				if ($s->menu_item_parent == $parent) {
					echo '<option value="' . $s->ID . '">' . str_repeat(" &gt; ", $deep) . $s->title . '</option>';
					$this->iterate_menus($menu_opts, $s->ID, $deep+1);
				}
			}
		}
		function adminize_url($type, $element, $nav_el='') {
			
			if (substr($element,0,4) == 'http') return $element;
			
			if ($type=='admin_link') {
				if (strpos($element, '.php') !== false) return $element;
				return 'admin.php?page=' . $element;
			}

			if ($type=='post_type') return 'post.php?post=' . $nav_el->object_id . '&action=edit';

			if ($type=='taxonomy') return 'edit-tags.php?action=edit&taxonomy=' . $nav_el->object . '&tag_ID=' . $nav_el->object_id . '&post_type=post';
			
			if (isset($nav_el->url)) return $nav_el->url;
			
			return '';
			
		}
		//Dashboard
		function print_dashboard_menu($pos_top=false, $widget_info=array(), $force_edition=false) {

			global $AdmazingOptions;

			echo '<div class="admazing-icons' . (($this->editDashboard || $force_edition) ? ' admazing-sortable' : '') . '"><ul class="' . $AdmazingOptions['icons_size'] . ' ' . $AdmazingOptions['icons_skin'] . '">';

			$display_dash_menu = $AdmazingOptions['dashboard_menu'];
			if ($this->editDashboard || $force_edition) $display_dash_menu = $AdmazingOptions['dashboard_menu_edit'];

			while ($opt = current($display_dash_menu)) {
				//echo key($display_dash_menu).'<br />';
				$keyx = key($display_dash_menu);
				echo '<li id="admazing-icon-' . $keyx . '">';
				if ($this->editDashboard || $force_edition) echo '<a href="#" class="delete_icon" onclick="admazingDelIcon(' . $keyx . '); return false;" title="' . __('Delete','admazing') . '"></a>';
				$color=''; if ($opt['color'] != '') $color = ' style="color:' . $opt['color'] . '" ';
				$blank=''; if ($opt['blank'] == 1) $blank = ' target="_blank" ';
				echo '<a href="' . $opt['url'] . '" title="' . $opt['title'] . '"' . $color . $blank . ' class="' . $opt['font'] . '"><strong>' . json_decode('"\u' . dechex($opt['icon']) . '"') . '</strong></a>';
				echo '<span>' .  $opt['title'] . '</span></li>';

				next($display_dash_menu);
			}

			if ($this->editDashboard || $force_edition) {
				echo '<li class="new"><a href="#" title="' . __('Add icon', 'admazing') . '" class="fa fa-plus-circle"></a><span>' . __('Add icon', 'admazing') . '</span></li>';
				echo '<li class="save"><a href="#" title="' . __('Save changes', 'admazing') . '" class="fa fa-check-circle"></a><span>' . __('Save changes', 'admazing') . '</span></li>';
				echo '<li class="forget"><a href="#" title="' . __('Forget changes', 'admazing') . '" class="fa fa-times-circle"></a><span>' . __('Forget changes', 'admazing') . '</span></li>';
			}
			echo '</ul></div>';
		}

		function print_dashboard_top() {
			if (!$this->isDashboard) return;
			
			global $AdmazingOptions;
			?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				admazing_pos='div.wrap h2:first'; if (jQuery('#welcome-panel').length != 0) admazing_pos='#welcome-panel:last';
				jQuery('div.metabox-holder-top').insertAfter(admazing_pos).show();
			});
			</script>
			<div class="metabox-holder-top metabox-holder columns-1" style="display:none">
				<div class="postbox" id="admazing-widget">
				<?php
				if ($AdmazingOptions['dashboard_title'] != '') {
				?>
					<div title="" class="handlediv"><br></div>
					<h3 class="hndle"><span><?php echo $AdmazingOptions['dashboard_title']; ?></span></h3>
				<?php
				}
				?>
					<div class="inside"><?php $this->print_dashboard_menu(); ?></div>
				</div>
			</div>
			<?php
		}

		function ajax() {
			global $AdmazingOptions;
			$this->init(true);
			
			switch ($this->post_safe('do')) {

				case 'sort':
					$newSort=array(); $n=0;

					foreach ($this->post_safe('admazing-icon', array(), 'unsafe') as $icon) {
						$icon = intval($icon);
						if (isset($AdmazingOptions['dashboard_menu_edit'][$icon])) {
							$newSort[$n] = $AdmazingOptions['dashboard_menu_edit'][$icon];
							$n++;
						}
					}
					if (count($newSort) == count($AdmazingOptions['dashboard_menu_edit'])) {
						$AdmazingOptions['dashboard_menu_edit'] = $newSort;
						update_option('admazing_opts', $AdmazingOptions);
						echo '1';
					} else {
						echo '0';
					}
					break;

				case 'del':
					if (isset($AdmazingOptions['dashboard_menu_edit'][$this->post_safe('id')])) {
						unset($AdmazingOptions['dashboard_menu_edit'][$this->post_safe('id')]);
						update_option('admazing_opts', $AdmazingOptions);
						echo '1';
					} else {
						echo '0';
					}
					break;

				case 'add':

					$title=$this->post_safe('title');
					$adminside=$this->post_safe('adminside');
					$url=$this->post_safe('url');
					$icon=$this->post_safe('icon');
					$menu=$this->post_safe('menu');
					$blank=$this->post_safe('blank');
					$color=$this->post_safe('color');
					$font=$this->post_safe('font');
					
					if ($menu==0) {
						$url=$this->adminize_url('admin_link', $url);

					} else if ($menu!='custom') {
						$menu_item = get_post($url);
						$menu_item = wp_setup_nav_menu_item($menu_item);
						if ($adminside != '1') {
							$url = $menu_item->url;
						} else {
							$url = $this->adminize_url($menu_item->type, $url, $menu_item);
						}
					}
					if ($url != '') {
					
						$AdmazingOptions['dashboard_menu_edit'][] = array(
							'title' => $title,
							'url' => $url,
							'icon' => $icon,
							'blank' => $blank,
							'color' => $color,
							'font' => $font
							);
						update_option('admazing_opts', $AdmazingOptions);
						echo '1';
					
					} else {
						echo '0';
					}
					break;

				case 'updateDash':
					$this->print_dashboard_menu('unknown', array(), true);
					break;

				case 'saveDash':
					$AdmazingOptions['dashboard_menu'] = $AdmazingOptions['dashboard_menu_edit'];
					update_option('admazing_opts', $AdmazingOptions);
					$this->print_dashboard_menu('unknown', array(), false);
					break;

				case 'forgetDash':
					$AdmazingOptions['dashboard_menu_edit'] = $AdmazingOptions['dashboard_menu'];
					update_option('admazing_opts', $AdmazingOptions);
					$this->print_dashboard_menu('unknown', array(), false);
					break;

			}
			die();
		}

		function dash_footer () {
			require 'admazing_dash_config.php';
		}

		function my_image_sizes($sizes) {
			$addsizes = array(
				"new-size" => __( "New Size")
			);
			$newsizes = array_merge($sizes, $addsizes);
			return $newsizes;
		}
		
		function login_css() {

			global $AdmazingOptions;
			$this->init(true);

			$custom = $AdmazingOptions['login'];
			if ($custom['active']==1) :
			?>
			<style type="text/css">
			body.login {
				<?php echo $this->bg_css($custom['obmode'], $custom['obc1'], $custom['obc2']); ?>
			}
			<?php if ($custom['logo']!='' && $custom['logo']!='x') : ?>
			body.login h1 a {
				background: url(<?php echo $custom['logo']; ?>) no-repeat center center;
				width:auto;
			}
			<?php endif; ?>
			body.login form {
				<?php echo $this->bg_css($custom['dbmode'], $custom['dbc1'], $custom['dbc2']); ?>
			}
			body.login label {
				color:<?php echo $custom['lc']; ?>;
			}
			body.login input[type=text],
			body.login input[type=password] {
				color:<?php echo $custom['itc']; ?> !important;
				background:<?php echo $custom['itbg']; ?>;
				border-color:<?php echo $custom['itbc']; ?>;
			}
			body.wp-core-ui .button-primary {
				color:<?php echo $custom['btc']; ?>;
				<?php echo $this->bg_css($custom['btmode'], $custom['btc1'], $custom['btc2']); ?>
				border-color:<?php echo $custom['btbc']; ?>;
			}
			body.wp-core-ui .button-primary.focus,
			body.wp-core-ui .button-primary.hover,
			body.wp-core-ui .button-primary:focus,
			body.wp-core-ui .button-primary:hover {
				color:<?php echo $custom['btch']; ?>;
				<?php echo $this->bg_css($custom['btmodeh'], $custom['btc1h'], $custom['btc2h']); ?>
				<?php /* AL LLORO QUE NO ESTA IMPLEMENTAT
				border-color:<?php echo $custom['btbch']; ?>;*/?>
			}
			body.login #backtoblog a, .login #nav a {
				color:<?php echo $custom['loc']; ?>;
			}
			body.login #backtoblog a:hover, .login #nav a:hover {
				color:<?php echo $custom['loch']; ?>;
			}
			</style>
			<?php
			endif;
		}
		
		function login_logo_url() {
			global $AdmazingOptions;
			$this->init(true);

			$url = $AdmazingOptions['login']['url_logo'];
			if ($url == '') $url = home_url();
			return $url;
		}
		
		function admazing_custom_sizes( $sizes ) {
			return array_merge( $sizes, array(
				'loginLogoSize' => __('Login logo (Admazing)'),
			) );
		}

		function bg_css($how, $col1, $col2) {
			
			if ($how=='vertical') {
				$reply = 'background: ' . $col1 . ';';
				$reply .= 'background: -moz-linear-gradient(top, ' . $col1 . ' 0%, ' . $col2 . ' 100%);';
				$reply .= 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . $col1 . '), color-stop(100%,' . $col2 . '));';
				$reply .= 'background: -webkit-linear-gradient(top, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -o-linear-gradient(top, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -ms-linear-gradient(top, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: linear-gradient(to bottom, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $col1 . "', endColorstr='" . $col2 . "',GradientType=0 );";
			} else 	if ($how=='horizontal') {
				$reply = 'background: ' . $col1 . ';';
				$reply .= 'background: -moz-linear-gradient(left, ' . $col1 . ' 0%, ' . $col2 . ' 100%);';
				$reply .= 'background: -webkit-gradient(linear, left top, right top, color-stop(0%,' . $col1 . '), color-stop(100%,' . $col2 . '));';
				$reply .= 'background: -webkit-linear-gradient(left, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -o-linear-gradient(left, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -ms-linear-gradient(left, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: linear-gradient(to right, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $col1 . "', endColorstr='" . $col2 . "',GradientType=1 );";
			} else if ($how=='diagonal1') {
				$reply = 'background: ' . $col1 . ';';
				$reply .= 'background: -moz-linear-gradient(-45deg, ' . $col1 . ' 0%, ' . $col2 . ' 100%);';
				$reply .= 'background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,' . $col1 . '), color-stop(100%,' . $col2 . '));';
				$reply .= 'background: -webkit-linear-gradient(-45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -o-linear-gradient(-45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -ms-linear-gradient(-45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: linear-gradient(135deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $col1 . "', endColorstr='" . $col2 . "',GradientType=1 );";
			} else 	if ($how=='diagonal2') {
				$reply = 'background: ' . $col1 . ';';
				$reply .= 'background: -moz-linear-gradient(45deg, ' . $col1 . ' 0%, ' . $col2 . ' 100%);';
				$reply .= 'background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,' . $col1 . '), color-stop(100%,' . $col2 . '));';
				$reply .= 'background: -webkit-linear-gradient(45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -o-linear-gradient(45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -ms-linear-gradient(45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: linear-gradient(45deg, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $col1 . "', endColorstr='" . $col2 . "',GradientType=1 );";
			} else if ($how=='radial') {
				$reply = 'background: ' . $col1 . ';';
				$reply .= 'background: -moz-radial-gradient(center, ellipse cover, ' . $col1 . ' 0%, ' . $col2 . ' 100%);';
				$reply .= 'background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,' . $col1 . '), color-stop(100%,' . $col2 . '));';
				$reply .= 'background: -webkit-radial-gradient(center, ellipse cover, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -o-radial-gradient(center, ellipse cover, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: -ms-radial-gradient(center, ellipse cover, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= 'background: radial-gradient(ellipse at center, ' . $col1 . ' 0%,' . $col2 . ' 100%);';
				$reply .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $col1 . "', endColorstr='" . $col2 . "',GradientType=1 );";
			} else {
				$reply = 'background: ' . $col1 . ';';
			}
			
			return $reply;
		}
	}
}

?>