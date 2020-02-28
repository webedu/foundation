		 function after2020() {
			var currentTime = new Date();
			var year =  currentTime.getFullYear();
            return year > 2020;
		 }			 
         function flashExists() {
          for (var i in navigator.plugins) {
           if (navigator.plugins[i].name && navigator.plugins[i].name.toString().indexOf('Flash') > -1) {
            return true;
           }
         }
         return false;
        }
		function initFlash(state) {
		  if(""==state) {	
		    if(after2020()) {
			   state = "ruffle";	
		       $.post("session.php", { flash:"ruffle"});
            } else if(flashExists()) {
			   state = "available";	
		       $.post("session.php", { flash:"available"});			
            } else {
			   state = "missing";	
		       $.post("session.php", { flash:"missing"});
            }
		  }
		  return state;
		}
		

		function showFlash(state) {
			if('ruffle' == state) {
			   $(".flash-used").hide();
			   $(".ruffle-used").show();
			   if(!after2020() && flashExists()) {	
			     $(".flash-use").show();
			   } else  {
				 $(".flash-use").hide();  
			   }
			} else if ('available' == state) {
			   $(".flash-used").show();
			   $(".ruffle-used").hide();
			   $(".flash-plugin").hide();
			} else {
			  $(".flash-use").hide();				
			  if(after2020()) {	
				 $(".ruffle-used").show();
                 $(".flash-plugin").hide();
			  } else {				
				 $(".ruffle-used").hide();  
                 $(".flash-plugin").show(2000).fadeOut(500).fadeIn(1000).fadeOut(500).fadeIn(1000).fadeOut(500).fadeIn(1000);				
			  }
			}
		}
        
		function switchToAdobe() {
			var state = 'missing';
			if(flashExists()) {
				state = 'available';
			}
			$.post("session.php", { flash: state});
			showFlash(state);
		}
		
		function switchToRuffle() {
			var state="ruffle";
			$.post("session.php", { flash: state});
			showFlash(state);
		}
		
	