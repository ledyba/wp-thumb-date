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

function update_date_from_thumbnail($data, $postarr) {
  $post_id = $postarr['ID'];;
  $thumb_id = get_post_thumbnail_id( $post_id );
  if(!$thumb_id) return $data;

  $meta = wp_get_attachment_metadata($thumb_id, false);
  if(!$meta) return $data;

  $meta = $meta['image_meta'];
  if(!$meta) return $data;

  $stamp = $meta['created_timestamp'];
  if(!$stamp) return $data;

  $data['post_date'] = date("Y-m-d H:i:s", $stamp);
  $data['post_date_gmt'] = get_gmt_from_date($data['post_date']);
  

  return $data;
}

add_filter('wp_insert_post_data', 'update_date_from_thumbnail' , '99', 2 );

?>
