<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>タイトル入力</title>
	</head>
	<body>
		<h2>第１食堂内でマッチングを行います。<br />検索中です。詳細な場所を指定する場合は場所を入力してください。</h2>

		<?php
		//データの受け取り
			$sid = $_POST['sid'];

		echo '学籍番号=';
		echo $sid;
		echo '<br />';
		echo '<form action="result.php" method="post"><br />';
        
        echo '<input name="sid" type="hidden">';
		echo '<input name="venue" type="text" style="width:80%"><br /><br />';

		echo '<input type="submit" value="次へ" style="width:50%"><br />';

		echo '</form>';

		?>
	</body>
</html>
