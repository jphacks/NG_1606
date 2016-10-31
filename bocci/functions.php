<?php

// カスタム投稿タイプの追加
add_action('init', 'create_post_type');

function create_post_type(){
  register_post_type('tickets', array(
    'labels' => array(
      'name' => __('チケット'),
      'singular_name' => __('チケット')
    ),
    'public' => true,
    'menu_position' => 5,
  ));
  register_taxonomy(
    'venue', 'tickets', array(
      'hierarchical' => false,
      'update_count_callback' => '_update_post_term_count',
      'label' => '場所',
      'singular_label' => '場所',
      'public' => true,
      'show_ui' => true
    )
  );
}

?>
