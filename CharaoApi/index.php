<?php get_header(); ?>
  <div class="login-page text-center">
    <div class="image-cover">
      <div class="title-part">
        <h1 class="sub-title">ワンチャン進歩管理ツール</h1>
        <h1 class="app-title">OneChance</h1>
      </div>

      <div class="login-form-part">
        <a href="<?php echo home_url('/')?>select"><div>ログイン</div></a>
        <a href="<?php echo home_url('/')?>register"><div>新規登録</div></a>
      </div>

      <div class="bottom-part">
        <div>＠JPHACKS</div>
      </div>
    </div>
  </div>

  <script>
    $(function(){
      console.log("jQuery move");
    });
  </script>

  <script>
  $(document).ready(function () {
      var hsize = $(window).height();
      var pad_top = hsize / 4;
      $(".title-part").css("padding-top", pad_top + "px");
  });
  </script>
