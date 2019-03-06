<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan $loan
 */
?>
<?= $this->Html->css('jquery.datetimepicker.min.css') ?>
<?= $this->Html->script('jquery.datetimepicker.full.js', array('inline' => false)); ?>

<div class="loans form large-12 medium-11 columns content">
    <?= $this->Form->create($loan) ?>
    <fieldset>
        <legend><?= __('Add Loan') ?></legend>
        
        <input type='hidden' id='user-id' name='user_id' required='required'/>
        <label for="user_search"><?= __('User:') ?></label>
        <span id='user_selected'></span>
        <input type="text" name="user_search" id="user_search">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='user_email_sort' class='asc'><?= __("Email Adress") ?></a></th>
                    <th scope="col"><a id='user_admin_status_sort'><?= __("Admin Status") ?></a></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody id="user-table-body">
            </tbody>
        </table>

        <label for="item_type"><?= __('Item type') ?></label>
        <?= $this->Form->select('item_type', ['mentors' => 'Mentors', 'rooms' => 'Rooms', 'licences' => 'Licences', 'equipments' => 'Equipments'], ['id' => 'item_type']); ?>
        
        <input type='hidden' id='item-id' name='item_id' required='required'/>
        <label for="item_search"><?= __('Item:') ?></label>
        <span id='item_selected'></span>
        <input type="text" name="item_search" id="item_search">
        
        <a onclick="$('#item_filters_div').toggle();"><?= __("Filters")?></a>
        <div id="item_filters_div" hidden>

        </div>

        <table id='item-table' cellpadding="0" cellspacing="0">
            <thead id='item-table-head'>
            </thead>
            <tbody id='item-table-body'>
            </tbody>
        </table>

        <?= $this->Form->control('start_time', ['type' => 'text', 'class' => 'datetpicker']); ?>
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
        mentors: {
            create: function(){
                setHeadersMentors();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','email');
                setBodyMentors();
            },
            search: function(){
                setBodyMentors();
            }
        },
        rooms: {
            create: function(){
                setHeadersRooms();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','email');
                setBodyRooms();
            },
            search: function(){
                setBodyRooms();
            }
        },
        licences: {
            create: function(){
                setHeadersLicences();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','email');
                setBodyLicences();
            },
            search: function(){
                setBodyLicences();
            }
        },
        equipments: {
            create: function(){
                setHeadersEquipments();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','email');
                setBodyEquipments();
            },
            search: function(){
                setBodyEquipments();
            }
        }
    };

    function setBodyUsers( keyword ){
        var data = keyword;

        $.ajax({
                method: 'get',
                url : "/users/search.json",
                data: {keyword:data, sort_field: sort.user.field, sort_dir: sort.user.dir},
                success: function( response ){

                    var table_body_name = "user-table-body";
                    var array_name = "users";

                    var table_body = $("#" + table_body_name);
                    table_body.empty();

                    usersArray = response[array_name];
                    $.each(usersArray, function(idx, elem){

                        var link = "";

                        table_body.append(`
                            <tr id='user_` + elem.id + `' onclick='rowSelected("user", "` + elem.id + `")'>
                                <td><img id='user_` + elem.id + `_img' src='data:image/png;base64,` + elem.image + `' alt='` + elem.email + `' width=100/></td>
                                <td><a id='user_` + elem.id + `_identifier' href='/users/` + elem.id + `'>` + elem.email + `</a></td>
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
    }

    function setHeadersMentors(){
        $('#item_filters_div').empty();
        $('#item_filters_div').append(`
            <input type="checkbox" id="item_available_check" checked><?=__('Search Available') ?>
            <input type="checkbox" id="item_check" checked><?=__('Search by Mentors') ?><br>
            <input type="checkbox" id="item_unavailable_check" checked><?=__('Search Unavailable') ?>
            <input type="checkbox" id="item_label_check"><?=__('Search by Skills') ?><br>
        `);
        
        $('#item_available_check').click( function(e) {
            $('#item_search').keyup();
         });
         $('#item_unavailable_check').click( function(e) {
            $('#item_search').keyup();
         });
         $('#item_check').click( function(e) {
            $('#item_search').keyup();
         });
         $('#item_label_check').click( function(e) {
            $('#item_search').keyup();
         });

        $('#item-table-head').empty();
        $('#item-table-head').append(`
            <tr>
                <th scope="col"></th>
                <th scope="col"><a id="item_email_sort" onclick="sortSetter('item', 'email'); setBodyMentors();"><?= __("Email") ?></a></th>
                <th scope="col"><a id="item_first_name_sort" onclick="sortSetter('item', 'first_name'); setBodyMentors();"><?= __("First name") ?></a></th>
                <th scope="col"><a id="item_last_name_sort" onclick="sortSetter('item', 'last_name'); setBodyMentors();"><?= __("Last name") ?></a></th>
                <th scope="col"><a id="item_description_sort" onclick="sortSetter('item', 'description'); setBodyMentors();"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Skills list") ?></th>
                <th scope="col"><?= __("Actions") ?></th>
            </tr>
        `);
    }

    

    function setBodyMentors(){

        let keyword = $('#item_search').val();

        var filters = {
            search_available: $('#item_available_check').is(':checked'),
            search_unavailable: $('#item_unavailable_check').is(':checked'),
            search_mentors: $('#item_check').is(':checked'),
            search_skills: $('#item_label_check').is(':checked')
        };

        $.ajax({
                method: 'get',
                url : "/mentors/search.json",
                data: {keyword:keyword, sort_field:sort.item.field, sort_dir:sort.item.dir, filters: filters},
                success: function( response ){

                    var table_body_name = "item-table-body";
                    var array_name = "mentors";

                    var table_body = $("#" + table_body_name);
                    table_body.empty();

                    mentorsArray = response[array_name];
                    $.each(mentorsArray, function(idx, elem){

                        var skills_list = "";
                        var three_skills = elem.skills_list.slice(0,3);
                        if (elem.skills_list.length > 3) {
                            skills_list = three_skills.join("; ") + "...";
                        } else {
                            skills_list = three_skills.join("; ");
                        }

                        var imgTag = '';
                        var imgAlt = '';
                        if (elem.available) {
                            imgTag = 'good.png';
                            imgAlt = 'Available';
                        } else {
                            imgTag = 'bad.png';
                            imgAlt = 'Not Available';
                        }
                        var link = "";

                        table_body.append(`
                            <tr id='item_` + elem.id + `' onclick='rowSelected("item", "` + elem.id + `")'>
                                <td><a href='/mentors/` + elem.id + `'><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.first_name + ` ` + elem.last_name + `' width=100/></a></td>
                                <td><a id='item_` + elem.id + `_identifier' href='/mentors/` + elem.id + `'>` + elem.email + `</a></td>
                                <td>` + elem.first_name + `</td>
                                <td>` + elem.last_name + `</td>
                                <td>` + elem.description + `</td>
                                <td>` + skills_list + `</td>
                                <td><a href='/mentors/` + elem.id + `'><img src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></a></td>
                                <td class='actions'>
                                    ` + link + `
                                </td>
                            </tr>
                        `);
                    });
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed to fetch mentors");
                    console.log(jqXHR.responseText);
                }
        });
    }

    function sortSetter( type, sort_field ){
        let oldHtmlFieldId = type + '_' + sort[type].field + '_sort';
        let newHtmlFieldId = type + '_' + sort_field + '_sort';
        
        $('#' + oldHtmlFieldId).removeClass('asc');
        $('#' + oldHtmlFieldId).removeClass('desc');
        $('#' + newHtmlFieldId).removeClass('asc');
        $('#' + newHtmlFieldId).removeClass('desc');

        sort[type].dir = sort[type].field != sort_field ? "asc" : sort[type].dir == "asc" ? "desc" : "asc";
        sort[type].field = sort_field;

        $('#' + newHtmlFieldId).addClass(sort[type].dir);
    }

    function rowSelected(type, id){
        let old_id = $('#' + type + '-id').val();
        $('#' + type + '_' + old_id).removeClass('selected');
        
        $('#' + type + '_' + id).addClass('selected');
        $('#' + type + '_selected').text($('#' + type + '_' + id + '_identifier').text());
        
        $('#' + type + '-id').val(id);
    }

    $('document').ready(function(){

        $("#start-time").datetimepicker({
            format: 'Y-m-d H:00:00',
            minDate: new Date(),
            onShow: function(ct) {
                let myDate = null;
                let date = null;
                
                let regMyDate = /(\d{4}-\d{2}-\d{2})/.exec($('#start-time').val());
                if(regMyDate != null)
                    myDate = regMyDate[0];
                let regDate = /(\d{4}-\d{2}-\d{2})/.exec($('#end-time').val());
                if(regDate != null)
                    date = regDate[0];

                if(date != null)
                    this.setOptions({maxDate: date});
                else
                    this.setOptions({maxDate: false});
            },
            onChangeDateTime: function(current_time, picker) {
                let myDate = null;
                let date = null;
                let time = null;
                
                let regMyDate = /(\d{4}-\d{2}-\d{2})/.exec($('#start-time').val());
                if(regMyDate != null)
                    myDate = regMyDate[0];
                let regDate = /(\d{4}-\d{2}-\d{2})/.exec($('#end-time').val());
                if(regDate != null)
                    date = regDate[0];
                let regTime = /\d{1,2}:\d{2}:\d{2}/.exec($('#end-time').val());
                if(regTime != null)
                    time = regTime[0];

                if(time != null && date == myDate)
                    this.setOptions({maxTime: time});
                else
                    this.setOptions({maxTime: false});
            }
        });
        $("#end-time").datetimepicker({
            format: 'Y-m-d H:00:00',
            minDate: new Date(),
            onShow: function(ct) {
                let myDate = null;
                let date = null;
                
                let regMyDate = /(\d{4}-\d{2}-\d{2})/.exec($('#end-time').val());
                if(regMyDate != null)
                    myDate = regMyDate[0];
                let regDate = /(\d{4}-\d{2}-\d{2})/.exec($('#start-time').val());
                if(regDate != null)
                    date = regDate[0];

                if(date != null)
                    this.setOptions({minDate: date});
                else
                    this.setOptions({minDate: new Date()});
            },
            onChangeDateTime: function(current_time, picker) {
                let myDate = null;
                let date = null;
                let time = null;
                
                let regMyDate = /(\d{4}-\d{2}-\d{2})/.exec($('#end-time').val());
                if(regMyDate != null)
                    myDate = regMyDate[0];
                let regDate = /(\d{4}-\d{2}-\d{2})/.exec($('#start-time').val());
                if(regDate != null)
                    date = regDate[0];
                let regTime = /\d{1,2}:\d{2}:\d{2}/.exec($('#start-time').val());
                if(regTime != null)
                    time = regTime[0];

                if(time != null && date == myDate)
                    this.setOptions({minTime: time});
                else
                    this.setOptions({minTime: false});
            }
        });
        

        //Users
        $('#user_search').keyup(function(){
            setBodyUsers( $(this).val() );
        });

        $('#user_email_sort').click( function(e) {
            sortSetter('user', 'email');
            $('#user_search').keyup();
        });
        $('#user_admin_status_sort').click( function(e) {
            sortSetter('user', 'admin_status');
            $('#user_search').keyup();
        });
    
        $('#user_search').keyup();

        //Items
        $('#item_type').on('change', function(){
            itemDict[$('#item_type').children("option:selected").val()].create();
        });

        $('#item_search').keyup(function(){
            itemDict[$('#item_type').children("option:selected").val()].search();
        });

        $('#item_type').change();
    });
</script>