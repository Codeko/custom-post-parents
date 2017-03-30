
;(function ($) {
    $(function () {
        var $ul = $('.custom-post-parents-selectedlist');
        $ul.on('click','.ntdelbutton',function(){
            var id_option=$(this).attr("name");
            var section=$(this).attr("data-section");
            $("#cpt-f-"+section+" >option[id='"+id_option+"']").show();
            $(this).parents('span').remove();
        });
        var it=$("<option>").attr("name","custom-post-parents-post_types").attr('id','defecto');
        it.text(data.first_option_select);
        $('.custom-post-parents-selectlist-add').append(it)
        $.each(data.lookup,function(i,v){
            var it=$("<option>").attr("name","custom-post-parents-post_types").attr("id",v);
            it.text(v);
            $('.custom-post-parents-selectlist-add').append(it);
        })

        $('.custom-post-parents-selectlist-add').change(function(event){
            var target=event.target;
            var section=target.getAttribute("data-section")
            var selectedElements = $('input[name="custom-post-parents-post_types['+section+'][]"]');
            var currentVals = [];
            var valActual=$(this).val();
            if(valActual!=data.first_option_select)
            $("#cpt-f-"+section+" option:selected").hide();
            $.each(selectedElements, function (i,v) {
                    currentVals.push($(this).val());
                });
            if ($.inArray(valActual, currentVals) === -1 && valActual!=data.first_option_select) {
                var ite = $([
                    '<span>',
                    '<input name="custom-post-parents-post_types['+section+'][]" type="hidden" value="'+valActual+'"/>',
                    "<a name='"+valActual+"'class='ntdelbutton' data-section='"+section+"'><span class='remove-tag-icon' aria-hidden='true'></span></a>&nbsp;",
                    valActual,
                    '</span>'
                     ].join(''));
                $("div[data-section='"+section+"']").append(ite);
            }
            $(".custom-post-parents-selectlist-add>#defecto").attr("selected",true);

        });
    });
})(jQuery);