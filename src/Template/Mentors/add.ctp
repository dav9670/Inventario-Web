<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mentor $mentor
 */
?>
<div class="mentors form large-12 medium-11 columns content">
    <?= $this->Form->create($mentor) ?>
    <fieldset>
        <legend><?= __('Add Mentor') ?></legend>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('first_name');
            echo $this->Form->control('last_name');
            echo $this->Form->control('description', ['type' => 'textarea']);
            echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)']);
            #echo $this->Form->control('skills._ids', ['options' => $skills]);
        ?>
        <img id='output'/>

    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
    <input id='autocomplete' type ='text'>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><a id='name_sort' class='asc'><?= __("Name") ?></a></th>
                <th scope="col"><a id='description_sort'><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Mentor count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id='skills_table_body'>
        </tbody>
    </table>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    $('document').ready(function(){
        $("#autocomplete").autocomplete({
            source: function(request, response){
                var data = [
                    {label: 'choice1', value: 'value1', other: 'other'},
                    {label: 'choice2', value: 'value2'},
                ];
                response(data);
            },
            minLength: 1, //This is the min amount of chars before auto complete kicks in
            autoFocus: true,
            select: function (event, ui) {
                $('#skills_table_body').append('<tr><td>' + ui.item.label + '</td></tr>');
            }
        });
    });
</script>