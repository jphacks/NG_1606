<?php /* Template Name: select_task */
get_header(); ?>
<p>
  <a href="javascript:void(0)" class="btn btn-default btn-reload-task">タスクを更新</a>
</p>
<ul id="task-list">
</ul>

<script>
  $(function(){
    console.log("jQuery move");
  });
  
  // タスクをロード
  function load_tasks(){
    $.getJSON("<?php echo home_url('/');?>wp-json/wp/v2/task?filter[orderby]=rand&_embed&filter[nopaging]=true", function(data){
      console.log(data);
      $('#task-list').empty();
      for(var i in data){
        console.log(data[i].title.rendered);
        $(data[i]._embedded['wp:featuredmedia']).each(function(index, element){
          media_url = element.source_url;
        });
        console.log(media_url);
        $('#task-list').append(
          '<li><img src="' + media_url + '" width="100"> ' + 
          data[i].title.rendered + ' ' + 
          '<a href="javascript:void(0)" class="btn btn-success btn-doing-task">やる</a> ' + 
          '<a href="javascript:void(0)" class="btn btn-danger btn-wont-task">やらない</a> ' + 
          '</li>'
        );

      }
    });
  }

  $(document).ready(function(){
    load_tasks();
  });
  $('.btn-reload-task').click(function(){
    load_tasks();
  });
  
</script>

<?php get_footer(); ?>
