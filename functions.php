<?php


//add_filter( 'tribe_ical_properties', 'tribe_ical_outlook_modify', 10, 2 );
function tribe_ical_outlook_modify( $content ) {
	$properties = preg_split ( '/$\R?^/m', $content );
	$searchValue = "X-WR-CALNAME";
	$fl_array = preg_grep('/^' . "$searchValue" . '.*/', $properties);
	$key = array_values($fl_array);
	$keynum = key($fl_array);
	unset($properties[$keynum]);
	$content = implode( "\n", $properties );
	return $content;
}

//add_filter( 'tribe_ical_feed_item', 'tribe_ical_modify_event', 10, 2 );
function tribe_ical_modify_event( $item, $eventPost ) {
	$searchValue = "DESCRIPTION";
	$fl_array = preg_grep('/^' . "$searchValue" . '.*/', $item);
	$key = array_values($fl_array);
	$keynum = key($fl_array);
	//$mod = substr($key[0], 0, 1000);
        $mod = $searchValue . ":Please visit for more information: " .get_permalink( $eventPost->ID );
	unset($item[$keynum]);
	$item[] = $mod;
	return $item;
}

//add_action( 'wp_head', 'template_name' );
function template_name() {
	if ( class_exists( 'TribeEvents' ) ) {
		if( tribe_is_month() && !is_tax() ) { // The Main Calendar Page
			$template = TribeEvents::getOption('tribeEventsTemplate');
			print_r( $template );
		}
	}
}

//add_action( 'wp_head', 'test_version' );
function test_version() {
	global $wp_version;
	$wp_34 = version_compare($wp_version, '3.4', '>=');
	var_dump($wp_version);
	var_dump($wp_34);	
}

// Try to stop the first event category from being added to the article classes
// (when the default page template is used) - Thanks Barry
//add_filter('post_class', 'remove_tribe_cat_once', 1);

function remove_tribe_cat_once(array $classes) {
	if ( class_exists('TribeEvents') ) {
		// The Main Calendar Page or Calendar Category Page
		if (( tribe_is_month() && !is_tax() ) || ( tribe_is_month() && is_tax() )) { 
			static $count = 0;
			if ($count++ === 0) {
				remove_filter('post_class', array(TribeEvents::instance(), 'post_class'));
			} else {
				add_filter('post_class', array(TribeEvents::instance(), 'post_class'));
				remove_filter('post_class', 'remove_tribe_cat_once');
			}
			return $classes;
		}
	}
}


//add_action( 'wp_enqueue_scripts', 'tribe_user_css_overrides' );
// function tribe_user_css_overrides () {
// 	$tec_user_css = '/events/events.css';
// 	if ( file_exists( get_stylesheet_directory() . $tec_user_css ) )
// 		wp_enqueue_style( 'tribe-user-css', get_stylesheet_directory_uri() . $tec_user_css ) ;	
// }

//add_action( 'teccc_add_legend_css', 'my_legend_css' );
function my_legend_css() {
	echo "#tribe-events #legend li { display: block; margin: 5px 40em; padding:0; }";
}

if( class_exists( 'Tribe_Events_Category_Colors' )) {

	teccc_reposition_legend('tribe_events_after_header');
	//teccc_remove_default_legend();
	//teccc_ignore_slug( 'just-show-up' );
	teccc_add_text_color( 'Red', '#f00' );

}

function list_active_plugin_file() {
	$activeplugins = get_option('active_plugins', array() );
	//print_r($activeplugins);
	foreach ( $activeplugins as $plugin ) {
		$parts =  explode( '/', $plugin );
		echo $parts[0] . "<br />\n";
		echo $parts[1] . "<br />\n";
		//echo ($plugin) . "<br />\n";
	}

}
//list_active_plugin_file();

function list_my_plugins() {
    $plugins = get_plugins();
    $activeplugins = get_option('active_plugins', array() );
    foreach ( $plugins as $plugin ) {
 		foreach ( $activeplugins as $activeplugin ) {
			$parts =  explode( '/', $activeplugin );
      		if ( strpos($plugin['PluginURI'], "github" ) ) { $uri = explode( '/', untrailingslashit($plugin['PluginURI']) ); }
			if( strpos($plugin['PluginURI'], $parts[0]) ) {
				if( $uri[4] == $parts[0] ) {
					echo $plugin['Name'] . " v." . $plugin['Version'] . " - " . untrailingslashit($plugin['PluginURI']) . "&nbsp;::&nbsp;";
					echo $uri[3] . ' - ' . $uri[4] . '/' . $parts[1];
					echo "<br />\n";
				}
			}
		}
	}
}
//if( is_admin() ) { list_my_plugins(); }

