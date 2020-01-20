var pageData = {current:1, pages: ['empty']}


function pageForward() {
   if(pageData.current < pageData.pages.length) {
      $(pageData.pages[pageData.current-1]).hide();
      pageData.current++;
      $(pageData.pages[pageData.current-1]).show();
      $('#backward').prop('disabled', false).show();
      if(pageData.current == pageData.pages.length) {
        $('#forward').prop('disabled', true).hide();
      }
   }
}

function pageBackward() {
   if(pageData.current > 1) {
      oldPage = $(pageData.pages[pageData.current-1]).hide();
      pageData.current--;
      newPage = $(pageData.pages[pageData.current-1]).show();
      oldPage.hide();
      newPage.show();
      $('#forward').prop('disabled', false).show();
      if(pageData.current == 1) {
        $('#backward').prop('disabled', true).hide();
      }
   }
}

function hidePages() {
    for (var i = 1; i < pageData.pages.length; i++) {
      $(pageData.pages[i]).hide();
    }
}

function initPages() {
  document.addEventListener('readystatechange', event => {
  if (event.target.readyState === "complete") {
    //alert("Now external resources are loaded too, like css,src etc... ");
    setTimeout(hidePages, 500);
  }
});


}

$('#forward').click(function() {
  pageForward();
  //$(window).trigger('resize');
});

$('#backward').click(function() {
  pageBackward();
  //$(window).trigger('resize');
});

