<?php
/*
  Plugin Name: Affiliatize Me
  Plugin URI: https://wordpress.org/extend/plugins/affiliatize-me/
  Description: Make all your links affiliate links.
  Version: 1.0
  Author: Erin McIntyre
  Author URI: https://erinmcintyre.com/
  Text Domain: affiliatize-me
  Requires at least: 4.0
  Requires PHP: 5.2
  Tested up to: 6.4
  License: GPL2

  Copyright Erin McIntyre

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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// include only file
if (!defined('ABSPATH')) {
  wp_die('Do not open this file directly.');
}

load_plugin_textdomain('affiliatize');
add_action('wp_head', 'affiliatize_client');

// Loads the code for the website
function affiliatize_client()
{
  $blogdomain = parse_url(get_option('home'));  
  echo "<script type=\"text/javascript\">//<![CDATA[";
  echo "
  function affiliatize_loop() {
    if (!document.links) {
      document.links = document.getElementsByTagName('a');
    }
    var change_link = false;
    var force = '" . esc_attr(trim(get_option("affiliatize_force", '')))."';
    var affID = '" . esc_attr(trim(get_option("affiliatize_affID", '')))."';
    

    for (var t=0; t<document.links.length; t++) {
      var all_links = document.links[t];
      change_link = false;
     // console.log('here are all the links: ' + all_links);
      
      if(document.links[t].hasAttribute('onClick') == false) {

          
        if(force != '' && all_links.href.search(force) != -1) {
          // forced
           console.log('force ' + all_links.href);
          change_link = true;
        }
        
        

        if(change_link == true) {
          // console.log('Changed ' + all_links.href);
          all_links.href = all_links.href + affID;
          //console.log(document.links[t]);
         // console.log(all_links.href);
          document.links[t].setAttribute('onClick', 'javascript:window.open(\\'' + all_links.href.replace(/'/g, '') + '\\', \'_blank\', \'noopener\'); return false;');
          document.links[t].removeAttribute('target');
        }
      }
    }
  }
  
  // Load
  function affiliatize_load(func)
  {  
    var oldonload = window.onload;
    if (typeof window.onload != 'function'){
      window.onload = func;
    } else {
      window.onload = function(){
        oldonload();
        func();
      }
    }
  }

  affiliatize_load(affiliatize_loop);
  ";

  echo "//]]></script>\n\n";
}

// Administration interface

/* What to do when the plugin is activated? */
register_activation_hook(__FILE__, 'affiliatize_activate');

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'affiliatize_deactivate' );

function affiliatize_activate() {
  update_option("affiliatize_force", '');
  update_option("affiliatize_affID", '');
}

function affiliatize_deactivate() {
  delete_option('affiliatize_force');
  delete_option('affiliatize_affID');
}

add_action('admin_menu', 'affiliatize_admin_menu');
function affiliatize_admin_menu() {
  add_options_page(__('Set up your affiliate links', "affiliatize"), __('Affiliatize Me',"affiliatize"), 'manage_options', 'affiliatize', 'affiliatize_admin_options_page');
}

function affiliatize_admin_options_page() {
?>
  <div class="wrap">
  <h2><?php esc_html_e("Set up your affiliate links", "affiliatize"); ?></h2>
  <p>
  <form method="post" action="options.php">
  <?php wp_nonce_field('update-options'); ?>
  
  <?php esc_html_e("This plugin searches links on your site for the domain that you specify here, adds your affiliate ID, and makes the link open in a new tab when clicked.","affiliatize"); ?><br />
  
  <br />

  
  <?php esc_html_e("Affiliate link domain (for example, if you are an Amazon affiliate, you'd enter 'amazon.com' (without the quotes) here:","affiliatize"); ?><br />
  <input class="regular-text code" name="affiliatize_force" type="text" id="affiliatize_force" value="<?php echo esc_attr(get_option('affiliatize_force', '')); ?>" /><br /><br />

  <?php esc_html_e("String to add to link to turn it into an affiliate link (for example, most Amazon affiliate links are in this format: 'amazon.com?tag=youraffiliateID', so you'd enter '?tag=youraffiliateID' (without the quotes) here:","affiliatize"); ?><br />
  <input class="regular-text code" name="affiliatize_affID" type="text" id="affiliatize_affID" value="<?php echo esc_attr(get_option('affiliatize_affID', '')); ?>" /><br /><br />

<!-- <p>Like the plugin? <a href="https://wordpress.org/support/plugin/affiliatize-me/reviews/#new-post" target="_blank">Please rate it ★★★★★.</a> Thank you!</p>
</p> -->

  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="page_options" value="affiliatize_force,affiliatize_affID" />
  <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
  
    
  
  

  </form>
  </p>
  </div>
<?php
}

function affiliatize_plugin_action_links( $links, $file ) {
  if ( $file == plugin_basename( dirname(__FILE__).'/affiliatize.php' ) ) {
    $settings = '<a href="options-general.php?page=affiliatize">'.__('Settings', "affiliatize").'</a>';
    array_unshift($links, $settings);
  }
  
  return $links;
}

add_filter( 'plugin_action_links', 'affiliatize_plugin_action_links', 10, 2 );

function affiliatize_wp_kses_wf($html)
  {
      add_filter('safe_style_css', function ($styles) {
            $styles_wf = array(
                'text-align',
                'margin',
                'color',
                'float',
                'border',
                'background',
                'background-color',
                'border-bottom',
                'border-bottom-color',
                'border-bottom-style',
                'border-bottom-width',
                'border-collapse',
                'border-color',
                'border-left',
                'border-left-color',
                'border-left-style',
                'border-left-width',
                'border-right',
                'border-right-color',
                'border-right-style',
                'border-right-width',
                'border-spacing',
                'border-style',
                'border-top',
                'border-top-color',
                'border-top-style',
                'border-top-width',
                'border-width',
                'caption-side',
                'clear',
                'cursor',
                'direction',
                'font',
                'font-family',
                'font-size',
                'font-style',
                'font-variant',
                'font-weight',
                'height',
                'letter-spacing',
                'line-height',
                'margin-bottom',
                'margin-left',
                'margin-right',
                'margin-top',
                'overflow',
                'padding',
                'padding-bottom',
                'padding-left',
                'padding-right',
                'padding-top',
                'text-decoration',
                'text-indent',
                'vertical-align',
                'width',
                'display',
            );

            foreach ($styles_wf as $style_wf) {
                $styles[] = $style_wf;
            }
            return $styles;
        });

        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['input'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'data-*' => true,
            'size' => true,
            'disabled' => true
        );

        $allowed_tags['textarea'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'data-*' => true,
            'cols' => true,
            'rows' => true,
            'disabled' => true,
            'autocomplete' => true
        );

        $allowed_tags['select'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'data-*' => true,
            'multiple' => true,
            'disabled' => true
        );

        $allowed_tags['option'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'selected' => true,
            'data-*' => true
        );
        $allowed_tags['optgroup'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'selected' => true,
            'data-*' => true,
            'label' => true
        );

        $allowed_tags['a'] = array(
            'href' => true,
            'data-*' => true,
            'class' => true,
            'style' => true,
            'id' => true,
            'target' => true,
            'data-*' => true,
            'role' => true,
            'aria-controls' => true,
            'aria-selected' => true,
            'disabled' => true
        );

        $allowed_tags['div'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'data-*' => true,
            'role' => true,
            'aria-labelledby' => true,
            'value' => true,
            'aria-modal' => true,
            'tabindex' => true
        );

        $allowed_tags['li'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'data-*' => true,
            'role' => true,
            'aria-labelledby' => true,
            'value' => true,
            'aria-modal' => true,
            'tabindex' => true
        );

        $allowed_tags['span'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'data-*' => true,
            'aria-hidden' => true
        );

        $allowed_tags['style'] = array(
            'class' => true,
            'id' => true,
            'type' => true
        );

        $allowed_tags['fieldset'] = array(
            'class' => true,
            'id' => true,
            'type' => true
        );

        $allowed_tags['link'] = array(
            'class' => true,
            'id' => true,
            'type' => true,
            'rel' => true,
            'href' => true,
            'media' => true
        );

        $allowed_tags['form'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'method' => true,
            'action' => true,
            'data-*' => true
        );

        $allowed_tags['script'] = array(
            'class' => true,
            'id' => true,
            'type' => true,
            'src' => true
        );

        echo wp_kses($html, $allowed_tags);

        add_filter('safe_style_css', function ($styles) {
            $styles_wf = array(
                'text-align',
                'margin',
                'color',
                'float',
                'border',
                'background',
                'background-color',
                'border-bottom',
                'border-bottom-color',
                'border-bottom-style',
                'border-bottom-width',
                'border-collapse',
                'border-color',
                'border-left',
                'border-left-color',
                'border-left-style',
                'border-left-width',
                'border-right',
                'border-right-color',
                'border-right-style',
                'border-right-width',
                'border-spacing',
                'border-style',
                'border-top',
                'border-top-color',
                'border-top-style',
                'border-top-width',
                'border-width',
                'caption-side',
                'clear',
                'cursor',
                'direction',
                'font',
                'font-family',
                'font-size',
                'font-style',
                'font-variant',
                'font-weight',
                'height',
                'letter-spacing',
                'line-height',
                'margin-bottom',
                'margin-left',
                'margin-right',
                'margin-top',
                'overflow',
                'padding',
                'padding-bottom',
                'padding-left',
                'padding-right',
                'padding-top',
                'text-decoration',
                'text-indent',
                'vertical-align',
                'width'
            );

            foreach ($styles_wf as $style_wf) {
                if (($key = array_search($style_wf, $styles)) !== false) {
                    unset($styles[$key]);
                }
            }
            return $styles;
      });
  }
