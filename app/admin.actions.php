<?php 
	/**
	 * 
	 */
	class NhlStats_Admin
	{
		public static function init()
		{
			require_once(AMB1_PLUGIN_PATH . 'nhl.api.php');
			add_action('admin_menu', array('NhlStats_Admin', 'adminPages') );
			
			add_action('admin_enqueue_scripts', array('NhlStats_Admin', 'loadAdminScripts') );

			add_action('wp_ajax_by_player', array('NhlStats_API', 'searchByPlayer') );
			add_action('wp_ajax_player_stats', array('NhlStats_API', 'playerStats') );
		}

		public static function adminPages()
		{
			add_menu_page(
	      'NHL Stats',
	      'NHL Stats',
	      'manage_options', 
	      'nhl-stats',
	      function()
	      {
	        require('views/index.php');
	      },
	      'dashicons-heart',
	      20
	    );

	    add_submenu_page(
      'nhl-stats',
      'Settings',
      'Settings',
      'manage_options', 
      'nhl-stats-settings',
      function()
      {
        require('views/settings.php');
      }
    );
		}

		public static function loadAdminScripts()
		{
			wp_register_script( 'nhlstats_functions.js', plugin_dir_url( __FILE__ ) . 'static/js/functions.js', array('jquery'), PLUGIN_VERSION, true);
      wp_register_script( 'ss_ajax.js', plugin_dir_url( __FILE__ ) . 'static/js/ajax.js', array('jquery'), PLUGIN_VERSION, true);
      wp_enqueue_script( 'nhlstats_functions.js');
      wp_enqueue_script( 'ss_ajax.js');
		}
	}