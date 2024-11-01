<?php
/**
 * Plugin Name:  WP News Desk Dashboard Feed
 * Plugin URI: 	 http://wpnewsdesk.com/
 * Description:  This plugin adds a widget to your WordPress Dashboard that pulls in the most recent posts from wpnewsdesk.com. The number of posts displayed, and the category of posts displayed, can both be configured.
 * Version: 	 1.0
 * Author: 		 Jean Galea
 * Author URI: 	 https://jeangalea.com
 * License: 	 GPL2
 * License 	URI: http://www.gnu.org/licenses/gpl-2.0.html
**/


// Creates the custom dashboard feed RSS box
function wpnewsdesk_dashboard_custom_feed_output() {
	
	$widget_options = wpnewsdesk_dashboard_options();
	
	// Variable for RSS feed
	$wpnewsdesk_feed = 'http://www.wpnewsdesk.com/feed/';			
	
	echo '<div class="rss-widget" id="wpnewsdesk-rss-widget">';
		wp_widget_rss_output(array(
			'url' 			=> $wpnewsdesk_feed,
			'title' 		=> 'Latest Posts from WP News Desk',
			'items' 		=> $widget_options['posts_number'],
			'show_summary' 	=> 0,
			'show_author' 	=> 0,
			'show_date' 	=> 0
		));
	echo "</div>";
}


// Function used in the action hook
function wpnewsdesk_add_dashboard_widgets() {	
	wp_add_dashboard_widget( 'wpnewsdesk_dashboard_custom_feed', 'Latest Posts from wpnewsdesk.com', 'wpnewsdesk_dashboard_custom_feed_output', 'wpnewsdesk_dashboard_setup' );
}


function wpnewsdesk_dashboard_options() {	
	$defaults = array( 'posts_number' => 10 );
	if ( ( !$options = get_option( 'wpnewsdesk_dashboard_custom_feed' ) ) || !is_array( $options ) )
		$options = array();
	return array_merge( $defaults, $options );
}


function wpnewsdesk_dashboard_setup() {
 
	$options = wpnewsdesk_dashboard_options();
 
	if ( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) && isset( $_POST['widget_id'] ) && 'wpnewsdesk_dashboard_custom_feed' == $_POST['widget_id'] ) {
		foreach ( array( 'posts_number', 'posts_feed' ) as $key )
				$options[$key] = $_POST[$key];
		update_option( 'wpnewsdesk_dashboard_custom_feed', $options );
	}
 
?>
 
		<p>
			<label for="posts_number"><?php _e('How many items?', 'wpnewsdesk_dashboard_custom_feed' ); ?>
				<select id="posts_number" name="posts_number">
					<?php for ( $i = 5; $i <= 10; $i = $i + 1 )
						echo "<option value='$i'" . ( $options['posts_number'] == $i ? " selected='selected'" : '' ) . ">$i</option>";
						?>
					</select>
				</label>
 		</p>

 
<?php
 }


// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action( 'wp_dashboard_setup', 'wpnewsdesk_add_dashboard_widgets' );