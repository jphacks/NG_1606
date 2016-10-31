<?php /* Template Name: update_venue */
$term_id = $_GET['term_id'];
$comment = $_GET['comment'];

wp_update_term($term_id, 'venue', array(
  'description' => $comment
));
?>

