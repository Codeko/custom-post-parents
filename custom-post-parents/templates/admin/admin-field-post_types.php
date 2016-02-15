<?php foreach($available_schemas as $superPostType => $lookupLabel): $info  = isset($selected[$superPostType]) ? $selected[$superPostType] : array('values' => array()); ?>
    <p>
        <label><strong><?php echo $lookupLabel?> </strong> puede tener los siguentes padres:</label>
        <div class="tagchecklist custom-post-parents-selectedlist" data-section="<?php echo $superPostType ?>">

        <?php foreach($info['values'] as $postType): ?>
            <span>
                <input name="custom-post-parents-post_types[<?php echo $superPostType?>][]" type="hidden" value="<?php echo $postType; ?>" />
                <a  class="ntdelbutton">X</a>&nbsp;
                <?php if (isset($lookup[$postType])): ?>
                    <?php echo $lookup[$postType] ?>
                <?php else: ?>
                    <?php echo $postType; ?>
                <?php endif ?>
            </span>
        <?php endforeach ?>
        </div>

        <input placeholder="<?php _e('Añadir CPT','custom-post-parents')?>" type="text" data-section="<?php echo $superPostType ?>" class="custom-post-parents-selectlist-add" />
    </p>
<?php endforeach ?>

