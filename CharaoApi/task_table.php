<?php /* Template Name: task_table */
get_header(); ?>
<div class="select-task-page">
<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="" role="group">
    <a href="javascript:void(0)" class="select-button" onclick="hide_done_task()">未完了のタスク</a>
  </div>
  <div class="" role="group">
    <a href="javascript:void(0)" class="select-button" onclick="show_done_task()">すべてのタスク</a>
  </div>
</div>
  <!--
  <p>
    <a href="javascript:void(0)" class="btn btn-default btn-reload-task">タスクプールを更新</a>
  </p>
  -->
  <table id="task-table" class="table">

  </table>
</div>


<?php include 'script.php'; ?>
<script>
function hide_done_task(){
  $('*[data-task_checked="true"]').hide();
  load_task_table($('#task-table'));
}
function show_done_task(){
  $('*[data-task_checked="true"]').show();
}

// アクションフック
$(document).ready(function(){
  load_task_table($('#task-table'));
});
</script>

<?php get_footer(); ?>
