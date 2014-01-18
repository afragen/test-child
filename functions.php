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


//add_action( 'teccc_add_legend_css', 'my_legend_css' );
function my_legend_css() {
	echo "#tribe-events #legend li { display: block; margin: 5px 40em; padding:0; }";
}

if( class_exists( 'Tribe_Events_Category_Colors' )) {

	//teccc_reposition_legend('tribe_events_before_footer');
	//teccc_remove_default_legend();
	//teccc_ignore_slug( 'just-show-up' );
	teccc_add_text_color( 'Red', '#f00' );
	teccc_add_legend_view( 'upcoming' );
	teccc_add_legend_view( 'photo' );

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


add_action( 'tribe_events_after_the_title', 'my_category_description' );
function my_category_description() {
	global $wp_query;
	if( !isset( $wp_query->query_vars['post_type'] ) or !isset( $wp_query->query_vars['eventDisplay'] ) or !isset( $wp_query->queried_object ) ) return;
	if( $wp_query->query_vars['post_type'] === 'tribe_events' or $wp_query->query_vars['post_type'][0] === 'tribe_events' and $wp_query->query_vars['eventDisplay'] === 'upcoming' )
		echo '<div style="text-align:center;">' . $wp_query->queried_object->description . '</div>';
}

add_filter('teccc_legend_html', 'add_legend_explanation');
function add_legend_explanation($html) {
	echo '<div class="legend-explanation"> To focus on events from only one of these categories, just click on the relevant label. </div>'
		. $html;
}
