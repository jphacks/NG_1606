<!-- $html = file_get_contents('http://webkaru.net/php/'); -->


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>タイトル入力</title>

</head>
<body>
    <h2>マッチングの結果です。<br /></h2>
    投稿から10分経過したものは表示されません。<br />

    <?php
    //データの受け取り
        $sid = $_POST['sid'];
        $time = $_POST['time'];
        $venue = $_POST['venue'];

        $dates=new DateTime($time);
        $y=$dates->format('Y');//「年」表示
        $m=$dates->format('n');//0をつけない「月」表示
        $d=$dates->format('j');//「日」表示
        $H=$dates->format('H');//24時間単位での「時」表示
        $M=$dates->format('i');//「分」表示
        $S=$dates->format('s');//「秒」表示

    // print '【学籍番号='.$sid.'、詳細な場所='.$venue.'、<br />開始時刻='.$y.'年 '.$m.'月 '.$d.'日 '.$H.'時 '.$M.'分 '.$S.'秒】<br />';

    // $seturl = 'http://bocci.sakura.ne.jp/bocci/result_json/?venue="'.$venue.'"';
    // echo $seturl;
    // echo '<br /><br />';
    //
    // $json = file_get_contents($seturl);
    // print_r($json);
    // echo "<br /><br /><br />";
    //
    // $array = json_decode($json,true);
    // echo "頭の１件だけ出力<br />";
    // print_r($array);
    ?>


<ul class="nameData">
<li>検索中...</li>
</ul>

    <script src="http://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">
//         $.ajax({
// url: '<?php echo "http://bocci.sakura.ne.jp/bocci/result_json/?venue=$venue"; ?>',
// dataType: 'json',
// success: function(data){
//     console.log(data);
//     $.each(data, function(i){
//         console.log(data[i].post_date_gmt);
//         $('.nameData').append("<li>" + data[i].post_date_gmt + "</li>")
//     });
//     refresh();
// }
//
// var output = function(data){
//     console.log(data);
//     $.each(data, function(i){
//         console.log(data[i].post_date_gmt);
//         $('.nameData').append("<li>" + data[i].post_date_gmt + "</li>")
//     });
//     refresh();
// }
// var refresh = function(){
//     setTimeout(output(data),5000);
// };
//
//
// });;
loadTickets();
function loadTickets(){
    console.log('<?php echo 'http://bocci.sakura.ne.jp/bocci/result_json/?venue='.$venue.''; ?>');
$.ajax({
    url: '<?php echo 'http://bocci.sakura.ne.jp/bocci/result_json/?venue='.$venue; ?>',
    dataType: 'json',
    success: function(data){
        console.log(data);
        $('.nameData > *').remove();
        $.each(data, function(i){
            var realtime = new Date();
            var dt1 = new Date(realtime.getFullYear(),realtime.getMonth(),realtime.getDate(),realtime.getHours(),realtime.getMinutes(),realtime.getSeconds());
            console.log("現在時刻dt1=",dt1);

//Safariに対応
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

// 学籍番号を学部学年出席番号に分ける作業------------------------
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
// --------------------------------------------------------

            if (diff>600) {     //10分より多い場合
                // $('.nameData').append("<li>(学籍番号 = " + data[i].post_author +" "+Gakubu+" 20"+gakunen+"年度入学、"+ Math.floor(diff/60) + "分前の投稿)</li>");
            }else if(diff>60){  //60秒より多い場合
                $('.nameData').append("<li>学籍番号 = " + data[i].post_author +" "+Gakubu+" 20"+gakunen+"年度入学、"+ Math.floor(diff/60) + "分前の投稿</li>");
            }else{              //60秒に満たない場合
                $('.nameData').append("<li>学籍番号 = " + data[i].post_author +" "+Gakubu+" 20"+gakunen+"年度入学、"+ diff + "秒前の投稿</li>");
            }
            // $('.nameData').append("<li>" + data[i].post_date+" Author_name = " + data[i].post_author + "</li>");

            // if(dt1.getTime() < dt2.getTime()) {
            //     //処理A
            //     console.log("dt1");
            //     $('.nameData').append("<li>" + data[i].post_date_gmt+ " Author_name = " + data[i].post_author + "</li>");
            //     // $('.nameData').append("<li>Author_name = " + data[i].post_author + "</li>");
            //     //
            // } else {
            //     //処理B
            //     console.log("dt2");
            // }
            // // $('.nameData').append("<li>" + data[i].post_date_gmt + "</li>")
        });
        setTimeout(loadTickets, 1000);
        return;
    },
    error: function(){
        console.log("error!!");
    }
});
}
    </script>
</body>
</html>
