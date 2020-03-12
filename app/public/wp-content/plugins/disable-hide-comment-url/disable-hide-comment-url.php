<?php

/*
Plugin Name: Disable / Hide Comment URL
Plugin URI: http://www.digimantra.com/
Description: Lets you hide the URL/Website input field from the WordPress inbuilt comments block.
Author: Sachin Khosla
Version: 1.0
Author URI: http://www.digimantra.com/
*/

/**
 * @desc Function to unset the URL/website field from the comments panel
 * @param Array $fields
 * @return Array 
 */
function disable_comment_url($fields)
{
    
 
    unset($fields['url']);
 
    return $fields;
}

/**
 * Now add the folder using WP's add_filder() function.
 * We hook our function disable_comment_url with the filter comment_form_default_fields
 */
add_filter('comment_form_default_fields','disable_comment_url');
?>
