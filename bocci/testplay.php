<?php /* Template Name: testplay */
get_header();

$sid = $_GET['sid'];
$venue = $_GET['venue'];

$term = get_term_by('name', $venue, 'venue');

// wp_update_term($term->term_id, 'venue', array(
//   'description' => 'testcomment'
// ));
?>
<h1>行き先を入力</h1>
<select name="venue" id="venue">
  <option value="北部食堂">北部食堂</option>
  <option value="南部食堂">南部食堂</option>
  <option value="ダイニングフォレスト">ダイニングフォレスト</option>
</select>
<h1>学籍番号を入力</h1>
<input type="text" name="sid" id="sid" class="input-large">
<input type="button" id="update_venue-btn" value="次へ">


<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script>
$(document).ready(function(){
  $("#update_venue-btn").click(function() {
    // if(!window.confirm('<?php echo $venue;?> の募集を締め切ります。')){
      // return;
    //} 
    var post_url = "<?php echo home_url('/'); ?>post_ticket?sid=" + 
                    $("#sid").val() + "&venue=" + 
                    $("#venue").val();
    window.location.href = post_url;
    return;
    console.log(post_url);
    $.ajax({
      type: "POST",
      url: post_url,
      success: function(data, dataType) {
        console.log(data);
        console.log($('#update_venue-btn'));
        $('#update_venue-btn').val('設定完了！').attr('disabled', 'disabled');
        window.location.href='<?php echo home_url('/')?>result<?php echo "?sid=$sid&venue=$venue"; ?>';
      }
    }).css('cursor', 'pointer');
  });
});
</script>
<?php get_footer(); ?>
