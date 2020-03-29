(function($) {
    $.fn.iconPicker = function( options ) {
        var mouseOver=false;
        var $popup=null;
        // var icons=new Array();
        var icons;
		$.ajax({  
			type: "GET",  
			url: '/admin_menu/iconlist',  
			dataType : 'json',  
			async: false,  
			success: function (data) {  
				if (data) {  
					icons=data;
				}  
			}  
		});

        var settings = $.extend({
        }, options);
        return this.each( function() {
        	element=this;
            if(!settings.buttonOnly && $(this).data("iconPicker")==undefined ){
            	$this=$(this).addClass("form-control");
            	$wraper=$("<div/>",{class:"input-group"});
            	$this.wrap($wraper);
            	$button=$("<span class=\"input-group-addon pointer\"><i class=\"glyphicon glyphicon-picture\"></i></span>");
            	$this.after($button);
            	(function(ele){
	            	$button.click(function(){
			       		createUI(ele);
			       		showList(ele,icons);
	            	});
	            })($this);
            	$(this).data("iconPicker",{attached:true});
            }

	        function createUI($element){
	        	$popup=$('<div/>',{
	        		css: {
		        		'top':$element.offset().top+$element.outerHeight()+6,
		        		'left':$element.offset().left
		        	},
		        	class:'icon-popup'
	        	})

	        	$popup.html('<div class="ip-control"> \
						          <ul> \
						            <li><a href="javascript:;" class="btn btn-sm" data-dir="-1"><span class="fa fa-backward"></span></a></li> \
						            <li><input type="text" class="ip-search glyphicon  glyphicon-search" placeholder="Search" /></li> \
						            <li><a href="javascript:;"  class="btn btn-sm" data-dir="1"><span class="fa fa-forward"></span></a></li> \
						          </ul> \
						      </div> \
						     <div class="icon-list"> </div> \
					         ').appendTo("body");

	        	$popup.addClass('dropdown-menu').show();
				$popup.mouseenter(function() {  mouseOver=true;  }).mouseleave(function() { mouseOver=false;  });

	        	var lastVal="", start_index=0,per_page=30,end_index=start_index+per_page;
	        	$(".ip-control .btn",$popup).click(function(e){
	                e.stopPropagation();
	                var dir=$(this).attr("data-dir");
	                start_index=start_index+per_page*dir;
	                start_index=start_index<0?0:start_index;
	                if(start_index+per_page<=210){
	                  $.each($(".icon-list>ul li"),function(i){
	                      if(i>=start_index && i<start_index+per_page){
	                         $(this).show();
	                      }else{
	                        $(this).hide();
	                      }
	                  });
	                }else{
	                  start_index=180;
	                }
	            });

	        	$('.ip-control .ip-search',$popup).on("keyup",function(e){
	                if(lastVal!=$(this).val()){
	                    lastVal=$(this).val();
	                    if(lastVal==""){
	                    	showList(icons);
	                    }else{
	                    	showList($element, $(icons)
                                .map(function(i,v){
                                    if(v.toLowerCase().indexOf(lastVal.toLowerCase())!=-1){return v}
                                }).get());
						}
	                }
	            });
	        	$(document).mouseup(function (e){
				    if (!$popup.is(e.target) && $popup.has(e.target).length === 0) {
				        removeInstance();
				    }
				});
	        }
	        function removeInstance(){
	        	$(".icon-popup").remove();
	        }
	        function showList($element,arrLis){
	        	$ul=$("<ul>");
	        	for (var i in arrLis) {
	        		$ul.append("<li><a title=\""+arrLis[i]+"\"><span class=\""+arrLis[i]+"\"></span></a></li>");
	        	};
	        	$(".icon-list",$popup).html($ul);
	        	$(".icon-list li a",$popup).click(function(e){
	        		e.preventDefault();
	        		var title=$(this).attr("title");
	        		$element.val(title);
	        		removeInstance();
	        	});
	        }
        });
    }

}(jQuery));
