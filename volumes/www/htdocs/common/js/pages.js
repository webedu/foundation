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

$('#forward').click(function() {
  pageForward();
});

$('#backward').click(function() {
  pageBackward();
});

