<?php foreach($available_schemas as $superPostType => $lookupLabel): $info  = isset($selected[$superPostType]) ? $selected[$superPostType] : array('values' => array()); ?>
    <p>
        <label><strong><?php echo $lookupLabel?> </strong> post parents:</label>
        <div class="tagchecklist custom-post-parents-selectedlist" data-section="<?php echo $superPostType ?>">
        <?php foreach($info['values'] as $postType): ?>
            <span>
                <input name="custom-post-parents-post_types[<?php echo $superPostType?>][]" type="hidden" value="<?php echo $postType; ?>" />
                <a  class="ntdelbutton"><span class="remove-tag-icon" aria-hidden="true"></span></a>&nbsp;
                <?php if (isset($lookup[$postType])): ?>
                    <?php echo $lookup[$postType] ?>
                <?php else: ?>
                    <?php echo $postType; ?>
                <?php endif ?>
            </span>
        <?php endforeach ?>
        </div>

        <!--<input id="cpt-f-<?php echo $superPostType;?>" placeholder="<?php _e('Add custom post type','custom-post-parents')?>" type="text" data-section="<?php echo $superPostType ?>" class="custom-post-parents-selectlist-add" />-->
    <select id="cpt-f-<?php echo $superPostType;?>" placeholder="<?php _e('Add custom post type','custom-post-parents')?>" type="text" data-section="<?php echo $superPostType ?>" class="custom-post-parents-selectlist-add">
    </select>
    </p>
<?php endforeach ?>

