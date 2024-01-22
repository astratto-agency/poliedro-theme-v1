<?php

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Theme activation
 */
if (is_admin() && isset($_GET['activated']) && 'themes.php' == $GLOBALS['pagenow']) {
    wp_redirect(admin_url('themes.php?page=theme_activation_options'));
    exit;
}

function p0l_theme_activation_options_init() {
    register_setting(
        'p0l_activation_options',
        'p0l_theme_activation_options'
    );
}
add_action('admin_init', 'p0l_theme_activation_options_init');

function p0l_activation_options_page_capability() {
    return 'edit_theme_options';
}
add_filter('option_page_capability_p0l_activation_options', 'p0l_activation_options_page_capability');

function p0l_theme_activation_options_add_page() {
    $p0l_activation_options = p0l_get_theme_activation_options();

    if (!$p0l_activation_options) {
        add_theme_page(
            __('Theme Activation', 'p0l'),
            __('Theme Activation', 'p0l'),
            'edit_theme_options',
            'theme_activation_options',
            'p0l_theme_activation_options_render_page'
        );
    } else {
        if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'theme_activation_options') {
            flush_rewrite_rules();
            wp_redirect(admin_url('themes.php'));
            exit;
        }
    }
}
add_action('admin_menu', 'p0l_theme_activation_options_add_page', 50);

function p0l_get_theme_activation_options() {
    return get_option('p0l_theme_activation_options');
}

function p0l_theme_activation_options_render_page() { ?>
    <div class="wrap">
        <h2><?php printf(__('%s Theme Activation', 'p0l'), wp_get_theme()); ?></h2>
        <div class="update-nag">
            <?php _e('These settings are optional and should usually be used only on a fresh installation', 'p0l'); ?>
        </div>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php settings_fields('p0l_activation_options'); ?>
            <table class="form-table">


                <tr valign="top"><th scope="row"><?php _e('Create static front page?', 'p0l'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Create static front page?', 'p0l'); ?></span></legend>
                            <select name="p0l_theme_activation_options[create_front_page]" id="create_front_page">
                                <option selected="selected" value="true"><?php echo _e('Yes', 'p0l'); ?></option>
                                <option value="false"><?php echo _e('No', 'p0l'); ?></option>
                            </select>
                            <p class="description"><?php printf(__('Create a page called Home and set it to be the static front page', 'p0l')); ?></p>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top"><th scope="row"><?php _e('Create additional pages?', 'p0l'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Create additional pages?', 'p0l'); ?></span>
                            </legend>
                            <select name="p0l_theme_activation_options[create_additional_page]"
                                    id="create_additional_page">
                                <option selected="selected" value="true"><?php echo _e('Yes', 'p0l'); ?></option>
                                <option value="false"><?php echo _e('No', 'p0l'); ?></option>
                            </select>
                            <p class="description"><?php printf(__('Create additional pages (Contatti, Privacy, etc)', 'p0l')); ?></p>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top"><th scope="row"><?php _e('Change permalink structure?', 'p0l'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Update permalink structure?', 'p0l'); ?></span></legend>
                            <select name="p0l_theme_activation_options[change_permalink_structure]" id="change_permalink_structure">
                                <option selected="selected" value="true"><?php echo _e('Yes', 'p0l'); ?></option>
                                <option value="false"><?php echo _e('No', 'p0l'); ?></option>
                            </select>
                            <p class="description"><?php printf(__('Change permalink structure to /&#37;postname&#37;/', 'p0l')); ?></p>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top"><th scope="row"><?php _e('Create navigation menu?', 'p0l'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Create navigation menu?', 'p0l'); ?></span></legend>
                            <select name="p0l_theme_activation_options[create_navigation_menus]" id="create_navigation_menus">
                                <option selected="selected" value="true"><?php echo _e('Yes', 'p0l'); ?></option>
                                <option value="false"><?php echo _e('No', 'p0l'); ?></option>
                            </select>
                            <p class="description"><?php printf(__('Create the Primary Navigation menu and set the location', 'p0l')); ?></p>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top"><th scope="row"><?php _e('Add pages to menu?', 'p0l'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Add pages to menu?', 'p0l'); ?></span></legend>
                            <select name="p0l_theme_activation_options[add_pages_to_primary_navigation]" id="add_pages_to_primary_navigation">
                                <option selected="selected" value="true"><?php echo _e('Yes', 'p0l'); ?></option>
                                <option value="false"><?php echo _e('No', 'p0l'); ?></option>
                            </select>
                            <p class="description"><?php printf(__('Add all current published pages to the Primary Navigation', 'p0l')); ?></p>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>

<?php }

function p0l_theme_activation_action() {

    if (!($p0l_theme_activation_options = p0l_get_theme_activation_options())) {
        return;
    }

    if (strpos(wp_get_referer(), 'page=theme_activation_options') === false) {
        return;
    }



    /* End - Create front page ----------------------------------------------------
       ---------------------------------------------------- --------------------------
    ---------------------------------------------------- End - Create front page */

    if ($p0l_theme_activation_options['create_additional_page'] === 'true') {
        $p0l_theme_activation_options['create_additional_page'] = false;
        $default_pages[] = __('Home', 'p0l');
        $default_pages[] = __('About', 'p0l');
        $default_pages[] = __('Blog', 'p0l');
        $default_pages[] = __('Contatti', 'p0l');
        $default_pages[] = __('Privacy Policy GDPR', 'p0l');
        $default_pages[] = __('Cookie Policy GDPR', 'p0l');
        $default_pages[] = __('Cerca', 'p0l');
    }



    /* End - Create front page ----------------------------------------------------
    ---------------------------------------------------- --------------------------
    ---------------------------------------------------- End - Create front page */
    if ($p0l_theme_activation_options['create_front_page'] === 'true') {
        $p0l_theme_activation_options['create_front_page'] = false;

        $existing_pages = get_pages();
        $temp = array();

        foreach ($existing_pages as $page) {
            $temp[] = $page->post_title;
        }

        $pages_to_create = array_diff($default_pages, $temp);

        foreach ($pages_to_create as $new_page_title) {

            if ($new_page_title == 'Home') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'template-home-child.php'
                );
            }
            if ($new_page_title == 'About') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'template-page.php'
                );
            }
            if ($new_page_title == 'Blog') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'archive.php'
                );
            }
            if ($new_page_title == 'Contatti') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'template-page.php'
                );
            }
            if ($new_page_title == 'Privacy Policy GDPR') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'template-policy.php'
                );
            }
            if ($new_page_title == 'Cookie Policy GDPR') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'template-policy.php'
                );
            }
            if ($new_page_title == 'Cerca') {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => 'search.php'
                );
            }

            $result = wp_insert_post($add_default_pages);
        }

        $home = get_page_by_title(__('Home', 'p0l'));
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home->ID);

        $home_menu_order = array(
            'ID' => $home->ID,
            'menu_order' => -1
        );
        wp_update_post($home_menu_order);
    }

    /* change_permalink_structure */
    if ($p0l_theme_activation_options['change_permalink_structure'] === 'true') {
        $p0l_theme_activation_options['change_permalink_structure'] = false;

        if (get_option('permalink_structure') !== '/%postname%/') {
            global $wp_rewrite;
            $wp_rewrite->set_permalink_structure('/%postname%/');
            flush_rewrite_rules();
        }
    }
    /* end - change_permalink_structure */

    /* create_navigation_menus */
    if ($p0l_theme_activation_options['create_navigation_menus'] === 'true') {
        $p0l_theme_activation_options['create_navigation_menus'] = false;

        $roots_nav_theme_mod = false;

        $primary_nav = wp_get_nav_menu_object(__('Primary Navigation', 'p0l'));

        if (!$primary_nav) {
            $primary_nav_id = wp_create_nav_menu(__('Primary Navigation', 'p0l'), array('slug' => 'primary_navigation'));
            $roots_nav_theme_mod['primary_navigation'] = $primary_nav_id;
        } else {
            $roots_nav_theme_mod['primary_navigation'] = $primary_nav->term_id;
        }

        if ($roots_nav_theme_mod) {
            set_theme_mod('nav_menu_locations', $roots_nav_theme_mod);
        }
    }
    /* end - create_navigation_menus */


    /* add_pages_to_primary_navigation */
    if ($p0l_theme_activation_options['add_pages_to_primary_navigation'] === 'true') {
        $p0l_theme_activation_options['add_pages_to_primary_navigation'] = false;

        $primary_nav = wp_get_nav_menu_object(__('Primary Navigation', 'p0l'));
        $primary_nav_term_id = (int) $primary_nav->term_id;
        $menu_items= wp_get_nav_menu_items($primary_nav_term_id);

        if (!$menu_items || empty($menu_items)) {
            $pages = get_pages();
            foreach($pages as $page) {
                $item = array(
                    'menu-item-object-id' => $page->ID,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                );
                wp_update_nav_menu_item($primary_nav_term_id, 0, $item);
            }
        }
    }
    /* end - add_pages_to_primary_navigation */
    update_option('p0l_theme_activation_options', $p0l_theme_activation_options);
}
add_action('admin_init','p0l_theme_activation_action');
/* End - Create front page ----------------------------------------------------
---------------------------------------------------- --------------------------
---------------------------------------------------- End - Create front page */


/*function p0l_deactivation() {
    delete_option('p0l_theme_activation_options');
}
add_action('switch_theme', 'p0l_deactivation');*/


function my_custom_sql_views() {
    global $wpdb;
    $wpdb->query("CREATE VIEW wp_articles AS SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'post';" );
    $wpdb->query("CREATE VIEW wp_pages AS SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'page';" );
    $wpdb->query("CREATE VIEW wp_products AS SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'product';" );
    $wpdb->query("CREATE VIEW wp_transient AS SELECT * FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE ('%\_transient\_%')" );
    $wpdb->query("CREATE VIEW wp_posts_categories AS SELECT `{$wpdb->prefix}posts`.ID AS 'ID', `{$wpdb->prefix}posts`.post_title AS 'Title', `{$wpdb->prefix}terms`.name AS 'Category' FROM `{$wpdb->prefix}posts` INNER JOIN `{$wpdb->prefix}term_relationships` ON `{$wpdb->prefix}posts`.ID = `{$wpdb->prefix}term_relationships`.object_id INNER JOIN `{$wpdb->prefix}terms` ON `{$wpdb->prefix}term_relationships`.term_taxonomy_id = `{$wpdb->prefix}terms`.term_id INNER JOIN `{$wpdb->prefix}term_taxonomy` ON `{$wpdb->prefix}term_relationships`.term_taxonomy_id = `{$wpdb->prefix}term_taxonomy`.term_taxonomy_id WHERE `{$wpdb->prefix}posts`.post_status = 'publish' AND `{$wpdb->prefix}posts`.post_type = 'post' AND `{$wpdb->prefix}term_taxonomy`.taxonomy = 'category' ORDER BY `{$wpdb->prefix}terms`.name;" );
    $wpdb->query("CREATE VIEW wp_products_categories AS SELECT `{$wpdb->prefix}posts`.ID AS 'ID', `{$wpdb->prefix}posts`.post_title AS 'Title', `{$wpdb->prefix}terms`.name AS 'Category' FROM `{$wpdb->prefix}posts` INNER JOIN `{$wpdb->prefix}term_relationships` ON `{$wpdb->prefix}posts`.ID = `{$wpdb->prefix}term_relationships`.object_id INNER JOIN `{$wpdb->prefix}terms` ON `{$wpdb->prefix}term_relationships`.term_taxonomy_id = `{$wpdb->prefix}terms`.term_id INNER JOIN `{$wpdb->prefix}term_taxonomy` ON `{$wpdb->prefix}term_relationships`.term_taxonomy_id = `{$wpdb->prefix}term_taxonomy`.term_taxonomy_id WHERE `{$wpdb->prefix}posts`.post_status = 'publish' AND `{$wpdb->prefix}posts`.post_type = 'product' AND `{$wpdb->prefix}term_taxonomy`.taxonomy = 'product_cat' ORDER BY `{$wpdb->prefix}terms`.name;" );
}

add_action("after_switch_theme", "my_custom_sql_views");
