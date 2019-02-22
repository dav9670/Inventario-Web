<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<div class="products form large-12 medium-11 columns content">
    <?= $this->Form->create($product, ['id' => 'product_form']) ?>
    <button type="button" class="right" id="viewButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(true)' hidden="hidden"><?=__('View')?></button>
    <button type="button" class="right" id="editButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(false)'><?=__('Edit')?></button> 
    <fieldset>
        <legend><?= __('Product') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('platform', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['id' => 'submit', 'hidden']) ?>
    <?= $this->Form->end() ?>
    <?= $this->Form->postLink(__('Delete product'), ['controller' => 'Products', 'action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete {0}?', $product->name . " for " . $product->platform)]);?>

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
                    <?= $this->Form->postLink(__('Unlink'), ['controller' => 'products', 'action' => 'unlink', '?' => ['product' => $product->id, 'licence' => $licence->id]], ['confirm' => __('Are you sure you want to delete the association between {0} and {1}?', $licence->name, $product->name), 'class' => 'unlink_link', 'hidden']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    $("#skill_form :input").change(function() {
        $("#skill_form").data("changed",true);
    });

    function setReadOnly(readOnly){
        if(readOnly){
            //View
            if ($("#skill_form").data("changed")) {
                if(confirm("<?=__('Return to view mode and cancel all your changes?')?>")){
                    location.reload(true);
                }
            } else {
                $('#name').attr('readOnly', readOnly);
                $('#platform').attr('readOnly', readOnly);
                $('#description').attr('readOnly', readOnly);

                $('#viewButton').hide();
                $('#submit').hide();
                $('#related a[class="unlink_link"').hide();

                $('#editButton').show();
            }
        }else{
            //Edit
            $('#name').attr('readOnly', readOnly);
            $('#platform').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#editButton').hide();

            $('#viewButton').show();
            $('#submit').show();
            $('#related a[class="unlink_link"').show();
        }
    }
</script>
