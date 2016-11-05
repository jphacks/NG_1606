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
    'show_in_rest' => 'true'
  ));
  // マイタスク (my_task)
  register_post_type('my_task', array(
    'labels' => array(
      'name' => __('マイタスク'),
      'singular_name' => __('マイタスク')
    ),
    'public' => true,
    'menu_position' => 6,
    'show_in_rest' => 'true'
  ));
  register_taxonomy(
    'level', array('task', 'my_task'), array(
      'hierarchical' => false,
      'update_count_callback' => '_update_post_term_count',
      'label' => 'レベル',
      'singular_label' => 'レベル',
      'public' => true,
      'show_ui' => true
    )
  );
  register_taxonomy(
    // true | false
    'is_done', 'my_task', array(
      'hierarchical' => false,
      'update_count_callback' => '_update_post_term_count',
      'label' => '終わった',
      'singular_label' => '終わった',
      'public' => true,
      'show_ui' => true
    )
  );
  
}

?>
