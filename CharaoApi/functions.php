<?php

// カスタム投稿タイプの追加
add_action('init', 'create_post_type');

function create_post_type(){
  // タスクプール (task)
  register_post_type('task', array(
    'labels' => array(
      'name' => __('タスクプール'),
      'singular_name' => __('タスクプール')
    ),
    'public' => true,
    'menu_position' => 5,
    'show_in_rest' => true
  ));
  // マイタスク (my_task)
  register_post_type('my_task', array(
    'labels' => array(
      'name' => __('マイタスク'),
      'singular_name' => __('マイタスク')
    ),
    'public' => true,
    'menu_position' => 6,
    'show_in_rest' => true
  ));

  // タスクレベル
  register_taxonomy(
    'level', array('task', 'my_task'), array(
      'hierarchical' => false,
      'update_count_callback' => '_update_post_term_count',
      'label' => 'タスクレベル',
      'singular_label' => 'タスクレベル',
      'public' => true,
      'show_ui' => true,
      'show_in_rest' => true
    )
  );
  // 終わった
  register_taxonomy(
    // true | false
    'is_done', 'my_task', array(
      'hierarchical' => false,
      'update_count_callback' => '_update_post_term_count',
      'label' => '終わった',
      'singular_label' => '終わった',
      'public' => true,
      'show_ui' => true,
      'show_in_rest' => true
    )
  );
}

/*投稿ページ等への投稿ページを追加するためのアクションフック*/
add_action('admin_menu', 'add_custom_inputbox');

/*追加した表示項目のデータ更新・保存のためのアクションフック*/
add_action('save_post', 'save_custom_postdata');

/*入力項目がどの投稿タイプのページに表示されるのかの設定*/
function add_custom_inputbox() {
  add_meta_box( 'task_id','追加入力欄', 'custom_area', array('task', 'my_task'), 'normal' );
}

/*実際、管理画面に表示される内容*/
function custom_area(){
  global $post;
	echo 'タスクID<input type="text" name="task_id" value="'.get_post_meta($post->ID,'task_id',true).'"><br>';
  // echo '形状<input type="text" name="keijo" value="'.get_post_meta($post->ID,'keijo',true).'"><br>';
}

/*投稿ボタンを押した際のデータ更新と保存*/
function save_custom_postdata($post_id){
  if(isset($_POST['task_id'])){
    $task_id=$_POST['task_id'];
  }else{
    $task_id='';
  };
 	//-1になると項目が変わったことになるので、項目を更新する
	if( strcmp($task_id,get_post_meta($post_id, 'task_id', true)) != 0 ){
		update_post_meta($post_id, 'task_id',$task_id);
	}elseif($task_id == ""){
		delete_post_meta($post_id, 'task_id',get_post_meta($post_id,'task_id',true));
	}
                                  	
  // if(isset($_POST['keijo'])){
  //   $keijo=$_POST['keijo'];
  // }else{
  //   $keijo='';
  // };
	// if( strcmp($keijo,get_post_meta($post_id, 'keijo', true)) != 0 ){
 	// 	update_post_meta($post_id, 'keijo',$keijo);
 	// }elseif($keijo == ""){
 	// 	delete_post_meta($post_id, 'keijo',get_post_meta($post_id,'keijo',true));
 	// }
}

?>

