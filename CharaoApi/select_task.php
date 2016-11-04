<?php /* Template Name: select_task */
get_header(); ?>

<ul id="task-list">
</ul>

<script>
  $(function(){
    console.log("jQuery move");
  });
  $(document).ready(function(){
    $.getJSON("<?php echo home_url('/');?>wp-json/wp/v2/task?filter[orderby]=rand&_embed&filter[nopaging]=true", function(data){
      console.log(data);
      for(var i in data){
        console.log(data[i].title.rendered);
        $(data[i]._embedded['wp:featuredmedia']).each(function(index, element){
          media_url = element.source_url;
        });
        console.log(media_url);
        $('#task-list').append('<li><img src="' + media_url + '" width="100"> ' + data[i].title.rendered + '</li>');
      }
    });
  });
</script>

<?php get_footer(); ?>
