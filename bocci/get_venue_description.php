<?php /* Template Name: get_venue_description */
$venue = $_GET['venue'];

$term = get_term_by('name', $venue, 'venue');
echo strip_tags(term_description($term->term_id, 'venue'));
?>
