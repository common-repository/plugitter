<?php
/*
Plugin Name: Plugitter
Plugin URI: http://www.datatendencias.com.ar/plugitter
Description: Widget showing your Twitter followers ranking
Version: 1.2
Author: Plugitter
Author URI: http://www.twitter.com/plugitter
Text domain: plugitter
License: GPL2
*/

class wp_ct_ranking extends WP_Widget {

	// constructor
	function wp_ct_ranking() {
    	parent::WP_Widget(false, $name = __('Twitter Ranking', 'plugitter') );
	}

	// widget form creation
	function form($instance) {
		// Check values
		if( $instance) {
		     $titulo = esc_attr($instance['titulo']);
		     $top_cuantos = intval($instance['top_cuantos']);
		     $screen_name = esc_attr($instance['screen_name']);
			 $hide_tweets = intval($instance['hide_tweets']);
		} else {
		     $titulo = '';
		     $top_cuantos = 0;
		     $screen_name = '';
			 $hide_tweets = 0;
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id('titulo'); ?>"><?php _e('Widget Title', 'plugitter'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>" name="<?php echo $this->get_field_name('titulo'); ?>" type="text" value="<?php echo $titulo; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('screen_name'); ?>"><?php _e('Twitter UserName:', 'plugitter'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" type="text" value="<?php echo $screen_name; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('top_cuantos'); ?>"><?php _e('How Many:', 'plugitter'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('top_cuantos'); ?>" name="<?php echo $this->get_field_name('top_cuantos'); ?>" type="text" value="<?php echo $top_cuantos; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('hide_tweets'); ?>"><?php _e('Show `Tweets` column:', 'plugitter'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('hide_tweets'); ?>" name="<?php echo $this->get_field_name('hide_tweets'); ?>" type="checkbox" value="1" <?php checked( $hide_tweets, 1 ); ?> />
		</p>

		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['titulo'] = strip_tags($new_instance['titulo']);
		$instance['top_cuantos'] = strip_tags($new_instance['top_cuantos']);
		$instance['screen_name'] = strip_tags($new_instance['screen_name']);
		$instance['hide_tweets'] = strip_tags($new_instance['hide_tweets']);
		return $instance;
 	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
		// these are the widget options
		$titulo = apply_filters('widget_title', $instance['titulo']);
		$top_cuantos = intval($instance['top_cuantos']);
		$screen_name = $instance['screen_name'];
		$hide_tweets = intval($instance['hide_tweets']);
		// registro el CSS
		wp_enqueue_style('ct_rank_table-css', plugins_url('style.css', __FILE__), null, null);
		// registro el JS
		wp_enqueue_script('ct_rank_table-js', 'http://www.datatendencias.com.ar/js/plugitter-1.2.js', array( 'jquery' ));
		// paso las variables al javascript
		wp_localize_script( 'ct_rank_table-js', 'Plugitter', array(
			'ajaxurl'		=> 'http://www.datatendencias.com.ar/app/api/ranking',
	        'cuantos'     	=> $top_cuantos,
	        'screen_name'	=> $screen_name,
	        'selectall'	=> __('Select all','plugitter'),
	        'nodata'	=> __('NO DATA', 'plugitter'),
	        'notfound'	=> __('{0} does not appear in the top {1}', 'plugitter'),
	        'congrats'	=> __('Congratulations <img class="ct_rank_img" src ="{0}" width=32 height=32 style="text-align: center; border-radius: 50%;"><a href="http://twitter.com/" >@{1}</a>!<br/>You ranked #{2} at {3}\'s ranking', 'plugitter'),
			'hide_tweets' => $hide_tweets
	    ));
		// comienzo a imprimir el widget
		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text">';
		// Check if title is set
		if ( $titulo ) {
		  echo $before_title . $titulo . $after_title;
		}
		?>
		<div id="ct_rank_mensaje"></div>
		<div class="ct_rank_table">
			<select id="ct_rank_categoria" name="categoria" style="width:100%">
			</select><br />
			<input class="ct_rank_search" name="search" id="ct_rank_usuario" type="text" value="" /><br />
			<table id="ct_rank_tabla">
				<thead>
				<tr>
					<th class="ct_rank_numeric">#</th>
					<th><?php _e('Picture', 'plugitter'); ?></th>
					<th abbr="4"><?php _e('Screen Name', 'plugitter'); ?></th>
					<th abbr="7" class="ct_rank_numeric"><?php _e('Followers', 'plugitter'); ?></th>
					<th abbr="1" class="ct_rank_numeric"><?php _e('Activity', 'plugitter'); ?></th>
					<?php
						if($hide_tweets==1) {
								echo '<th abbr="2" class="ct_rank_numeric">';
								_e('Tweets', 'plugitter');
								echo '</th>';
						};
					?>
				</tr>
				</thead>
				<tbody>
				</tbody>
			  	<tfoot>
			  		<tr>
			  			<td colspan=<?php echo 5 + $hide_tweets; ?>>
			  			<a href="http://www.datatendencias.com.ar/rankings?r=<?php echo $screen_name; ?>"><?php _e('view detailed table', 'plugitter'); ?></a>
			  			</td>
			  		</tr>
	        	</tfoot>
	        </table>
		</div>
		</div>
		<?php
		echo $after_widget;
   	}
}

// register widget
add_action('widgets_init', function(){ register_widget("wp_ct_ranking"); });

$plugin_dir = basename( dirname( __FILE__ ) );
load_plugin_textdomain( 'plugitter', null, $plugin_dir );

?>
