<?php
/*
Plugin Name: Thumb-Date
Plugin URI:  https://ledyba.org/
Description: Update post date from thumbnail metadata.
Version: 1.0
Author: PSI
Author URI: https://ledyba.org/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

function update_date_from_thumbnail($post_id, $post, $updated) {
  $thumb_id = get_post_thumbnail_id( $post_id );
  if(!$thumb_id) return;

  $meta = wp_get_attachment_metadata($thumb_id, false);
  if(!$meta) return;

  $meta = $meta['image_meta'];
  if(!$meta) return;

  $stamp = $meta['created_timestamp'];
  if(!$stamp) return;

  $post_date = date("Y-m-d H:i:s", $stamp);
  $post_date_gmt = get_gmt_from_date($post_date);

  if (!wp_is_post_revision($post_id)) {
    remove_action('save_post', 'update_date_from_thumbnail');
    wp_update_post(array(
      'ID'            => $post_id,
      'post_date'     => $post_date,
      'post_date_gmt' => $post_date_gmt,
      'post_category' => array(get_category_by_slug('photo')->cat_ID),
    ));
    add_action('save_post', 'update_date_from_thumbnail', 10, 3);
  }
}

add_action('save_post', 'update_date_from_thumbnail', 10, 3);
?>
