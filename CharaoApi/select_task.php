<?php /* Template Name: select_task */
get_header(); ?>
<div class="row">
  <div class="col-sm-1">
    <h1>O.C.</h1>
    <p>
      <a href="javascript:void(0)" class="btn btn-default btn-reload-task">タスクプールを更新</a>
    </p>
  </div>
  <div class="col-sm-7 col-sm-offset-1">  
    <ul id="task-pool">
    </ul>
  </div>
  <div class="col-sm-3">
    <ul id="task-list">
    </ul>
  </div>
</div>
<script>
  // マイタスクをロード
  function load_task_list(list){
    list.empty();
    path = "<?php echo home_url('');?>" + '/wp-json/wp/v2/my_task?_embed';
    $.ajax({type: 'GET', url: path, dataType: 'json'}).done(function(json, textStatus, request){
      page_amount = request.getResponseHeader('X-WP-TotalPages');
      page_amount = +page_amount; // キャスト
      for(var p = 1; p < page_amount + 1; p++){
        $.getJSON(path + "&page=" + p, function(data){
          if(data.length == 0){
            console.log("break");
            return;
          }
          for(var i in data){
            $.getJSON("<?php echo home_url('/');?>wp-json/wp/v2/task/" + data[i].title.rendered + "?_embed", function(item){
              console.log(item);
              $(item._embedded['wp:featuredmedia']).each(function(index, element){
                media_url = element.source_url;
              });
              list.append(
                '<li><img src="' + media_url + '" width="50"><br>' + 
                item.title.rendered + ' ' + 
                '</li>'
              );
            });
          }
        });
      }
    });
  }

  // タスクプールをロード
  function load_task_pool(pool){
    $.getJSON("<?php echo home_url('/');?>wp-json/wp/v2/task?filter[orderby]=rand&_embed&filter[nopaging]=true", function(data){
      pool.empty();
      for(var i in data){
        $(data[i]._embedded['wp:featuredmedia']).each(function(index, element){
          media_url = element.source_url;
        });
        pool.append(
          '<li><img src="' + media_url + '" width="100"> ' + 
          data[i].title.rendered + ' ' + 
          '<a href="javascript:void(0)" class="btn btn-success btn-doing-task" onclick="add_my_task($(this));"' + 
          'data-post_title="' + data[i].title.rendered + '" ' + 
          'data-task_id="' + data[i].id + '" ' + 
          'data-post_content="' + data[i].content.rendered + '">やる</a> ' + 
          '<a href="javascript:void(0)" class="btn btn-danger btn-wont-task" onclick="remove_my_task($(this));">やらない</a> ' + 
          '</li>'
        );
        
      }
    });
  }

  // やる関数
  var wpApiSettings = {"root":"<?=esc_url_raw( rest_url())?>","nonce":"<?=wp_create_nonce( 'wp_rest' )?>"};
  function add_my_task(item){
    console.log(item.data('post_title'));
    console.log(item.data('post_content'));
    item.attr('disabled', 'disabled');
    $.ajax( {
      url: wpApiSettings.root + 'wp/v2/my_task/',
      method: 'POST',
      beforeSend: function ( xhr ) {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      },
      data:{
        'title': item.data('task_id'),
        'content': item.data('post_content'), 
        'status': 'publish',
        'fields[task_id]': item.data('task_id')
      }
    }).done( function ( response ) {
      item.parent().append(' <span class="text-success">タスクに登録しました</span>') 
      item.parent('li').remove();
      load_task_list($('#task-list'));
      console.log( response );
    });
  }
  // やらない関数
  function remove_my_task(item){
    item.attr('disabled', 'disabled');
    item.parent('li').remove();
  }


  // アクションフック
  $(document).ready(function(){
    load_task_pool($('#task-pool'));
    load_task_list($('#task-list'));
  });
  $('.btn-reload-task').click(function(){
    load_task_pool($('#task-pool'));
  });
  $('.btn-doing-task').click(function(){
    console.log("item: ");

    add_my_task(this);
  });
  
</script>

<?php get_footer(); ?>
