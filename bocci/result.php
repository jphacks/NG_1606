<?php /* Template Name: result*/
get_header();

$sid = $_GET['sid'];
$venue = $_GET['venue'];

$term = get_term_by('name', $venue, 'venue');

echo "<h1 id='shugo'>". $venue . " の " . 
  "<span id='venue-spot'></span>" . 
  "に集合します。</h1>";


// wp_update_term($term->term_id, 'venue', array(
//   'description' => 'testcomment'
// ));
?>
<ul class="nameData"></ul>

<input type="button" value="募集をやめる" id="close-btn">

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
var timerStopFlag = false;
loadTickets();
function loadTickets(){
    if(timerStopFlag) return;
    $.ajax({
      url: '<?php echo home_url('/') . "result_json/?venue=$venue"; ?>',
      dataType: 'json',
      success: function(data){
        if(data == null){
          $('#close-btn').val('募集を締め切りました。').attr('disabled', 'disabled');
          return;
        }
        console.log(data);
        $('.nameData > *').remove();
        $.each(data, function(i){
          var realtime = new Date();
          var dt1 = new Date(realtime.getFullYear(),realtime.getMonth(),realtime.getDate(),realtime.getHours(),realtime.getMinutes(),realtime.getSeconds());
          console.log("現在時刻dt1=",dt1);

//Safariに応
          var post_date = data[i].post_date;
          // console.log(post_date);
          var getyear = post_date.slice(0,4);
          var getmonth = post_date.slice(5,7);
          var getday = post_date.slice(8,10);
          var gethours = post_date.slice(11,13);
          var getminute = post_date.slice(14,16);
          var getseconds = post_date.slice(17,19);

          var dt2 = new Date(getyear,getmonth-1,getday,gethours,getminute,getseconds);
          // console.log(dt2);
          // console.log("post_date=",dt2);
          var diff = Math.floor((dt1-dt2)/1000); //差を秒で取得

// 学籍番  学部学年出席番号に分ける作業------------------------
          var stdnum = data[i].post_author;
          // 学部(2)年度(2)番号(2)(一部チェックディジット)
          var gakubu = stdnum.charAt(0);
          var gakunen = stdnum.slice(1,3);
          var person = stdnum.slice(3,4);
          var Gakubu; var Gakunen;

          switch (gakubu) {
              case '1':
                  Gakubu = "文学部";
                  break;
              case '2':
                  Gakubu = "経済学部";
                  break;
              case '3':
                  Gakubu = "法学部";
                  break;
              case '4':
                  Gakubu = "教育学部";
                  break;
              case '5':
                  Gakubu = "医学部";
                  break;
              case '6':
                  Gakubu = "情報文化学部";
                  break;
              case '7':
                  Gakubu = "理学部";
                  break;
              case '8':
                  Gakubu = "工学部";
                  break;
              case '9':
                  Gakubu = "農学部";
                  break;
              default:
                  Gakubu = "【学部不明】";
              }
// ------------------------------------------------------

          if(diff>60){  //60秒より多い場合
              $('.nameData').prepend("<li><span class='sid'>"+Gakubu+" 20"+gakunen+"年度入学</span><span class='dates'>"+ Math.floor(diff/60) + "分前の投稿</span></li>");
          }else{              //60秒に満たない場合
              $('.nameData').prepend("<li><span class='sid'>"+Gakubu+" 20"+gakunen+"年度入学</span><span class='dates'>"+ diff + "秒前の投稿</span></li>");
          }
          // if (diff>60) {//60秒より多い場合
          //     $('.nameData').prepend("<li><span class='sid'>" + data[i].post_author +"</span><span class='dates'>"+ Math.floor(diff/60) + "分前の投稿</span></li>");
          // }else {
          //     $('.nameData').prepend("<li><span class='sid'>" + data[i].post_author +"</span><span class='dates'>"+ diff + "秒前の投稿</span></li>");
          // }
          // // $('.nameData').append("<li>" + data[i].post_author + "</li>")
        });
        setTimeout(loadTickets, 5000);
      }
    });
    $.ajax({
        url: '<?php echo home_url('/') . "get_venue_description/?venue=$venue"; ?>',
        //dataType: 'json',
        success: function(data){
          if(data.length < 1) return;
          $('#venue-spot > *').remove();
          $('#venue-spot').append("<strong>" + data + "</strong>");
            console.log();
        }
    });
    return;
}

$(document).ready(function(){
  $("#close-btn").click(function() {
    // if(!window.confirm('<?php echo $venue;?> の募集を締め切ります。')){
      // return;
    //} 
    $.ajax({
      type: "POST",
      url: "<?php echo home_url('/'); ?>/delete_venue?id=<?php echo $term->term_id; ?>",
      success: function(data, dataType) {
        console.log(data);
        console.log($('#close-btn'));
        timerStopFlag = true;
        $('#close-btn').val('募集を締め切りました。').attr('disabled', 'disabled');
      }
    });
  }).css('cursor', 'pointer');
});
</script>
<?php get_footer(); ?>
