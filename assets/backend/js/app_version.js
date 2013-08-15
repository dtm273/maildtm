var current_url = base_url + 'admin/version_manager/';

$(document).ready(function() {	
	gebo_select_row.init();
	gebo_delete_rows.simple();
	
	//* colorbox single
	gebo_colorbox_single.init();
	
	//search action
	search.init();
        search_by_parent.init();
});


//* select all rows
gebo_select_row = {
	init: function() {
		$('.select_rows').click(function () {	
			var tableid = $(this).data('tableid');
            $('#'+tableid).find('input[name=row_sel]').attr('checked', this.checked);
		});
	}
};

//* delete rows
gebo_delete_rows = {
	simple: function() {
		$(".delete_rows_simple").on('click',function (e) {
			e.preventDefault();
			var tableid = $(this).data('tableid');
            if($('input[name=row_sel]:checked', '#'+tableid).length) {
                $.colorbox({
                    initialHeight: '0',
                    initialWidth: '0',
                    href: "#confirm_dialog",
                    inline: true,
                    opacity: '0.3',
                    onComplete: function(){
                        $('.confirm_yes').click(function(e){
                            e.preventDefault();
                                                                                
                            //remove in layout
                            $('input[name=row_sel]:checked', '#'+tableid).closest('tr').fadeTo(300, 0, function () {                   	
                            	//remove row
                            	$(this).remove();    
                                $('.select_rows','#'+tableid).attr('checked',false);
                            });
                                  
                            $.colorbox.close();
                                                             
                        });
                        $('.confirm_no').click(function(e){
                            e.preventDefault();
                            $.colorbox.close(); 
                        });
                    }
                });
            }
		});
		
		$(".confirm_yes").click(function () {	
			var tableid = $('.delete_rows_simple').data('tableid');
			
			 //get all ids checked
            var ids = $('input[name=row_sel]:checked','#'+tableid).map(function () {
            	  return $(this).attr('id').split('_')[1];
            }).get();
            
            //ajax remove
            submit.init(current_url+'remove?ids='+ids);
		})
	},  
};

//* single image colorbox
gebo_colorbox_single = {
	init: function() {
		if($('.cbox_single').length) {
			$('.cbox_single').colorbox({
				maxWidth	: '80%',
				maxHeight	: '80%',
				opacity		: '0.2', 
				fixed		: true
			});
		}
	}
};

//search action
search = {
	init : function(){
		jQuery('#search').on('keypress',function(e){
			if (e.keyCode == 13){				
				var keyword = jQuery(this).val();
				submit.init(current_url+'search?keyword='+keyword);
			}
		});
		jQuery('#search_button').on('click',function (e) {
			var keyword = jQuery('#search').val();
			submit.init(current_url+'search?keyword='+keyword);
		});
		jQuery('#search_button').on('hover',function (e) {
			jQuery(this).css('cursor','pointer');
		});
	}
}
search_by_parent = {
	init : function(){
		jQuery('#search_by_parent').on('keypress',function(e){
			if (e.keyCode == 13){				
				var parent_keyword = jQuery(this).val();
				submit.init(current_url+'search_parent?parent_keyword='+parent_keyword);
			}
		});
		jQuery('#search_parent_button').on('click',function (e) {
			var parent_keyword = jQuery('#search_by_parent').val();
                        alert(parent_keyword);
			submit.init(current_url+'search_parent?parent_keyword='+parent_keyword);
		});
		jQuery('#search_parent_button').on('hover',function (e) {
			jQuery(this).css('cursor','pointer');
		});
	}
}

//submit
submit = {
	init : function(action){
		jQuery('#adminForm').attr('action',action);
		jQuery('#adminForm').submit();
	}
}


