<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan $loan
 */
echo $this->Html->css('jquery.datetimepicker.min.css');
echo $this->Html->script('jquery.datetimepicker.full.js', array('inline' => false));
echo $this->Html->script('moment-with-locales.js', array('inline' => false));
?>

<div class="loans form large-12 medium-11 columns content">
    <?= $this->Form->create($loan, ['id' => 'loan_form']) ?>
    <fieldset>
        <legend><?= __('Add Loan') ?></legend>

        <div class='right half-width'>
            
        </div>

        <div class='left half-width' style='height:700px;'>
            <input type='hidden' id='user-id' name='user_id' required='required'/>
            <label style='display:inline;' for="user_search"><?= __('Selected User: ') ?><b><span id='user_selected'></span></b></label><br>
            <input type="text" placeholder="<?=__('Search')?>" name="user_search" id="user_search">
            <div style="overflow:auto; height: 80%;">
                <table cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"><a id='user_email_sort' class='asc'><?= __("Email Adress") ?></a></th>
                            <th scope="col"><a id='user_admin_status_sort'><?= __("Admin Status") ?></a></th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                    </tbody>
                </table>
            </div>
        </div>

        <div class='right half-width' style='height:700px;'>

            <div class='left half-width'>
                <?= $this->Form->control('start_time', ['type' => 'text', 'class' => 'datepicker']); ?>
            </div>
            <div class='right half-width'>
                <?= $this->Form->control('end_time', ['type' => 'text', 'class' => 'datepicker']); ?>
            </div>
            <div style="clear:both;"></div>

            <label for="item_type"><?= __('Item type') ?></label>
            <?= $this->Form->select('item_type', ['mentors' => 'Mentors', 'rooms' => 'Rooms', 'licences' => 'Licences', 'equipments' => 'Equipments'], ['id' => 'item_type']); ?>
            
            <input type='hidden' id='item-id' name='item_id' required='required'/>
            <label style='display:inline;' for="item_search"><?= __('Selected Item: ') ?></label><b><span id='item_selected'></span></b><br>
            <input type="text" placeholder="<?=__('Search')?>" name="item_search" id="item_search">
            
            <a onclick="$('#item_filters_div').toggle();"><?= __("Filters")?></a>
            <div id="item_filters_div" hidden>
                <input type="checkbox" id="item_available" checked><?=__('Search Available') ?>
                <input type="checkbox" id="item_check" checked><?=__('Search by Items') ?><br>
                <input type="checkbox" id="item_unavailable"><?=__('Search Unavailable') ?>
                <input type="checkbox" id="item_label_check"><?=__('Search by Labels') ?><br>
            </div>

            <div style="overflow-x:auto; height:56%">
                <table id='item-table' cellpadding="0" cellspacing="0">
                    <thead id='item-table-head'>
                    </thead>
                    <tbody id='item-table-body'>
                    </tbody>
                </table>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->end() ?>
    <button class="right" onclick="submitForm()"><?=__('Save')?></button>
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

    //Users
    function setBodyUsers(){
        
        let keyword = $('#user_search').val();

        $.ajax({
                method: 'get',
                url : "/users/search.json",
                data: {keyword:keyword, sort_field: sort.user.field, sort_dir: sort.user.dir},
                success: function( response ){

                    var table_body_name = "user-table-body";
                    var array_name = "users";

                    var table_body = $("#" + table_body_name);
                    table_body.empty();

                    usersArray = response[array_name];
                    $.each(usersArray, function(idx, elem){

                        var link = "";

                        table_body.append(`
                            <tr id='user_` + elem.id + `' ` + ($('#user-id').val() == elem.id.toString() ? 'class="selected"' : '') + ` onclick='rowSelected("user", "` + elem.id + `")'>
                                <td><img id='user_` + elem.id + `_img' src='data:image/png;base64,` + elem.image + `' alt='` + elem.email + `' width=100/></td>
                                <td><a class='inline' id='user_` + elem.id + `_identifier' href='/users/` + elem.id + `'>` + elem.email + `</a></td>
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

    //Items
    let itemDict = {
        mentors: {
            labels: 'skills',
            create: function(){
                setHeadersMentors();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','email');
                setBody('mentors');
            },
            search: function(){
                setBody('mentors');
            }
        },
        rooms: {
            labels: 'services',
            create: function(){
                setHeadersRooms();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','name');
                setBody('rooms');
            },
            search: function(){
                setBody('rooms');
            }
        },
        licences: {
            labels: 'products',
            create: function(){
                setHeadersLicences();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','name');
                setBody('licences');
            },
            search: function(){
                setBody('licences');
            }
        },
        equipments: {
            labels: 'categories',
            create: function(){
                setHeadersEquipments();
                sort['item'].field = '';
                sort['item'].dir = '';
                sortSetter('item','name');
                setBody('equipments');
            },
            search: function(){
                setBody('equipments');
            }
        }
    };

    function setBody(itemType){
        let keyword = $('#item_search').val();

        if($('#start-time').val() && $('#end-time').val()){
            var filters = {};
            filters['search_available'] = $('#item_available').is(':checked');
            filters['search_unavailable'] = $('#item_unavailable').is(':checked');
            filters['search_' + itemType] = $('#item_check').is(':checked');
            filters['search_' + itemDict[itemType].labels] = $('#item_label_check').is(':checked');
            filters['start_time_available'] = $('#start-time').val();
            filters['end_time_available'] = $('#end-time').val();

            $.ajax({
                method: 'get',
                url : "/" + itemType + "/search.json",
                data: {keyword:keyword, sort_field:sort.item.field, sort_dir:sort.item.dir, filters: filters},
                success: function( response ){
                    itemArray = response[itemType];
                    eval('setBody' + itemType.charAt(0).toUpperCase() + itemType.slice(1) + '(itemArray);');
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed to fetch " + itemType);
                    console.log(jqXHR.responseText);
                }
            });
        }
    }

    function setHeadersMentors(){
        $('#item-table-head').empty();
        $('#item-table-head').append(`
            <tr>
                <th scope="col"></th>
                <th scope="col"><a id="item_email_sort" onclick="sortSetter('item', 'email'); itemDict.mentors.search();"><?= __("Email") ?></a></th>
                <th scope="col"><a id="item_first_name_sort" onclick="sortSetter('item', 'first_name'); itemDict.mentors.search();"><?= __("First name") ?></a></th>
                <th scope="col"><a id="item_last_name_sort" onclick="sortSetter('item', 'last_name'); itemDict.mentors.search();"><?= __("Last name") ?></a></th>
                <th scope="col"><a id="item_description_sort" onclick="sortSetter('item', 'description'); itemDict.mentors.search();"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Skills list") ?></th>
                <th scope="col"><?= __("Available") ?></th>
            </tr>
        `);
    }

    function setBodyMentors(itemArray){

        var table_body = $("#item-table-body");
        table_body.empty();

        $.each(itemArray, function(idx, elem){

            var labels_list = "";
            var three_labels = elem.skills_list.slice(0,3);
            if (elem.skills_list.length > 3) {
                labels_list = three_labels.join("; ") + "...";
            } else {
                labels_list = three_labels.join("; ");
            }

            var imgTag = '';
            var imgAlt = '';
            if (elem.available_between) {
                imgTag = 'good.png';
                imgAlt = 'Available';
            } else {
                imgTag = 'bad.png';
                imgAlt = 'Not Available';
            }

            table_body.append(`
                <tr id='item_` + elem.id + `' onclick='rowSelected("item", "` + elem.id + `")'>
                    <td><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.first_name + ` ` + elem.last_name + `' width=100/></td>
                    <td colspan="3">
                        <a class='inline' id='item_` + elem.id + `_identifier' href='/mentors/` + elem.id + `'>` + elem.email + `</a><br>
                        ` + elem.first_name + ` ` + elem.last_name + `
                    </td>
                    <td>` + elem.description + `</td>
                    <td>` + labels_list + `</td>
                    <td><img id='item_` + elem.id + `_available' data-available='`+ elem.available_between + `' src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></td>
                </tr>
            `);

            if($('#item-id').val() == elem.id.toString()){
                rowSelected("item", elem.id.toString());
            }
        });
    }

    function setHeadersRooms(){
        $('#item-table-head').empty();
        $('#item-table-head').append(`
            <tr>
                <th scope="col"></th>
                <th scope="col"><a id="item_name_sort" onclick="sortSetter('item', 'name'); itemDict.rooms.search();"><?= __("Name") ?></a></th>
                <th scope="col"><a id="item_description_sort" onclick="sortSetter('item', 'description'); itemDict.rooms.search();"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Services list") ?></th>
                <th scope="col"><?= __("Available") ?></th>
            </tr>
        `);
    }

    function setBodyRooms(itemArray){
        var table_body = $("#item-table-body");
        table_body.empty();

        $.each(itemArray, function(idx, elem){

            var labels_list = "";
            var three_labels = elem.services_list.slice(0,3);
            if (elem.services_list.length > 3) {
                labels_list = three_labels.join("; ") + "...";
            } else {
                labels_list = three_labels.join("; ");
            }

            var imgTag = '';
            var imgAlt = '';
            if (elem.available_between) {
                imgTag = 'good.png';
                imgAlt = 'Available';
            } else {
                imgTag = 'bad.png';
                imgAlt = 'Not Available';
            }

            table_body.append(`
                <tr id='item_` + elem.id + `' ` + ($('#item-id').val() == elem.id.toString() ? 'class="selected"' : '') + ` onclick='rowSelected("item", "` + elem.id + `")'>
                    <td><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.name + `' width=100/></td>
                    <td><a class='inline' id='item_` + elem.id + `_identifier' href='/rooms/` + elem.id + `'>` + elem.name + `</a></td>
                    <td>` + elem.description + `</td>
                    <td>` + labels_list + `</td>
                    <td><img id='item_` + elem.id + `_available' data-available='`+ elem.available_between + `' src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></td>
                </tr>
            `);

            if($('#item-id').val() == elem.id.toString()){
                rowSelected("item", elem.id.toString());
            }
        });
    }

    function setHeadersLicences(){
        $('#item-table-head').empty();
        $('#item-table-head').append(`
            <tr>
                <th scope="col"></th>
                <th scope="col"><a id="item_name_sort" onclick="sortSetter('item', 'name'); itemDict.licences.search();"><?= __("Name") ?></a></th>
                <th scope="col"><a id="item_description_sort" onclick="sortSetter('item', 'description'); itemDict.licences.search();"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Products list") ?></th>
                <th scope="col"><?= __("Status") ?></th>
                <th scope="col"><?= __("Available") ?></th>
            </tr>
        `);
    }

    function setBodyLicences(itemArray){
        var table_body = $("#item-table-body");
        table_body.empty();

        $.each(itemArray, function(idx, elem){

            var labels_list = "";
            var three_labels = elem.products_list.slice(0,3);
            if (elem.products_list.length > 3) {
                labels_list = three_labels.join("; ") + "...";
            } else {
                labels_list = three_labels.join("; ");
            }

            var imgTag = '';
            var imgAlt = '';
            if (elem.available_between) {
                imgTag = 'good.png';
                imgAlt = 'Available';
            } else {
                imgTag = 'bad.png';
                imgAlt = 'Not Available';
            }

            table_body.append(`
                <tr id='item_` + elem.id + `' ` + ($('#item-id').val() == elem.id.toString() ? 'class="selected"' : '') + ` onclick='rowSelected("item", "` + elem.id + `")'>
                    <td><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.name + `' width=100/></td>
                    <td><a class='inline' id='item_` + elem.id + `_identifier' href='/licences/` + elem.id + `'>` + elem.name + `</a></td>
                    <td>` + elem.description + `</td>
                    <td>` + labels_list + `</td>
                    <td>` + elem.status + `</td>
                    <td><img id='item_` + elem.id + `_available' data-available='`+ elem.available_between + `' src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></td>
                </tr>
            `);

            if($('#item-id').val() == elem.id.toString()){
                rowSelected("item", elem.id.toString());
            }
        });
    }

    function setHeadersEquipments(){
        $('#item-table-head').empty();
        $('#item-table-head').append(`
            <tr>
                <th scope="col"></th>
                <th scope="col"><a id="item_name_sort" onclick="sortSetter('item', 'name'); itemDict.equipments.search();"><?= __("Name") ?></a></th>
                <th scope="col"><a id="item_description_sort" onclick="sortSetter('item', 'description'); itemDict.equipments.search();"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Categories list") ?></th>
                <th scope="col"><?= __("Available") ?></th>
            </tr>
        `);
    }

    function setBodyEquipments(itemArray){
        var table_body = $("#item-table-body");
        table_body.empty();

        $.each(itemArray, function(idx, elem){

            var labels_list = "";
            var three_labels = elem.categories_list.slice(0,3);
            if (elem.categories_list.length > 3) {
                labels_list = three_labels.join("; ") + "...";
            } else {
                labels_list = three_labels.join("; ");
            }

            var imgTag = '';
            var imgAlt = '';
            if (elem.available_between) {
                imgTag = 'good.png';
                imgAlt = 'Available';
            } else {
                imgTag = 'bad.png';
                imgAlt = 'Not Available';
            }

            table_body.append(`
                <tr id='item_` + elem.id + `' ` + ($('#item-id').val() == elem.id.toString() ? 'class="selected"' : '') + ` onclick='rowSelected("item", "` + elem.id + `")'>
                    <td><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.name + `' width=100/></td>
                    <td><a class='inline' id='item_` + elem.id + `_identifier' href='/rooms/` + elem.id + `'>` + elem.name + `</a></td>
                    <td>` + elem.description + `</td>
                    <td>` + labels_list + `</td>
                    <td><img id='item_` + elem.id + `_available' data-available='`+ elem.available_between + `' src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></td>
                </tr>
            `);

            if($('#item-id').val() == elem.id.toString()){
                rowSelected("item", elem.id.toString());
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
        var canSelect = true;
        if(type == 'item'){
            if($('#' + type + '_' + id + '_available').attr('data-available') == 'false'){
                alert("<?=__('You cannot select this item, it is not available during the time period.')?>");
                canSelect = false;
            }
        }

        if(id == null || (id != null && canSelect)){
            let old_id = $('#' + type + '-id').val();
            $('#' + type + '_' + old_id).removeClass('selected');
        }

        if(id != null && canSelect) {
            $('#' + type + '_' + id).addClass('selected');
            $('#' + type + '_selected').text(id != null ? $('#' + type + '_' + id + '_identifier').text() : '');
            $('#' + type + '-id').val(id != null ? id : '');
        } else if (id == null) {
            $('#' + type + '_selected').text('');
            $('#' + type + '-id').val('');
        }
    }

    function submitForm()
    {
        var startTime = new Date($('#start-time').val());
        var endTime = new Date($('#end-time').val());
        $('#start-time').val(startTime.toISOString());
        $('#end-time').val(endTime.toISOString());
        $('#loan_form').submit();
    }

    $('document').ready(function(){

        let dateTimeBoundarySet = function(datePicker, date, htmlObject){
            let pickerId = htmlObject[0].id;
            let start_time_date = null;
            let end_time_date = null;
            
            let reg_start_time_date = /(\d{4}-\d{2}-\d{2})/.exec($('#start-time').val());
            if(reg_start_time_date != null)
                start_time_date = reg_start_time_date[0];
            let reg_end_time_date = /(\d{4}-\d{2}-\d{2})/.exec($('#end-time').val());
            if(reg_end_time_date != null)
                end_time_date = reg_end_time_date[0];

            if(pickerId == "start-time"){
                if(end_time_date != null)
                    datePicker.setOptions({maxDate: end_time_date});
                else
                    datePicker.setOptions({maxDate: false});
            } else if(pickerId == "end-time"){
                datePicker.setOptions({maxDate: false});
                if(start_time_date != null)
                    datePicker.setOptions({minDate: start_time_date});
                else
                    datePicker.setOptions({minDate: new Date()});
            }
        }

        let showDateTime = function(datePicker, htmlObject){
            dateTimeBoundarySet(this, datePicker, htmlObject);
        }

        let changeDateTime = function(datePicker, htmlObject){
            dateTimeBoundarySet(this, datePicker, htmlObject);
            rowSelected("item", null);
            $('#item_search').keyup();
        }

        $(".datepicker").datetimepicker({
            format: 'Y-m-d H:i',
            minDate: new Date(),
            onShow: showDateTime,
            onChangeDateTime: changeDateTime
        });
        
        //Users
        $('#user_search').keyup(function(){
            setBodyUsers();
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

        rowSelected('user', <?=isset($loan->user_id) ? "'" . $loan->user_id . "'" : null ?>);

        //Items
        $('#item_type').on('change', function(){
            itemDict[$('#item_type').children("option:selected").val()].create();
            rowSelected('item', null);
        });

        $('#item_search').keyup(function(){
            itemDict[$('#item_type').children("option:selected").val()].search();
        });

        $('#item_check').click( function(e) {
            $('#item_search').keyup();
        });
        $('#item_label_check').click( function(e) {
            $('#item_search').keyup();
        });

        $('#item_available').click( function(e) {
            $('#item_search').keyup();
        });
        $('#item_unavailable').click( function(e) {
            $('#item_search').keyup();
        });

        $('#item_type').change();

        rowSelected('item', <?=isset($loan->item_id) ? "'" . $loan->item_id . "'" : null ?>);

        if($("#start-time").val() != '') 
            $("#start-time").val(moment($("#start-time").val()).format("YYYY-MM-DD HH:mm"));
        if($("#end-time").val() != '')
            $("#end-time").val(moment($("#end-time").val()).format("YYYY-MM-DD HH:mm"));
    });
</script>