
;(function ($) {
    $(function () {
        $('.custom-post-parents-selectlist-add').removeAttr("disabled");
        var $ul = $('.custom-post-parents-selectedlist');
        $ul.on('click','.ntdelbutton',function(){
            var $currentItem=$(this);
            var $section=$currentItem.data("section");
            var ind=$currentItem.attr("ind");
            var $currentSelect=$("select[data-section='"+$section+"']");
            $currentSelect.find("option[ind="+ind+"]").show();
            var hiddenChildrenSelect=$currentSelect.find(":hidden").length;
            var childrenSelect=$currentSelect.children().length-1;
            if(hiddenChildrenSelect<childrenSelect){
                $currentSelect.removeAttr("disabled");
            }
            $currentItem.parents('span').remove();
        });
        var ind=0;
        $.each($("select"),function(i,v){
            var section=$("#"+v.id).data("section");
            var it=$("<option>").attr("name","custom-post-parents-post_types").attr('id',ind+'_'+section).attr("ind",ind);
            it.text(postParentConfig.defalutOption);
            $("#"+v.id).append(it);
            ind++;
            $.each(postParentConfig.lookup,function(o,c){
                var id=ind+"_"+section;
                var it=$("<option>").attr("name","custom-post-parents-post_types").attr("id",id).attr("ind",ind);
                it.text(c);
                $("#"+v.id).append(it);
                ind++;
            })
        });
        $('.custom-post-parents-selectlist-add').change(function(){
            var $currentItem=$(this);
            var $section=$currentItem.data("section");
            var $selectedElements = $('input[name="custom-post-parents-post_types['+$section+'][]"]');
            var currentVals = [];
            var currentValue=$currentItem.val();
            var ind=$("option:selected",this).attr("ind");
            if(currentValue!=postParentConfig.defalutOption){
                $("option:selected",this).hide();
            }
            var hiddenChildrenSelect=$(":hidden",this).length;
            var childrenSelect=$(this).children().length-1;
            if(hiddenChildrenSelect==childrenSelect){
                $currentItem.attr("disabled","disabled");
            }
            $.each($selectedElements, function() {
                    currentVals.push($currentItem.val());
                });
            var $ite = $([
                '<span>',
                '<input name="custom-post-parents-post_types['+$section+'][]" type="hidden" value="'+currentValue+'"/>',
                "<a name='"+currentValue+"'class='ntdelbutton' ind='"+ind+"' data-section='"+$section+"'><span class='remove-tag-icon' aria-hidden='true'></span></a>",
                currentValue,
                '</span>'
                ].join(''));
                $("div[data-section='"+$section+"']").append($ite);
            $(":first-child",this).attr("selected",true);
        });
    });
})(jQuery);