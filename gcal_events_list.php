<?php
/*
Plugin Name: GCal Events List
Plugin URI: http://digitaladoptive.wordpress.com/gcal-events-list
Description: GCal Events List generates a list of events from a public Google Calendar. You need the calendar ID to make it work.
Version: 0.1
Author: Carlo Daniele
Author URI: http://digitaladoptive.wordpress.com/
*/

/*  Copyright 2010  Carlo Daniele  (email : carloxdaniele@yahoo.it)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class gcalEventsList extends WP_Widget {
	function gcalEventsList(){
		add_action('wp_print_styles', 'add_styles');
		$widget_ops = array('description' => 'A widget that generates a list of events from a public Google Calendar');
		$this->WP_Widget('gcal-events-list', 'GCal Events List', $widget_ops);
	}
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		//open div
		echo $before_widget; 
		
		//widget title
		$title = apply_filters('widget_title', $instance['title']);		
				
		//google calendar parameters
		$params = array(
			'id' => $instance['calendar'], //calendar ID
			'orderby' => $instance['orderby'],
			'sortorder' => $instance['sortorder'],
			'max-results' => $instance['maxresults'], //ricordati che il parametro del gcal è max-results
			'start-min' => $instance['startmin'], //ricordati che il parametro del gcal è start-min
			'start-max' => $instance['startmax'] //ricordati che il parametro del gcal è start-max	
		);
		
		if(!empty($title)){
			echo $before_title . $title . $after_title;
		}
		
		if(!empty($params['id'])){
			getData($params);			
		}else{
			echo __('You shoud set the calendar ID to make this widget work');
		}
		//close div
		echo $after_widget;
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['calendar'] = strip_tags($new_instance['calendar']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['sortorder'] = $new_instance['sortorder'];
		$instance['maxresults'] = (int)$new_instance['maxresults'];
		$instance['startmin'] = $new_instance['startmin'];
		$instance['startmax'] = $new_instance['startmax'];
		return $instance;
	}
	function form($instance){
		//http://codex.wordpress.org/Function_Reference/esc_attr
		$title = esc_attr($instance['title']);
		$calendar = esc_attr($instance['calendar']);
		$orderby = esc_attr($instance['orderby']);
		$sortorder = esc_attr($instance['sortorder']);
		$maxresults = esc_attr($instance['maxresults']);
		$startmin = esc_attr($instance['startmin']);
		$startmax = esc_attr($instance['startmax']);
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title: 
				<input class="widefat" 
					id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>"
					type="text"
					value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('calendar'); ?>">Calendar ID: 
				<input class="widefat" 
					id="<?php echo $this->get_field_id('calendar'); ?>"
					name="<?php echo $this->get_field_name('calendar'); ?>"
					type="text"
					value="<?php echo $calendar; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order by'); ?>: </label>		
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">
				<option <?php if ( $instance['orderby'] == 'lastmodified'  ) echo 'selected="selected"'; ?>>lastmodified</option>
				<option <?php if ( $instance['orderby'] != 'lastmodified' ) echo 'selected="selected"'; ?>>starttime</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sortorder'); ?>"><?php echo __('Sort order'); ?>: </label>		
			<select id="<?php echo $this->get_field_id( 'sortorder' ); ?>" name="<?php echo $this->get_field_name( 'sortorder' ); ?>" class="widefat">
				<option <?php if ( $instance['sortorder'] == 'ascending' ) echo 'selected="selected"'; ?>>ascending</option>
				<option <?php if ( $instance['sortorder'] != 'ascending' ) echo 'selected="selected"'; ?>>descending</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('maxresults'); ?>"><?php echo __('Max results'); ?>: </label>		
			<select id="<?php echo $this->get_field_id( 'maxresults' ); ?>" name="<?php echo $this->get_field_name( 'maxresults' ); ?>" class="widefat">
				<option <?php if ( $instance['maxresults'] == 1 ) echo 'selected="selected"'; ?>>1</option>
				<option <?php if ( $instance['maxresults'] == 3 ) echo 'selected="selected"'; ?>>3</option>
				<option <?php if ( $instance['maxresults'] == 5 ) echo 'selected="selected"'; ?>>5</option>
				<option <?php if ( $instance['maxresults'] == 10 ) echo 'selected="selected"'; ?>>10</option>
				<option <?php if ( $instance['maxresults'] == 15 ) echo 'selected="selected"'; ?>>15</option>
				<option <?php if ( $instance['maxresults'] == 20 ) echo 'selected="selected"'; ?>>20</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('startmin'); ?>">Start min (YYYY-MM-DD): 
				<input class="widefat" 
					id="<?php echo $this->get_field_id('startmin'); ?>"
					name="<?php echo $this->get_field_name('startmin'); ?>"
					type="text"
					value="<?php echo $startmin; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('startmax'); ?>">Start max (YYYY-MM-DD): 
				<input class="widefat" 
					id="<?php echo $this->get_field_id('startmax'); ?>"
					name="<?php echo $this->get_field_name('startmax'); ?>"
					type="text"
					value="<?php echo $startmax; ?>" />
			</label>
		</p>
		<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("gcalEventsList");'));


function getData($params){
	extract($params, EXTR_SKIP);
	
	$calID = $params['id']; 
	$feed = "http://www.google.com/calendar/feeds/" . $calID . "/public/full?";
	$params = "orderby=". $params['orderby'] 
			. "&sortorder=" . $params['sortorder'] 
			. "&max-results=" . $params['max-results']
			. "&start-min=" . $params['start-min']
			. "&start-max=" . $params['start-max'];			

	$contents = @file_get_contents($feed . $params) or die(__('Bad request'));
	$xml = new SimpleXmlElement($contents);
	
	echo '<div id="eventslist">';
	foreach($xml->entry as $entry){
		$gd = $entry->children('http://schemas.google.com/g/2005');
		$start = strtotime($gd->when->attributes()->startTime);
		$end = strtotime($gd->when->attributes()->endTime);
		$dayName = __(date('l', $start));
		$dayNum = date('j', $start);
		$month = __(date('F', $start));
		$year = date('Y', $start);
		$date = $dayName . ', ' . $dayNum . ' ' . $month . ' ' . $year;
		$startTime = date('G:i', $start);
		$endTime = date('G:i', $end);
		
		echo '<p>';
		echo '<span class="gcelist_date">' . $date . '</span><br />';
		echo '<span class="gcelist_title">' . (string)$entry->title . '</span><br />';
		if($startTime != $endTime){
			echo '<span class="gcelist_hour">' . $startTime . " - " . $endTime . '</span>';
		}
		echo '</p>';
	}
	echo '</div>';
}

    function add_styles() {
        $myStyleUrl = WP_PLUGIN_URL . '/gcal_events_list/css/gcel-style.css';
        $myStyleFile = WP_PLUGIN_DIR . '/gcal_events_list/css/gcel-style.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('gcel_styles', $myStyleUrl);
            wp_enqueue_style( 'gcel_styles');
        }
    }