var pageData = {current:1, pages: ['empty']}


function pageControl() {
	  location.hash = '#' + pageData.current.toString();
	  $('#page-select option').removeAttr('selected');	  
	  $('#page-select option[value='+pageData.current.toString()+']').attr('selected','selected');
      $('#backward').prop('disabled', false).show();
      $('#forward').prop('disabled', false).show();	  
      if(pageData.current == pageData.pages.length) {
        $('#forward').prop('disabled', true).hide();
      }
      if(pageData.current == 1) {
        $('#backward').prop('disabled', true).hide();
      }	
}

function pageForward() {
   if(pageData.current < pageData.pages.length) {
      oldPage = $(pageData.pages[pageData.current-1]).addClass("w4uHidden");
      pageData.current++;
      newPage = $(pageData.pages[pageData.current-1]).removeClass("w4uHidden");
	  pageControl();
   }
}

function pageBackward() {
   if(pageData.current > 1) {
      oldPage = $(pageData.pages[pageData.current-1]).addClass("w4uHidden");
      pageData.current--;
      newPage = $(pageData.pages[pageData.current-1]).removeClass("w4uHidden");
	  pageControl();
   }
}

function pageSelect(pageId) {
	var pageInt = parseInt(pageId);
	if((pageInt > 0) && (pageInt<(pageData.pages.length+1))) {
	  oldPage = $(pageData.pages[pageData.current-1]).addClass("w4uHidden");
	  pageData.current = pageInt;
	  newPage = $(pageData.pages[pageData.current-1]).removeClass("w4uHidden");
      pageControl();
	}
}

function hidePages() {
    for (var i = 0; i < pageData.pages.length; i++) {
	  if((pageData.current-1) != i) {
        $(pageData.pages[i]).addClass("w4uHidden");
	  }
    }
}

function initPages() {
  if(location.hash) {
    var pageId = parseInt(location.hash.replace('#',''));
	   if(pageId>0 && pageId<(pageData.pages.length+1)) {
          pageData.current = pageId;		  
	   }
  }		
  pageControl();
  document.addEventListener('readystatechange', event => {
    if (event.target.readyState === "complete") {
      //alert("Now external resources are loaded too, like css,src etc... ");
      setTimeout(hidePages, 500);
    }
  });
}

$('#forward').click(function() {
  pageForward();
});

$('#backward').click(function() {
  pageBackward();
});

$('#page-select').change(function() {
  var pageNo =	$(this).children("option:selected").val();
  pageSelect(pageNo);	
});

