<script>
var checked_str = "";

function load_task_table(table){
  table.empty();
  path = "<?php echo home_url('');?>" + '/wp-json/wp/v2/my_task?filter[author]=<?=wp_get_current_user()->get('ID')?>&_embed';
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
          // tr を append
          table.append('<tr data-my_task_id="' + data[i].id + '" data-task_checked="' + checked_str + '" data-task_id="' + data[i].title.rendered + '"></tr>');
          $('tr[data-task_checked="true"]').css("display", "none");

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
            // td の中身を append
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
// マイタスクをロード
function load_task_list(list){
  list.empty();
  path = "<?php echo home_url('');?>" + '/wp-json/wp/v2/my_task?filter[author]=<?=wp_get_current_user()->get('ID')?>&_embed';
  $.ajax({type: 'GET', url: path, dataType: 'json'}).done(function(json, textStatus, request){
    page_amount = request.getResponseHeader('X-WP-TotalPages');
    page_amount = +page_amount; // キャスト
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
          list.append('<li data-my_task_id="' + data[i].id + '" data-task_checked="' + checked_str + '" data-task_id="' + data[i].title.rendered + '"></li>');

          // ID を用いてタスクプールから検索
          $.getJSON("<?php echo home_url('/');?>wp-json/wp/v2/task/" + data[i].title.rendered + "?_embed", function(item){
            // サムネイルの取得
            $(item._embedded['wp:featuredmedia']).each(function(index, element){
              media_url = element.source_url;
            });
            var is_checked = ""
            if($('li[data-task_id="' + item.id + '"]').data('task_checked')){
              is_checked = "checked";
            }
            $('li[data-task_id="' + item.id + '"]').append(
              '<img src="' + media_url + '" width="50"><br>' +
              '<input type="checkbox" data-task_id="' + item.id + '" class="checkbox" onclick="check_my_task($(this))"' + is_checked + '> ' +  
              '<div class="image-title">' + item.title.rendered + '</div>' +
              ''
            );
          });
        }
      }).done(function(){
        delete_overlap_task();
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
        '<li><a href="javascript:void(0)" class="btn btn-doing-task" onclick="add_my_task($(this));"' +
        'data-post_title="' + data[i].title.rendered + '" ' +
        'data-task_id="' + data[i].id + '" ' +
        'data-post_content="' + data[i].content.rendered + '"><span class="good"></span></a> ' +
        '<a href="javascript:void(0)" class="btn btn-wont-task" onclick="remove_my_task($(this));"><span class="bad"></span></a>'
        + '<div class="task-part text-center"><img src=' + media_url + ' width="100">' + '<div class="image-title">' +
        '<p>' + data[i].title.rendered + '</p>' + '</div>' + '</div>' + '</li>'
      );

    }
  });
}

function delete_overlap_task(){
  // console.log("delete_overlap");
  $('#task-list li').each(function(i, li){
    // console.log($(li).data('task_id'));
    $('a[data-task_id="' + $(li).data('task_id') + '"]')
      .parent('li').remove();
    console.log($('#task-pool li').length);
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
      'content': 'will', 
      'status': 'publish'
    }
  }).done( function ( response ) {
    item.parent().append(' <span class="text-success">タスクに登録しました</span>')
    item.parents('li').remove();
    load_task_list($('#task-list'));
    console.log( response );
  });
}
// やらない関数
function remove_my_task(item){
  item.attr('disabled', 'disabled');
  item.parent('li').remove();
}

// やった関数
function check_my_task(item){
  var my_task_id = item.parents('li, tr').data('my_task_id');
  console.log(my_task_id);
  if(item.is(':checked')){
    // checked
    $.ajax( {
      url: wpApiSettings.root + 'wp/v2/my_task/' + my_task_id,
      method: 'POST',
      beforeSend: function ( xhr ) {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      },
      data:{
        'content': 'done', 
      }
    }).done( function ( response ) {
      console.log( response );
    });
  }else{
    // unchecked
    $.ajax( {
      url: wpApiSettings.root + 'wp/v2/my_task/' + my_task_id,
      method: 'POST',
      beforeSend: function ( xhr ) {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      },
      data:{
        'content': 'doing', 
      }
    }).done( function ( response ) {
      console.log( response );
    });
  }
}
</script>
