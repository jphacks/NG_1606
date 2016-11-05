<?php /* Template Name: task_table */
get_header(); ?>
<div class="select-task-page">
  <!--
  <p>
    <a href="javascript:void(0)" class="btn btn-default btn-reload-task">タスクプールを更新</a>
  </p>
  -->
  <table id="task-table" class="table">
    
  </table>
</div>

<script>

  function load_task_table(table){
    table.empty();
    path = "<?php echo home_url('');?>" + '/wp-json/wp/v2/my_task?_embed';
    $.ajax({type: 'GET', url: path, dataType: 'json'}).done(function(json, textStatus, request){
      page_amount = request.getResponseHeader('X-WP-TotalPages');
      page_amount = +page_amount; // キャスト
      console.log(page_amount);
      for(var p = 1; p < page_amount + 1; p++){
        $.getJSON(path + "&page=" + p, function(data){
          if(data.length == 0){
            return;
          }
          for(var i = 0; i < data.length; i++){
            task_state = data[i].content.rendered.replace(/(<([^>]+)>)/ig,"").replace(/\s+/g, "");
            checked_str = "false";
            if(task_state.match(/done/)){
              checked_str = "true"
            }
            // li を append
            table.append('<tr data-my_task_id="' + data[i].id + '" data-task_checked="' + checked_str + '" data-task_id="' + data[i].title.rendered + '"></tr>');

            // ID を用いてタスクプールから検索
            $.getJSON("<?php echo home_url('/');?>wp-json/wp/v2/task/" + data[i].title.rendered + "?_embed", function(item){
              // サムネイルの取得
              $(item._embedded['wp:featuredmedia']).each(function(index, element){
                media_url = element.source_url;
              });
              var is_checked = ""
              if($('tr[data-task_id="' + item.id + '"]').data('task_checked')){
                is_checked = "checked";
              }
              // li の中身を append
              $('tr[data-task_id="' + item.id + '"]').append(
                '<td><img src="' + media_url + '" width="50"></td>' + 
                '<td class="image-title">' + item.title.rendered + '</td>' +
                '<td><input type="checkbox" data-task_id="' + item.id + '" class="checkbox" onclick="check_my_task($(this))"' + is_checked + '></td> ' +  
                ''
              );
            });
          }
        });
      }
    });
  }

  // アクションフック
  $(document).ready(function(){
    load_task_table($('#task-table'));
  });
</script>

<?php get_footer(); ?>
