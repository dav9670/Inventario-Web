<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan $loan
 */
echo $this->Html->css('jquery.datetimepicker.min.css');
echo $this->Html->script('jquery.datetimepicker.full.js', array('inline' => false));
?>
<div class="loans form large-12 medium-11 columns content">
    <?= $this->Form->create($loan, ['id' => 'return_form']) ?>
    <fieldset>
        <legend><?= __('Return Loan') ?></legend>
        <div style='width: 50%; float: left;'>
            <h3><?= $this->Html->Link($loan->user->email, ['controller' => 'users', 'action' => 'consult', $loan->item_id])?></h3>
            <img src='data:image/png;base64,<?=$loan->user->image?>' id='output'/>
        </div>
        <div style='width: 50%; float: right;'>
            <?php
                if($loan->item_type == 'mentors'){ 
                ?>
                    <?php $mentor = $loan->getItem(); ?>
                    <h3><?= $this->Html->Link($mentor->email, ['controller' => 'mentors', 'action' => 'consult', $mentor->id])?></h3>
                    <img src='data:image/png;base64,<?=$mentor->image?>' id='output'/>
                    <h3><?=__('Skills')?></h3>
                    <table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __("Name") ?></a></th>
                                <th scope="col"><?= __("Description") ?></a></th>
                                <th scope="col"><?= __("Mentor count") ?></th>
                            </tr>
                        </thead>
                        <tbody id='skills_table_body'>
                            <?php foreach ($mentor->skills as $skill): ?>
                            <tr id='skill_row_<?=$skill->id?>'>
                                <td><a href='skills/<?=$skill->id?>'><?= h($skill->name) ?></a></td>
                                <td><a href='skills/<?=$skill->id?>'><?= h($skill->description)?></a></td>
                                <td><a href='skills/<?=$skill->id?>'><?= h($skill->mentor_count)?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php
                } else if ($loan->item_type == 'rooms') {
                ?>
                    <?php $room = $loan->getItem(); ?>
                    <h3><?= $this->Html->Link($room->name, ['controller' => 'rooms', 'action' => 'consult', $room->id])?></h3>
                    <img src='data:image/png;base64,<?=$room->image?>' id='output'/>
                    <h3><?=__('Services')?></h3>
                    <table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __("Name") ?></a></th>
                                <th scope="col"><?= __("Description") ?></a></th>
                                <th scope="col"><?= __("Room count") ?></th>
                            </tr>
                        </thead>
                        <tbody id='services_table_body'>
                            <?php foreach ($room->services as $service): ?>
                            <tr id='service_row_<?=$service->id?>'>
                                <td><a href='services/<?=$service->id?>'><?= h($service->name) ?></a></td>
                                <td><a href='services/<?=$service->id?>'><?= h($service->description)?></a></td>
                                <td><a href='services/<?=$service->id?>'><?= h($service->room_count)?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php
                } else if ($loan->item_type == 'licences') {
                ?>
                    <?php $licence = $loan->getItem();?>
                    <h3><?= $this->Html->Link($licence->name, ['controller' => 'licences', 'action' => 'consult', $licence->id])?></h3>
                    <img src='data:image/png;base64,<?=$licence->image?>' id='output'/>
                    <h3><?=__('Products')?></h3>
                    <table id='products_table' cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __("Name") ?></a></th>
                                <th scope="col"><?= __("Product") ?></a></th>
                                <th scope="col"><?= __("Description") ?></a></th>
                                <th scope="col"><?= __("Licence count") ?></th>
                            </tr>
                        </thead>
                        <tbody id='products_table_body'>
                            <?php foreach ($licence->products as $product): ?>
                            <tr id='product_row_<?=$product->id?>'>
                                <td><a href='/products/<?=$product->id?>'><?= h($product->name) ?></a></td>
                                <td><a href='/products/<?=$product->id?>'><?= h($product->platform) ?></a></td>
                                <td><a href='/products/<?=$product->id?>'><?= h($product->description)?></a></td>
                                <td><a href='/products/<?=$product->id?>'><?= h($product->licence_count)?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php
                } else if ($loan->item_type == 'equipments') {
                ?>
                    <?php $equipment = $loan->getItem();?>
                    <h3><?= $this->Html->Link($equipment->name, ['controller' => 'equipments', 'action' => 'consult', $equipment->id])?></h3>
                    <img src='data:image/png;base64,<?=$equipment->image?>' id='output'/>
                    <h3><?=__('Categories')?></h3>
                    <table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __("Name") ?></a></th>
                                <th scope="col"><?= __("Description") ?></a></th>
                                <th scope="col"><?= __("Equipment count") ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id='categories_table_body'>
                            <?php foreach ($equipment->categories as $category): ?>
                            <tr id='category_row_<?=$category->id?>'>
                                <td><a href='categories/<?=$category->id?>'><?= h($category->name) ?></a></td>
                                <td><a href='categories/<?=$category->id?>'><?= h($category->description)?></a></td>
                                <td><a href='categories/<?=$category->id?>'><?= h($category->equipment_count)?></a></td>
                                <td><a class='unlink_link delete-link' onclick='removeLink(<?=$category->id?>)' style="display:none;">Remove</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php
                }
            ?>
            <div class="left half-width">
                <?= $this->Form->control('start_time', ['type' => 'text', 'class' => 'datetpicker', 'readonly']); ?>
            </div>
            <div class="right half-width">
                <?= $this->Form->control('end_time', ['type' => 'text', 'class' => 'datepicker', 'readonly']); ?>
            </div>
        </div>
        <div style='clear:both;'></div>
        <div style='width: 50%; float: left;'>
            <input type="hidden" name="returned" id="returned" value="<?=date("Y-m-d H:i:s")?>">
            <div style='clear:both;'></div>
            <?php 
            if($loan->overtime_hours_late != 0){
            ?>
                <?=__("Total hours late: {0}", $loan->overtime_hours_late)?><br>
                <?=__("Hourly late: {0}$", number_format($loan->overtime_hourly_rate), 2)?><br>
                <?=__("Overtime fee: {0}$", number_format($loan->overtime_fee, 2))?><br>
            <?php
            }
            ?>
        </div>
    </fieldset>
    <?= $this->Form->end() ?>
    <button class='right' onclick='returnLoan()'><?=__('Return')?></button>
</div>

<script>
    function returnLoan(){
        if(confirm("<?=__('Are you sure you want to return this loan? ') . ($loan->overtime_hours_late != 0 ? __('This loan has {0}$ in overtime fees.', number_format($loan->overtime_fee, 2)): '')?>")){
            $('#return_form').submit();
        }
    }
</script>