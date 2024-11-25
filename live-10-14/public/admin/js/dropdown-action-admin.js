 $(document).ready(function() {
  
  $(document).click(function() {
     $('.dropdown-menu.show').removeClass('show');
  });
  
  $('body').on('click','.apto-trigger-dropdown', function(e){
    
    e.stopPropagation();
    
   $(this).closest('.apto-dropdown-wrapper').find('.dropdown-menu').toggleClass('show');
  });
  
  
  $('body').on('click','.dropdown-item', function(e){
    
    e.stopPropagation();
    
    let $selectedValue = $(this).val(); 
    let $icon          = $(this).find('svg');
    let $btn           = $(this).closest('.apto-dropdown-wrapper').find('.apto-trigger-dropdown');
    
   $(this).closest('.apto-dropdown-wrapper').find('.dropdown-menu').removeClass('show').attr('data-selected', $selectedValue);
    
    $btn.find('svg').remove();
    $btn.prepend($icon[0].outerHTML);
    
  });
  
}); 