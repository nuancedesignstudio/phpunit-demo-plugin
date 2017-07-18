<?php

/*
Plugin Name:PHPUnit Demo Plugin
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: A Basic WordPress Plugin for an accompanying article
Version:     1.0
Author:      Karan NA Gupta
Author URI:  http://nuancedesignstudio.in
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

/*
 * Add a meta key with a default value when a new user is created
 * 
 *  @param $user WP_User user object
 */
add_action( 'user_register', 'nds_custom_meta_add', 11, 1 );
function nds_custom_meta_add( $user_id ) {
    
    $user_info = get_userdata( $user_id );
    $user_roles = $user_info->roles; 
    
    if ( in_array( "editor", $user_roles ) ){
        $preferred_browser = "chrome";
        add_user_meta($user_id, 'preferred_browser', $preferred_browser);
    }

}

// add the field to user's own profile editing screen
add_action(
    'edit_user_profile', 'nds_display_usermeta_field_browser'
);
 
// add the field to user profile editing screen
add_action(
    'show_user_profile', 'nds_display_usermeta_field_browser'
);

/**
 * The field on the editing screens.
 *
 * @param $user WP_User user object
 */
function nds_display_usermeta_field_browser($user)
{
    if( $user->has_cap('editor') ) {
            
        ?>
        <h3>What's your favorite browser?</h3>
        <table class="form-table">
            <tr>
                <th>
                    <label for="preferred_browser">Preferred Browser</label>
                </th>
                <td>
                    <input type="text"
                           class="regular-text ltr"
                           id="preferred_browser"
                           name="preferred_browser"
                           value="<?= esc_attr(get_user_meta($user->ID, 'preferred_browser', true)); ?>"
                           title="Please use YYYY-MM-DD as the date format."
                           required>
                    <p class="description">
                        Please enter your preferred browser.
                    </p>
                </td>
            </tr>
        </table>
        <?php
    }
}
  

/**
 * The save action.
 *
 * @param $user_id int the ID of the current user.
 *
 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
 */
function nds_update_usermeta_field_browser($user_id)
{
    // check that the current user have the capability to edit the $user_id
    if (!current_user_can('edit_user', $user_id) ) {
        return false;
    }
 
    // crete/update user meta for the $user_id
    if( isset($_POST['preferred_browser'])) {
        return update_user_meta($user_id, 'preferred_browser', $_POST['preferred_browser']);    
    }    
}

// add the save action to user's own profile editing screen update
add_action(
    'personal_options_update', 'nds_update_usermeta_field_browser'
);
 
// add the save action to user profile editing screen update
add_action(
    'edit_user_profile_update', 'nds_update_usermeta_field_browser'
);
