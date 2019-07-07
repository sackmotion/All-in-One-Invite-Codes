<?php

/**
 * Add the Settings Page to the All in One Invite Codes Menu
 */
function all_in_one_invite_codes_tree_menu() {
	add_submenu_page( 'edit.php?post_type=tk_invite_codes', __( 'All in One Invite Codes Settings', 'all_in_one_invite_codes_tree' ), __( 'Tree View', 'all_in_one_invite_codes_tree' ), 'manage_options', 'all_in_one_invite_codes_tree', 'all_in_one_invite_codes_tree_page' );
}

add_action( 'admin_menu', 'all_in_one_invite_codes_tree_menu' );

/**
 * Settings Page Content
 */
function all_in_one_invite_codes_tree_page() { ?>

    <style>

        ul.children {
            padding-left: 30px;
        }



    </style>
    <div id="post" class="wrap">

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">

                <div id="postbox-container-1" class="postbox-container">
                    A
                </div>
                <div id="postbox-container-2" class="postbox-container">
					<?php all_in_one_invite_codes_tree_tabs_content(); ?>
                </div>
            </div>
        </div>

    </div> <!-- .wrap -->
	<?php
}


/**
 * Settings Tabs Navigation
 *
 * @param string $current
 */
function all_in_one_invite_codes_tree_admin_tabs( $current = 'general' ) {
	$tabs = array( 'general' => 'General Statistics' );

	$tabs                           = apply_filters( 'all_in_one_invite_codes_tree_admin_tabs', $tabs );
	$tabs['invite_codes_tree']      = 'Invite Codes Tree';
	$tabs['invite_codes_user_tree'] = 'User Tree';


	echo '<h2 class="nav-tab-wrapper" style="padding-bottom: 0;">';
	foreach ( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='edit.php?post_type=tk_invite_codes&page=all_in_one_invite_codes_tree&tab=$tab'>$name</a>";
	}
	echo '</h2>';
}


function all_in_one_invite_codes_tree_tabs_content() {
	global $pagenow, $all_in_one_invite_codes; ?>
    <div id="poststuff">

		<?php

		// Display the Update Message
		if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) {
			echo '<div class="updated" ><p>All in One Invite Codes...</p></div>';
		}

		if ( isset ( $_GET['tab'] ) ) {
			all_in_one_invite_codes_tree_admin_tabs( $_GET['tab'] );
		} else {
			all_in_one_invite_codes_tree_admin_tabs( 'general' );
		}

		if ( $pagenow == 'edit.php' && $_GET['page'] == 'all_in_one_invite_codes_tree' ) {

			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = 'general';
			}

			switch ( $tab ) {
				case 'general' :
					$all_in_one_invite_codes_general = get_option( 'all_in_one_invite_codes_general' ); ?>
                    <div class="metabox-holder">
                        <div class="postbox all_in_one_invite_codes-metabox">
                            <div class="inside">
                                Add some general statistics

								<?php
								$invite_codes_stats = wp_count_posts( 'tk_invite_codes' );

								echo '<ul>';
								foreach ( $invite_codes_stats as $type => $count ) {
									echo '<li>' . $type . ': ' . $count . '</li>';
								}
								echo '</ul>';
								?>
                            </div><!-- .inside -->
                        </div><!-- .postbox -->
                    </div><!-- .metabox-holder -->
					<?php
					break;
				case 'invite_codes_tree' : ?>
                    <div class="metabox-holder">
                        <div class="postbox all_in_one_invite_codes-metabox">
                            <div class="inside">
								<?php
								add_filter( 'wp_list_pages', 'all_in_one_invite_codes_wp_list_pages_filter', 10, 3 );
								add_filter( 'post_type_link', 'all_in_one_invite_codes_list_pages_permalink_filter', 10, 2 );

								wp_list_pages( array(
									'post_type' => 'tk_invite_codes',
									'title_li'  => 'Invite Codes Flow'
								) );

								remove_filter( 'wp_list_pages', 'all_in_one_invite_codes_wp_list_pages_filter', 10, 3 );
								remove_filter( 'post_type_link', 'all_in_one_invite_codes_list_pages_permalink_filter', 10, 2 );
								?>
                            </div><!-- .inside -->
                        </div><!-- .postbox -->
                    </div><!-- .metabox-holder -->
					<?php
					break;

				case 'invite_codes_user_tree' : ?>
                    <div class="metabox-holder">
                        <div class="postbox all_in_one_invite_codes-metabox">
                            <div class="inside">
								<?php
								add_filter( 'wp_list_pages', 'all_in_one_invite_codes_user_tree_wp_list_pages_filter', 10, 3 );
								add_filter( 'post_type_link', 'all_in_one_invite_codes_list_pages_permalink_filter', 10, 2 );

								wp_list_pages( array(
									'post_type' => 'tk_invite_codes',
									'title_li'  => 'Invite Codes Flow'
								) );

								remove_filter( 'wp_list_pages', 'all_in_one_invite_codes_wp_list_pages_filter', 10, 3 );
								remove_filter( 'post_type_link', 'all_in_one_invite_codes_list_pages_permalink_filter', 10, 2 );
								?>
                            </div><!-- .inside -->
                        </div><!-- .postbox -->
                    </div><!-- .metabox-holder -->
					<?php
					break;

				default:
					do_action( 'all_in_one_invite_codes_tree_page_tab', $tab );

					break;
			}
		}
		?>
    </div> <!-- #poststuff -->
	<?php
}


function all_in_one_invite_codes_list_pages_permalink_filter( $permalink, $page ) {
	return get_edit_post_link( $page->ID );
}


function all_in_one_invite_codes_user_tree_wp_list_pages_filter( $html, $key, $values ) {

	foreach ( $values as $key => $value ) {
		$old_title = $value->post_title;

		$values[ $key ] = $value;

		$invite_key     = get_post_meta( $value->ID, 'tk_all_in_one_invite_code', true );
		$invite_status  = get_post_meta( $value->ID, 'tk_all_in_one_invite_code_status', true );
		$invite_options = get_post_meta( $value->ID, 'all_in_one_invite_codes_options', true );
		$avatar         = get_avatar_url( $value->ID );


		if ( $invite_options['email'] ) {
			$user      = get_user_by( 'email', $invite_options['email'] );
			$new_title = $invite_key . ' </a><br> <img src="' . $avatar . '" /> <br>Status: ' . $invite_status . ' <br> User: <a href="' . get_edit_user_link( $user->ID ) . '">  ' . $user->display_name;

		} else {
			$new_title = '';
		}


		$html = str_replace( $old_title, $new_title, $html );
	}

	return $html;
}

function all_in_one_invite_codes_wp_list_pages_filter( $html, $key, $values ) {

	foreach ( $values as $key => $value ) {
		$old_title = $value->post_title;

		$values[ $key ] = $value;

		$invite_key     = get_post_meta( $value->ID, 'tk_all_in_one_invite_code', true );
		$invite_status  = get_post_meta( $value->ID, 'tk_all_in_one_invite_code_status', true );
		$invite_options = get_post_meta( $value->ID, 'all_in_one_invite_codes_options', true );

		if ( $invite_options['email'] ) {
			$user      = get_user_by( 'email', $invite_options['email'] );
			$new_title = $invite_key . ' </a><br> <img src="' . $avatar . '" /> <br>Status: ' . $invite_status . ' <br> User: <a href="' . get_edit_user_link( $user->ID ) . '">  ' . $user->display_name;
		} else {
			$new_title = $invite_key . ' </a><br> Status: ' . $invite_status . ' <br>';
		}


		$html = str_replace( $old_title, $new_title, $html );
	}

	return $html;
}
