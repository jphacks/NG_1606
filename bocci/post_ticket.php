<?php /* Template Name: post_ticket */
?>

<?php

$sid = $_GET['sid'];
$venue = $_GET['venue'];

$ticket = array();
$ticket['post_status'] = 'publish';
$ticket['post_type'] = 'tickets';
$ticket['post_title'] = $sid;
$ticket['post_content'] = $sid;
$ticket['post_author'] = $sid;
$ticket['tax_input'] = [array(
  'venue' => $venue
)];

$post_id = wp_insert_post($ticket);
wp_set_object_terms($post_id, $venue, 'venue', true);

$query = new WP_Query(array('venue' => $venue));
$num_tickets = $query->found_posts;
// print_r($query);
// echo "num tickets: $numTickets";
$term = get_term_by('name', $venue, 'venue');

if($num_tickets == 1 || mb_strlen($term->description) < 1){
  $result_url =  home_url('/') . "finding?sid=$sid&venue=$venue";
}else{
  $result_url =  home_url('/') . "result?sid=$sid&venue=$venue";
}
?>
<script type="text/javascript">
window.location.replace('<?php echo $result_url; ?>');
</script>
