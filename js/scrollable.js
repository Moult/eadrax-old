// execute your scripts when DOM is ready. this is a good habit 
$(function() {         
         
    // initialize scrollable  
    $("div.scrollable").scrollable({ 
        size: 3, 
        items: '#thumbs',   
        hoverClass: 'hover' 
    });     
     
}); 
