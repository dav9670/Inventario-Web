<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan $loan
 */
?>
<div class="loans form large-12 medium-11 columns content">
    <?= $this->Form->create($loan) ?>
    <fieldset>
        <legend><?= __('Add Loan') ?></legend>
        
        <input type='hidden' id='user-id' name='user_id' required='required'/>
        <label for="search_user"><?= __('User:') ?></label>
        <span id='user_selected'></span>
        <input type="text" name="search_user" id="search_user">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='user_email_sort' class='asc'><?= __("Email Adress") ?></a></th>
                    <th scope="col"><a id='user_admin_status_sort'><?= __("Admin Status") ?></a></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody id="table_activated">
            </tbody>
            <tbody id="table_archived" hidden="hidden">
            </tbody>
        </table>

        <label for="item_type"><?= __('Item type') ?></label>
        <?= $this->Form->select('item_type', ['mentors' => 'Mentors', 'rooms' => 'Rooms', 'licences' => 'Licences', 'equipments' => 'Equipments'], ['id' => 'item_type']); ?>
        <input type='hidden' id='item-id' name='item_id' required='required'/>
        <label for="search_item"><?= __('Item:') ?></label>
        <span id='item_selected'></span>
        <input type="text" name="search_item" id="search_item">
        <table id='item-table' cellpadding="0" cellspacing="0" style='display:none;'>
            <thead id='item-table-head'>
                <tr>
                </tr>
            </thead>
            <tbody id='item-table-body'>
            </tbody>
        </table>

        <?= $this->Form->control('start_time', ['type' => 'text', 'class' => 'datepicker']); ?>
        <?= $this->Form->control('end_time', ['type' => 'text', 'class' => 'datepicker', 'empty' => true]); ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    var sort = {
        user: {
            field: 'email',
            dir: 'asc'
        },
        item: {
            field: 'email',
            dir: 'asc'
        }
    };

    let itemDict = {
        mentors: function(){
            setHeadersMentors();
            sort['item'].field = '';
            sort['item'].dir = '';
            sortSetter('email');
            setBodyMentors();
        },
        rooms: function(){
            setHeadersRooms();
            sort['item'].field = '';
            sort['item'].dir = '';
            sortSetter('name');
            setBodyRooms();
        },
        licences: function(){
            setHeadersLicences();
            sort['item'].field = '';
            sort['item'].dir = '';
            sortSetter('name');
            setBodyLicences();
        },
        equipments: function(){
            setHeadersEquipments();
            sort_field = '';
            sort_dir = '';
            sortSetter('name');
            setBodyEquipments();
        }
    }

    function searchUsers( keyword ){
        var data = keyword;

        $.ajax({
                method: 'get',
                url : "/users/search.json",
                data: {keyword:data, sort_field: sort.user.field, sort_dir: sort.user.dir},
                success: function( response ){
                    
                    var table_name = "table_activated";
                    var array_name = "users";

                    var table = $("#" + table_name);
                    table.empty();

                    usersArray = response[array_name];
                    $.each(usersArray, function(idx, elem){

                        var link = "";
                        if(elem.deleted == null){
                            link = link.concat('<?= $this->Html->link(__('Deactivate'), ['action' => 'deactivate', -1], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', -2)]) ?> ');
                        } else {
                            link = link.concat('<?= $this->Html->link(__('Reactivate'), ['action' => 'reactivate', -1], ['confirm' => __('Are you sure you want to reactivate {0}?', -2)]) ?> ');
                            if(elem.loan_count == 0){
                                link = link.concat('<br/><?= $this->Html->link(__('Delete'), ['action' => 'delete', -1], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', -2)]) ?> ');
                            }
                        }
                        link = link.replace(/-1/g, elem.id);
                        link = link.replace(/-2/g, elem.email);

                        table.append(`
                            <tr id='user_` + elem.id + `' onclick='userSelected("` + elem.id + `")'>
                                <td><img id='user_` + elem.id + `_img' src='data:image/png;base64,` + elem.image + `' alt='` + elem.email + `' width=100/></td>
                                <td><a id='user_` + elem.id + `_email' href='/users/` + elem.id + `'>` + elem.email + `</a></td>
                                <td id='user_` + elem.id + `_admin_status'>` + elem.admin_status + `</td>
                                <td class='actions'>
                                    ` + link + `
                                </td>
                            </tr>
                        `);
                    });
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed to fetch users");
                    console.log(jqXHR.responseText);
                }
        });
    };

    function userSelected(id){
        let old_id = $('#user-id').val();
        $('#user_' + old_id).removeClass('selected');
        
        $('#user_' + id).addClass('selected');
        $('#user_selected').text($('#user_' + id + '_email').text());
        $('#user-id').val(id);
    }

    function sort_setter( type, sort_field ){
        var oldHtmlFieldId = '#' + type + '_' + sort[type].field + '_sort';
        var newHtmlFieldId = '#' + type + '_' + sort_field + '_sort';
        
        $(oldHtmlFieldId).removeClass('asc');
        $(oldHtmlFieldId).removeClass('desc');
        $(newHtmlFieldId).removeClass('asc');
        $(newHtmlFieldId).removeClass('desc');

        sort[type].dir = sort[type].field != sort_field ? "asc" : sort[type].dir == "asc" ? "desc" : "asc";
        sort[type].field = sort_field;

        $(newHtmlFieldId).addClass(sort[type].dir);
    }

    $('document').ready(function(){

        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            onSelect: function(date) {
                $('#preset-dates').val('custom');
                $('#start-time').datepicker('option', 'maxDate', $('#end-time').val());
                $('#end-time').datepicker('option', 'minDate', $('#start-time').val());
            }
        }).attr("autocomplete", "off");

         $('#search_user').keyup(function(){
            searchUsers( $(this).val() );
         });

         $('#user_email_sort').click( function(e) {
            sort_setter('user', 'email');
            $('#search').keyup();
         });
         $('#user_admin_status_sort').click( function(e) {
            sort_setter('user', 'admin_status');
            $('#search').keyup();
         });

         $('#item_type').on('change', function(){
            itemDict[$('#item_type').children("option:selected").val()]();
         });
        
         $('#search_user').keyup();
    });
</script>