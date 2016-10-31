<?php /* Template Name: result_json */

$venue = $_GET['venue'];

$tickets = get_posts(array(
  'post_type' => 'tickets',
  'numberposts' => -1,
  'tax_query' => array(
    array(
      'taxonomy' => 'venue',
      'field' => 'slug',
      'terms' => $venue
    )
  )
));

$i = 0;
foreach($tickets as $ticket){
  setup_postdata($ticket);
  // echo $ticket->post_author;
  $arr[$i] = $ticket;
  $i++;
}
header("Access-Control-Allow-Origin: *");
echo json_encode($arr);
?>
