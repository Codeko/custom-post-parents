
;(function ($) {
    $(function () {
        var $ul = $('.custom-post-parents-selectedlist');
        $ul.on('click','.ntdelbutton',function(){
            var currentItem=$(this);
            var section=currentItem.data("section");
            var currentSelect=$("select[data-section='"+section+"']");
            var idOption=currentItem.parent().text()+"_"+section;
            currentSelect.find("#"+idOption).show();
            var hiddenchildren=currentSelect.find(":hidden").length;
            var children=currentSelect.children().length-1;
            if(hiddenchildren<children)
                currentSelect.removeAttr("disabled")
            currentItem.parents('span').remove();
        });
        $.each($("select"),function(i,v){
            var section=$("#"+v.id).data("section");
            var it=$("<option>").attr("name","custom-post-parents-post_types").attr('id','defecto_'+section);
            it.text(parents.first_option_select);
            $("#"+v.id).append(it);
            $.each(parents.lookup,function(i,c){
                var id=c+"_"+section;
                var it=$("<option>").attr("name","custom-post-parents-post_types").attr("id",id);
                it.text(c);
                $("#"+v.id).append(it);
            })
        })

        $('.custom-post-parents-selectlist-add').change(function(event){
            var currentItem=$(this)
            var section=currentItem.data("section")
            var selectedElements = $('input[name="custom-post-parents-post_types['+section+'][]"]');
            var currentVals = [];
            var currentValue=currentItem.val();
            if(currentValue!=parents.first_option_select)
            $(" option:selected",this).hide();
            var hiddenchildren=$(":hidden",this).length;
            var children=$(this).children().length-1
            if(hiddenchildren==children)
                currentItem.attr("disabled","disabled");
            $.each(selectedElements, function (i,v) {
                    currentVals.push(currentItem.val());
                });
            var ite = $([
                '<span>',
                '<input name="custom-post-parents-post_types['+section+'][]" type="hidden" value="'+currentValue+'"/>',
                "<a name='"+currentValue+"'class='ntdelbutton' data-section='"+section+"'><span class='remove-tag-icon' aria-hidden='true'></span></a>",
                currentValue,
                '</span>'
                ].join(''));
                $("div[data-section='"+section+"']").append(ite);
            $(":nth-child(1)",this).attr("selected",true);

        });
    });
})(jQuery);