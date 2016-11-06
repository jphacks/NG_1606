<?php /* Template Name: select_task */
get_header(); ?>

<div class="select-task-page">
  <!--
  <p>
    <a href="javascript:void(0)" class="btn btn-default btn-reload-task">タスクプールを更新</a>
  </p>
  -->

  <ul id="task-pool">
  </ul>
  <div style="clear: both; width: 1px; height: 1px;"></div>
  <div class="task-list-box">
    <ul id="task-list"></ul>
  </div>
</div>

<?php include 'script.php'; ?>

<script>
  // アクションフック
  $(document).ready(function(){
    load_task_pool($('#task-pool'));
    load_task_list($('#task-list'));
  });
</script>

<?php get_footer(); ?>
