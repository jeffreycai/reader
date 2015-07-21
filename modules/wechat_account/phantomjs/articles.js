var page = require('webpage').create()
    system = require('system');
page.settings.resourceTimeout = 5000; // 5 secs
page.onResourceTimeout = function(e) {
  console.log('[Error]: Phantomjs timeout');
  phantom.exit(1);
}
    
var args = system.args;
var url = '';
if (args.length !== 2) {
  console.log('[Error]: url required');
  phantom.exit();
} else {
  url = args[1];
}
    
page.onConsoleMessage = function(msg) {
    system.stdout.write(msg);
};

page.open(url, function(status) {
  if (status === 'success') {
    waitFor(function(){
      return page.evaluate(function(){
        return $('#wxmore').length > 0;
      });
    }, function(){
      console.log(page.content);
      phantom.exit();
    }, 4500); // wait for 4.5 seconds
  } else {
    console.log("[Error]: can not load page")
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
//        console.log("'waitFor()' finished in " + (new Date().getTime() - start) + "ms.");
        typeof(onReady) === "string" ? eval(onReady) : onReady(); //< Do what it's supposed to do once the condition is fulfilled
        clearInterval(interval); //< Stop this interval
      }
    }
  }, 250); //< repeat check every 250ms
}
;
