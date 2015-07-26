jQuery(function(){
  /** top dropdown **/
  $('.dropdown a').click(function(){
    target = $(this).parent().data('target');
    toggleDropdown($(this).parent(), target);
  });
  
  function toggleDropdown(dropdownm, target) {
    $('.dropdown').each(function(){
      if ($(this).data('target') == target) {
        if ($('small', this).hasClass('glyphicon-expand')) {
          $('small', this).removeClass('glyphicon-expand').addClass('glyphicon-collapse-down');
          $('#'+target).show();
        } else {
          $('small', this).removeClass('glyphicon-collapse-down').addClass('glyphicon-expand');
          $('#'+target).hide();
        }
      } else {
          $('small', this).removeClass('glyphicon-collapse-down').addClass('glyphicon-expand');
          $('#'+$(this).data('target')).hide();
      }
    });
  }
  
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
  
  /** article actions **/
  // read
  $('body.articles #body').on('click', '.article .read', function(event){
    event.stopPropagation();
    var url = $(this).data('url');alert(url);
    window.open(url);
    return false;
  });
  
  
  /** page load overlay **/
  $('#header .search form').submit(function(){
    pageLoad();
    return true;
  });
  $('#pagebar_container a').click(function(){
    pageLoad();
    return true;
  });
  function pageLoad() {
    $('body').append('<div id="page_overlay" style="position:fixed; width:100%; height:100%; background:rgba(0,0,0,0.5); top:0px; left:0px; z-index:99999;"><div style="margin-top:260px;text-align:center;"><img src="/modules/site/assets/images/page-loader.gif" alt="loading..." /><div style="color:#FFF;font-size:1em; margin-top:5px;">&nbsp;玩命加载中 ...</div></div></div>');
  }
  
  
  /** lazy loading **/
  $('img.lazyloading').each(function(){
    var source = $(this).data('source');
    var image = $(this);
    var img = $("<img />").attr('src', source)
    .load(function() {
        if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
            // do nothing;
        } else {
            image.replaceWith(img);
        }
    });
  });
  
});

