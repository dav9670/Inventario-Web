<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Licence $licence
 */
?>
<div class="licences form large-12 medium-11 columns content">
    <?= $this->Form->create($licence, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Licence') ?></legend>
    
        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('name');
                echo $this->Form->control('key_text');
                echo $this->Form->control('description', ['type' => 'textarea']);
            ?>
        </div>
        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)']); ?>
            <img id='output'/>
        </div>

        <div style="clear: both;"></div>
        
        <div class="left third-width">
            <?php echo $this->Form->control('start_time', ['type' => 'text', 'class' => 'datepicker']); ?>
        </div>
        <div class="left third-width">
            <?php echo $this->Form->control('end_time', ['type' => 'text', 'class' => 'datepicker', 'empty' => true]); ?>
        </div>
        
    </fieldset>
    <h3><?=__('Products')?></h3>
    <input id='autocomplete' type ='text'>
    <input type='hidden' name='products[_ids]' value=""/>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= __("Name") ?></a></th>
                <th scope="col"><?= __("Platform") ?></a></th>
                <th scope="col"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Licence count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id='products_table_body'>
        </tbody>
    </table>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function removeRow(id) {
        if(confirm('<?= __('Are you sure you want to remove this product?')?>')){
            $('#product_row_' + id).remove();
        }
    }

    $('document').ready(function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(date) {
                $('#preset-dates').val('custom');
            }
        });

        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/products/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc'},
                    success: function( response ){
                        var results = [];
                        $.each(response.products, function(idx, elem){
                            if(!$('#product_row_' + elem.id).length){
                                var entry = {
                                    label: elem.name + " for " + elem.platform,
                                    data: elem
                                };
                                results.push(entry);
                            }
                        });
                        show(results);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log(textStatus);
                    }
                });
            },
            minLength: 1,
            autoFocus: true,
            select: function (event, ui) {
                let table = $('#products_table_body');
                let elem = ui.item.data;

                table.append(`
                    <tr id='product_row_` + elem.id + `'>
                        <input type='hidden' name='products[_ids][]' value='` + elem.id + `'/>
                        <td><a href='/products/` + elem.id + `'>` + elem.name + `</a></td>
                        <td><a href='/products/` + elem.id + `'>` + elem.platform + `</a></td>
                        <td><a href='/products/` + elem.id + `'>` + elem.description + `</a></td>
                        <td><a href='/products/` + elem.id + `'>` + elem.licence_count + `</a></td>
                        <td class='actions'>
                            <a class='unlink_link delete-link' onclick='removeLink(` + elem.id + `)'><?=__('Remove')?></a>
                        </td>
                    </tr>
                `);

                $('#autocomplete').val('');
                event.preventDefault();
            }
        });
    });
</script>