$(function() {
  /** backend menu effect **/
  $('#side-menu').metisMenu();

  /** CKEditor initialize **/
  if (typeof CKEDITOR != 'undefined' && $('.ckeditor').length) {
    for (i = 0; i < $('.ckeditor').length; i++) {
      CKEDITOR.replace($('.ckeditor').get(i));
    }
  }
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    $(window).bind("load resize", function() {
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
})

// funciton to ask for confirm
function ask(question) {
  var answer = confirm(question);
  if (answer) {
    return true;
  }
  return false;
}

function goback() {
  window.history.back();
  return false;
}