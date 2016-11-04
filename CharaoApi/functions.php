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
  ));
  register_taxonomy(
    'lebel', 'task', array(
      'hierarchical' => false,
      'update_count_callback' => '_update_post_term_count',
      'label' => 'レベル',
      'singular_label' => 'レベル',
      'public' => true,
      'show_ui' => true
    )
  );
}

?>
