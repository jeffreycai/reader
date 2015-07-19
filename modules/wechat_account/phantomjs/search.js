var page = require('webpage').create()
    system = require('system');
    
page.onConsoleMessage = function(msg) {
    system.stdout.write(msg);
};

page.open('http://weixin.sogou.com/weixin?query='+encodeURIComponent('读书')+'&fr=sgsearch&type=1', function(status) {
  if (status === 'success') {
    page.evaluate(function(){
      var articles = new Array();
      $('._item').each(function(){
        var article = {
          'thumb': $('.img-box img').attr('src'),
          'title': $('.txt-box h3').html(),
          'wechat_account': $('.txt-box h4 span').text().split(':')[1],
        };
        console.log($('.txt-box h4 span',this).html());
        articles.push(article);
      });
//      console.log(JSON.stringify(articles));
    });
    phantom.exit();
  } else {
    console.log('Can not open sougou homepage');
    phantom.exit();
  }
});

function waitFor(testFx, onReady, timeOutMillis) {
  var maxtimeOutMillis = timeOutMillis ? timeOutMillis : 3000, //< Default Max Timout is 3s
          start = new Date().getTime(),
          condition = false,
          interval = setInterval(function() {
    if ((new Date().getTime() - start < maxtimeOutMillis) && !condition) {
      // If not time-out yet and condition not yet fulfilled
      condition = (typeof(testFx) === "string" ? eval(testFx) : testFx()); //< defensive code
    } else {
      if (!condition) {
        // If condition still not fulfilled (timeout but condition is 'false')
        console.log("'waitFor()' timeout");
        phantom.exit(1);
      } else {
        // Condition fulfilled (timeout and/or condition is 'true')
        console.log("'waitFor()' finished in " + (new Date().getTime() - start) + "ms.");
        typeof(onReady) === "string" ? eval(onReady) : onReady(); //< Do what it's supposed to do once the condition is fulfilled
        clearInterval(interval); //< Stop this interval
      }
    }
  }, 250); //< repeat check every 250ms
}
;
