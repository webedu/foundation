var pageData = {current:1, pages: ['empty']}


function pageForward() {
   if(pageData.current < pageData.pages.length) {
      oldPage = $(pageData.pages[pageData.current-1]).addClass("w4uHidden");
      pageData.current++;
      newPage = $(pageData.pages[pageData.current-1]).removeClass("w4uHidden");
      //oldPage.hide();
      oldPage.addClass("w4uHidden");
      //newPage.show();
      newPage.removeClass("w4uHidden");

      $('#backward').prop('disabled', false).show();
      if(pageData.current == pageData.pages.length) {
        $('#forward').prop('disabled', true).hide();
      }
   }
}

function pageBackward() {
   if(pageData.current > 1) {
      oldPage = $(pageData.pages[pageData.current-1]).addClass("w4uHidden");
      pageData.current--;
      newPage = $(pageData.pages[pageData.current-1]).removeClass("w4uHidden");
      //oldPage.hide();
      oldPage.addClass("w4uHidden");
      //newPage.show();
      newPage.removeClass("w4uHidden");

      $('#forward').prop('disabled', false).show();
      if(pageData.current == 1) {
        $('#backward').prop('disabled', true).hide();
      }
   }
}

function hidePages() {
    for (var i = 1; i < pageData.pages.length; i++) {
      //$(pageData.pages[i]).hide();
      $(pageData.pages[i]).addClass("w4uHidden");
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

