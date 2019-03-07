<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Licence $licence
 */
?>
<div class="licences form large-12 medium-11 columns content">
    <?= $this->Form->create($licence, ['id' => 'licence_form', 'type' => 'file']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <div style="clear: both;"></div>
    <fieldset>
        <legend><?= __('Edit Licence') ?></legend>

        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('name', ['readOnly' => 'readOnly']);
                echo $this->Form->control('key_text', ['readOnly' => 'readOnly']);
                echo $this->Form->control('description', ['readOnly' => 'readOnly', 'type' => 'textarea']);
            ?>
        </div>

        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)', 'hidden' => 'hidden', 'disabled' => 'disabled']); ?>
            <img src='data:image/png;base64,<?=$licence->image?>' id='output'/>
        </div>

        <div style="clear: both;"></div>

        <div class="left third-width">
            <?php echo $this->Form->control('start_time', ['readOnly' => 'readOnly', 'type' => 'text', 'class' => 'datepicker']); ?>
        </div>
        <div class="left third-width">
            <?php echo $this->Form->control('end_time', ['readOnly' => 'readOnly', 'type' => 'text', 'class' => 'datepicker', 'empty' => true]); ?>
        </div>

        <div style="clear: both;"></div>
    </fieldset>
    
    <?php 
        if($licence->deleted == null){
            echo $this->Html->link(__('Deactivate licence'), ['controller' => 'Licences', 'action' => 'deactivate', $licence->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', $licence->name)]);
        } else {
            echo $this->Html->link(__('Reactivate licence'), ['controller' => 'Licences', 'action' => 'reactivate', $licence->id], ['confirm' => __('Are you sure you want to reactivate {0}?', $licence->name), 'style' => 'margin-right: 25px;']);  
            if($licence->loan_count == 0){
                echo $this->Html->link(__('Delete licence'), ['controller' => 'Licences', 'action' => 'delete', $licence->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', $licence->name)]);
            }
        }
    ?>
    <button type="button" class="right editdone" id="cancelButton" onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    
    <h3><?=__('Products')?></h3>
    <input id='autocomplete' type ='text' style='display:none'>
    <table id='products_table' cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= __("Name") ?></a></th>
                <th scope="col"><?= __("Product") ?></a></th>
                <th scope="col"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Licence count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id='products_table_body'>
            <?php foreach ($licence->products as $product): ?>
            <tr id='product_row_<?=$product->id?>'>
                <td><a href='/products/<?=$product->id?>'><?= h($product->name) ?></a></td>
                <td><a href='/products/<?=$product->id?>'><?= h($product->platform) ?></a></td>
                <td><a href='/products/<?=$product->id?>'><?= h($product->description)?></a></td>
                <td><a href='/products/<?=$product->id?>'><?= h($product->licence_count)?></a></td>
                <td><a class='unlink_link delete-link' onclick='removeLink(<?=$product->id?>)' style="display:none;"><?=__("Remove")?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function doneEditing(){
        if ($("#licence_form").data("changed")){
            $('#licence_form').submit();
        } else {
            setReadOnly(true);
        }
    }

    function cancel(){
        if(confirm("<?=__('Cancel all your changes?')?>")){
            location.reload(true);
        }
    }

    function removeLink(product_id){
        var rowCount = document.getElementById('products_table').rows.length;
        if (rowCount > 2)
        {
            $.ajax({
                method: 'post',
                url : "/products/unlink.json?product=" + product_id + "&licence=<?= $licence->id ?>",
                headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
                success: function( response ){
                    $('#product_row_' + product_id).remove();
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert('The association could not be deleted');
                    console.log(jqXHR.responseText);
                }
            });
        }
        else
        {
            alert("<?=__("Cannot remove Product. A Licence must always have at least one Product.")?>");
        }
    }

    function setReadOnly(readOnly){
        $('#name').attr('readOnly', readOnly);
        $('#key-text').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        $('#start-time').attr('readOnly', readOnly);
        $('#end-time').attr('readOnly', readOnly);
        
        if(readOnly){
            //View
            $('#image').hide();
            $('#image').attr('disabled', 'disabled');
            $('#autocomplete').hide();

            $(".datepicker").datepicker("option", "disabled", true);

            $('#doneButton').hide();
            $('.unlink_link').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#image').show();
            $('#image').removeAttr('disabled');
            $('#autocomplete').show();

            $(".datepicker").datepicker("option", "disabled", false);

            $('#doneButton').show();
            $('.unlink_link').show();

            $('#editButton').hide();
        }
    }

    $('document').ready(function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(date, i) {
                $('#preset-dates').val('custom');
            }
        }).attr("autocomplete", "off");

        $end_time = "<?=$licence->end_time?>";

        $('#start-time').datepicker('setDate', new Date("<?=$licence->start_time?>"));
        if ($end_time != ""){
            $('#end-time').datepicker('setDate', new Date($end_time));
        }

        $(".datepicker").datepicker("option", "disabled", true);

        $("#licence_form :input:not(#autocomplete)").on('change paste keyup', (function() {
            $("#licence_form").data("changed",true);
            $('#cancelButton').show();
        }));

        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/products/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc', licence_id: '<?=$licence->id?>'},
                    success: function( response ){
                        var results = [];
                        $.each(response.products, function(idx, elem){
                            if(!$('#product_row_' + elem.id).length){
                                var entry = {
                                    label: elem.name,
                                    data: elem
                                };
                                results.push(entry);
                            }
                        });
                        show(results);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log(jqXHR.responseText);
                    }
                });
            },
            minLength: 1,
            autoFocus: true,
            select: function (event, ui) {
                let elem = ui.item.data;

                $.ajax({
                    method: 'post',
                    url : "/products/link.json?product=" + elem.id + "&licence=<?= $licence->id ?>",
                    headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
                    success: function( response ){
                        let table = $('#products_table_body');
                        table.append(`
                            <tr>
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
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });
    });
</script>