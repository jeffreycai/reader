<div class="container" id="body">
  <div id="tobeloaded">
    
  </div>
  <div class="row">
    <div class="col-xs-12">
      <button id="loadmore" data-page="1" class="btn btn-default form-control">加载更多</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var page = $('#loadmore').data('page');
  var category = <?php echo $category ? $category : 0 ?>;
  var unread = <?php echo $unread ? $unread : "0" ?>;
  jQuery(function(){
    
    /** load articles **/
    loadArticles(page, category, unread);
    $('#loadmore').click(function(){
      page = $('#loadmore').data('page');
      loadArticles(page, category, unread);
    });
    
    
    /** article overlay **/
    var opacity = 0.2;
    $('body.articles #body').on('click', '.article', function(){
      // reset all
      $('.article .actions').hide();
      $('.article .content').css('opacity', 1);
      // overlay action
      $('.actions', this).show();
      $('.content', this).css('opacity', opacity);
    });
    $('body.articles #body').on('click', '.article .actions', function(event){
      event.stopPropagation();
      var parent = $(this).parents('.article').first();
      $(this).hide();
      $('.content', parent).css('opacity', 1);
    });

    /** article inner link stop propagation **/
    $('body.articles #body').on('click', '.wechat_nickname a', function(event){
      event.stopPropagation();
      return true;
    });

    /** article actions **/
    // goto
    $('body.articles #body').on('click', '.article .goto', function(event){
      event.stopPropagation();
      
      // mark as read
      var article = $(this).parents('.article').first();
      var read_link = $('.read', article);
      if (!read_link.hasClass('loading') && !read_link.hasClass('active')) {
        read_link.click();
      }
      
      var url = $(this).data('url');
      window.open(url);
      return false;
    });
    // read
    $('body.articles #body').on('click', '.article .read', function(event){
      event.stopPropagation();
      
      if (!$(this).hasClass('loading')) {
        var article_id = $(this).data('id');
        var read = $(this).data('read') == "1" ? 0 : 1;
        var user_wechat_account_id = $(this).data('user_wechat_account_id');
        var link = $(this);

        link.removeClass('glyphicon glyphicon-ok-sign').addClass('loading').html('<img src="<?php echo uri('modules/site/assets/images/ajax-loader.gif', false) ?>" alt="loading" />');
        $.post('<?php echo uri('article/mark-as/read') ?>', 'id='+article_id+'&read='+read+'&user_wechat_account_id='+user_wechat_account_id, function(data){
          if (data.status != 'success') {
            sweetAlert('Oops', data.message, 'error');
          } else {
            link.data('read', data.read);
            if (data.read == 1) {
              link.addClass('active');
              link.parents('.article').first().addClass('read');
            } else {
              link.removeClass('active');
              link.parents('.article').first().removeClass('read');
            }
          }
          link.removeClass('loading').addClass('glyphicon glyphicon-ok-sign').html('');
        }, 'json');
      }
      return false;
    });
    
    
    
    /** function to load articles **/
    function loadArticles(page, category, unread) {
      var text = $('#loadmore').html();
      $('#loadmore').html('加载中 ...').addClass('disabled');
      $.post('<?php echo uri('articles/load') ?>', 'page=' + page + '&category=' + category + '&unread=' + unread, function(data){
        $('#loadmore').html(text).removeClass('disabled').data('page', data.page);
        for (i in data.articles) {
          var article = data.articles[i];
          $('#tobeloaded').append(
'<article class="row article '+(article.read ? 'read' : '')+'">\n\
   <div class="col-xs-12 content">\n\
     <div class="inner">\n\
       <div class="left">\n\
         <img src="'+article.thumbnail+'" />\n\
       </div>\n\
       <div class="middle">\n\
         <h2>'+article.title+'</h2>\n\
         <div class="wechat_nickname"><a href="<?php echo uri('account') ?>/'+article.wechat_id+'">'+article.wechat_account+'</a></div>\n\
         <div class="published_at">'+article.published_at+'</div>\n\
       </div>\n\
     </div>\n\
   </div>\n\
   <div class="col-xs-12 actions">\n\
     <ul>\n\
       <li><a class="goto" data-url="'+article.url+'" title="阅读"><span class="glyphicon glyphicon-new-window"></span></a><br /><i>前往</i></li>\n\
       <li><a class="later" href="#" title="稍后阅读"><span class="glyphicon glyphicon-bookmark"></span></a><br /><i>标记</i></li>\n\
       <li><a class="favourite" href="#" title="收藏"><span class="glyphicon glyphicon-heart"></span></a><br /><i>收藏</i></li>\n\
       <li><a class="read '+(article.read ? 'active' : '')+'" data-id="'+article.id+'" data-read="'+article.read+'" data-user_wechat_account_id="'+article.user_wechat_account_id+'" href="#" title="标记已读"><span class="glyphicon glyphicon-ok-sign"></span></a><br /><i>已阅</i></li>\n\
     </ul>\n\
   </div>\n\
 </article>\n\
\n\
'
);
        }
      }, 'json');
    }
  });
</script>