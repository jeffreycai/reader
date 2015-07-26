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
  var read = <?php echo $read ? $read : "-1" ?>;
  jQuery(function(){
    loadArticles(page, category, read);
    $('#loadmore').click(function(){
      page = $('#loadmore').data('page');
      loadArticles(page, category, read);
    });
    
    /** function to load articles **/
    function loadArticles(page, category, read) {
      var text = $('#loadmore').html();
      $('#loadmore').html('加载中 ...').addClass('disabled');
      $.post('<?php echo uri('articles/load') ?>', 'page=' + page + '&category=' + category + '&read=' + read, function(data){
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
         <div class="wechat_nickname">'+article.wechat_account+'</div>\n\
         <div class="published_at">'+article.published_at+'</div>\n\
       </div>\n\
     </div>\n\
   </div>\n\
   <div class="col-xs-12 actions">\n\
     <ul>\n\
       <li><a class="read" data-url="'+article.url+'" title="阅读"><span class="glyphicon glyphicon-new-window"></span></a></li>\n\
       <li><a href="" title="稍后阅读"><span class="glyphicon glyphicon-bookmark"></span></a></li>\n\
       <li><a href="" title="收藏"><span class="glyphicon glyphicon-heart"></span></a></li>\n\
       <li><a href="" title="标记已读"><span class="glyphicon glyphicon-ok-sign"></span></a></li>\n\
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