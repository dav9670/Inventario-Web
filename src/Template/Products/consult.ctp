<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<div class="products form large-12 medium-11 columns content">
    <?= $this->Form->create($product, ['id' => 'product_form']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Product') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('platform', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['type' => 'textarea', 'readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('Delete product'), ['controller' => 'Products', 'action' => 'delete', $product->id], ['class' => 'delete-link', 'confirm' => $product->licence_count == 0 ? __('Are you sure you want to delete {0}?', $product->name . " for " . $product->platform) : __('Are you sure you want to delete {0}? {1} items are associated with it.', $product->name . " for " . $product->platform, $product->licence_count)]);?>

    <div class="related">
        <h4><?= __('Related Licences') ?></h4>
        <?php if (!empty($product->licences)): ?>
        <table id="related" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __("Products") ?></th>
                <th scope="col"><?= __("Status") ?></th>
                <th scope="col"><?= __("Available") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($product->licences as $licence): ?>
            <tr class="clickable-row">
                <td><a href='/licences/<?= h($licence->id) ?>'><img src="data:image/png;base64, <?= h($licence->image) ?>" alt="<?= h($licence->name) ?>" width=100/></a></td>
                <td><a href='/licences/<?= h($licence->id) ?>'><?= h($licence->name) ?></a></td>
                <td><a href='/licences/<?= h($licence->id) ?>'><?= h($licence->description) ?></a></td>

                <?php if (count($licence->products_list) > 3): ?>
                    <td><a href='/licences/<?= h($licence->id) ?>'><?= h(implode(", ", array_slice($licence->products_list,0,3)) . "...") ?></a></td>
                <?php else: ?>
                    <td><a href='/licences/<?= h($licence->id) ?>'><?= h(implode(", ", array_slice($licence->products_list,0,3))) ?></a></td>
                <?php endif; ?>

                <td><a href='/licences/<?= h($licence->id) ?>'><?= h($licence->status) ?></a></td>

                <?php if ($licence->available): ?>
                    <td><a href='/licences/<?= h($licence->id) ?>'><img src='/img/good.png' alt='Available' width=20 height=20></a></td>
                <?php else: ?>
                    <td><a href='/licences/<?= h($licence->id) ?>'><img src='/img/bad.png' alt='Not Available' width=20 height=20></a></td>
                <?php endif; ?>

                <td class="actions">
                    <?= $this->Form->postLink(__('Unlink'), ['controller' => 'products', 'action' => 'unlink', '?' => ['product' => $product->id, 'licence' => $licence->id]], ['confirm' => __('Are you sure you want to delete the association between {0} and {1}?', $licence->name, $product->name . " for " . $product->platform), 'class' => 'unlink_link delete-link', 'hidden']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    function doneEditing(){
        if ($("#product_form").data("changed")){
            var confirmed = true;
            <?php if($product->licence_count > 0) { ?>
                confirmed = confirm('<?= __('Are you sure you want to modify {0}? {1} items are associated with it.', $product->name . " for " . $product->platform, $product->licence_count) ?>');
            <?php } ?>
            if(confirmed){
                $('#product_form').submit();
            }
        } else {
            setReadOnly(true);
        }
    }

    function cancel(){
        if(confirm("<?=__('Cancel all your changes?')?>")){
            location.reload(true);
        }
    }

    function setReadOnly(readOnly){
        if(readOnly){
            //View
            $('#name').attr('readOnly', readOnly);
            $('#platform').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#doneButton').hide();
            $('.unlink_link').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#name').attr('readOnly', readOnly);
            $('#platform').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#editButton').hide();

            $('#doneButton').show();
            $('#submit').show();
            $('.unlink_link').show();
        }
    }

    $('document').ready(function(){
        $("#product_form :input").on('change paste keyup', (function() {
            $("#product_form").data("changed",true);
            $('#cancelButton').show();
        }));
    });
</script>
