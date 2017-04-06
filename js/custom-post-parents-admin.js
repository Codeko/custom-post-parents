
/* global postParentConfig */

;
(function ($) {
    "use strict";
    $(function () {
        var $ul = $('.custom-post-parents-selectedlist');
        $ul.on('click', '.ntdelbutton', function () {
            var $currentItem = $(this);
            var section = $currentItem.data("section");
            var ind = $currentItem.data("ind");
            var $currentSelect = $("select[data-section='" + section + "']");
            $currentSelect.find("option[data-ind=" + ind + "]").show();
            var hiddenChildrenSelect = $currentSelect.find(":hidden").length;
            var childrenSelect = $currentSelect.children().length - 1;
            if (hiddenChildrenSelect < childrenSelect) {
                $currentSelect.removeAttr("disabled");
            }
            $currentItem.parents('span').remove();
        });
        var ind = 0;
        $(".custom-post-parents-selectlist-add").each(function (i, v) {
            var section = $("#" + v.id).data("section");
            var $selectedElements = $('input[name="custom-post-parents-post_types[' + section + '][]"]');
            var it = $("<option>").attr("data-ind", ind).attr("name", "custom-post-parents-post_types").attr('id', ind + '_' + section);
            it.text(postParentConfig.defaultOption);
            $("#" + v.id).append(it);
            ind++;
            $.each(postParentConfig.lookup, function (o, c) {
                var id = ind + "_" + section;
                var it = $("<option>").attr("data-ind", ind).attr("name", "custom-post-parents-post_types").attr("id", id);
                it.text(c);
                $.each($selectedElements, function (p, q) {
                    if (c === q.value) {
                        $('input[name="custom-post-parents-post_types[' + section + '][]"][value="' + q.value + '"]').next().data("ind", ind);
                        it.hide();
                    }
                });
                $("#" + v.id).append(it);
                ind++;
            });
        });
        $(".tagchecklist").each(function (i, v) {
            v.id = i;
            var section = $("#" + v.id).data("section");
            var $selectedElements = $('input[name="custom-post-parents-post_types[' + section + '][]"]');
            var select = $("select[data-section='" + section + "']");
            var childrenSelect = select.children().length - 1;
            if ($selectedElements.length === childrenSelect) {
                select.attr("disabled", "disabled");
            } else
            {
                select.removeAttr("disabled");
            }
        });
        $('.custom-post-parents-selectlist-add').change(function () {
            var $currentItem = $(this);
            var section = $currentItem.data("section");
            var currentValue = $currentItem.val();
            var ind = $("option:selected", this).data("ind");
            if (currentValue !== postParentConfig.defalutOption) {
                $("option:selected", this).hide();
            }
            var hiddenChildrenSelect = $(":hidden", this).length;
            var childrenSelect = $(this).children().length - 1;
            if (hiddenChildrenSelect === childrenSelect) {
                $currentItem.attr("disabled", "disabled");
            }
            var $ite = $([
                '<span>',
                '<input name="custom-post-parents-post_types[' + section + '][]" type="hidden" value="' + currentValue + '"/>',
                "<a class='ntdelbutton' data-ind=" + ind + " data-section='" + section + "'><span class='remove-tag-icon' aria-hidden='true'></span></a>&nbsp",
                currentValue,
                '</span>'
            ].join(''));
            $("div[data-section='" + section + "']").append($ite);
            $(":first-child", this).attr("selected", true);
        });
    });
})(jQuery);