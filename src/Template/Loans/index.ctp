<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan[]|\Cake\Collection\CollectionInterface $loans
 */
echo $this->Html->css('jquery.datetimepicker.min.css');
echo $this->Html->script('jquery.datetimepicker.full.js', array('inline' => false));
?>

<div class="loans index large-12 medium-11 columns content">
    
    <div class="left">
        <h3><?= __('Loans') ?></h3>
    </div>

    <div style="clear: both;"></div>

    <div class="search-container">
        <a href="/loans/add">
            <img class="plus" src="/img/plus.png" alt="Plus">
        </a>

        <div class="search-bar">
            <label for="search"><?= __('Search') ?></label>
            <input type="text" name="search" id="search">
        </div>
    </div>

    <br>
    <br>
    <br>

    <a href="#" onclick="$('#hid').toggle()"><?= __("Filters")?></a>
    <div id="hid" hidden>
        <form>
            <input type="checkbox" id="field_items" checked><?=__('Search by Items') ?>
            <input type="checkbox" id="field_users"><?=__('Search by Users') ?><br>

            <label for="item_type"><?= __('Item type') ?></label>
            <select id="item_type">
                <option value="all"><?= __('All') ?></option>
                <option value="mentors"><?= __('Mentors') ?></option>
                <option value="rooms"><?= __('Rooms') ?></option>
                <option value="licences"><?= __('Licences') ?></option>
                <option value="equipments"><?= __('Equipments') ?></option>
            </select>

            <label for="start_time"><?= __('Start time') ?></label>
            <input id='start_time' type="text" class="datepicker">

            <label for="end_time"><?= __('End time') ?></label>
            <input id='end_time' type="text" class="datepicker">
        </form>
    </div>
    <div class="tab">
        <button id='current_button' class="tablinks active" onclick="show_table('current')"><?= __("Current") ?></button>
        <button id='returned_button' class="tablinks" onclick="show_table('returned')"><?= __("Returned") ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead id="header_current">
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='item_sort' class='asc'><?= __("Item") ?></a></th>
                    <th scope="col" style="width:30%;"><a id='description_sort'><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Labels") ?></th>
                    <th scope="col"><a id='user_sort'><?= __("User") ?></a></th>
                    <th scope="col"><a id='start_time_sort'><?= __("Start time") ?></a></th>
                    <th scope="col"><a id='end_time_sort'><?= __("End time") ?></a></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <thead id="header_returned" hidden>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='item_sort' class='asc'><?= __("Item") ?></a></th>
                    <th scope="col" style="width:30%;"><a id='description_sort'><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Labels") ?></th>
                    <th scope="col"><a id='user_sort'><?= __("User") ?></a></th>
                    <th scope="col"><a id='start_time_sort'><?= __("Start time") ?></a></th>
                    <th scope="col"><a id='end_time_sort'><?= __("End time") ?></a></th>
                    <th scope="col"><a id='returned_sort'><?= __('Returned') ?></a></th>
                </tr>
            </thead>
            <tbody id="body_current">
            </tbody>
            <tbody id="body_returned" hidden>
            </tbody>
        </table>
    </div>
</div>

<script>
    var sort_field = "item";
    var sort_dir = "asc";

    var current_table = "current";

    function searchLoans( keyword ){
        var filters = {
            search_items: $('#field_items').is(':checked'),
            search_users: $('#field_users').is(':checked'),
            item_type: $('#item_type').children("option:selected").val(),
            start_time: $('#start_time').val(),
            end_time: $('#end_time').val()
        };

        $.ajax({
                method: 'get',
                url : "/loans/search.json",
                data: {keyword:keyword, sort_field:sort_field, sort_dir:sort_dir, filters: filters},
                success: function( response ){

                    for(var i=0; i<2; i++){
                        var body_name = "";
                        var array_name = "";
                        if(i == 0){
                            body_name = "body_current";
                            array_name = "loans";
                        } else if(i == 1){
                            body_name = "body_returned";
                            array_name = "returnedLoans";
                        }
                        var body = $("#" + body_name);
                        body.empty();

                        loansArray = response[array_name];

                        $.each(loansArray, function(idx, elem){

                            var labels_list = "";
                            var three_labels = elem.item.labels.slice(0,3);
                            if (elem.item.labels.length > 3) {
                                labels_list = three_labels.join("; ") + "...";
                            } else {
                                labels_list = three_labels.join("; ");
                            }

                            var link = "";
                            if(elem.returned == null){
                                link = link.concat('<?= $this->Html->link(__('Return'), ['action' => 'return', -1]) ?> ');
                            }
                            link = link.replace(/-1/g, elem.id);

                            let dateOptions = {hour12: false, year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit"};

                            if(body_name == "body_current"){
                                body.append(`
                                    <tr` + (new Date(elem.end_time) < new Date() && elem.returned == null ? " class='late'" : "") + `>
                                        <td><img src='data:image/png;base64,` + elem.item.image + `' width=100/></td>
                                        <td>` + elem.item.identifier + `</td>
                                        <td>` + elem.item.description + `</td>
                                        <td>` + labels_list + `</td>
                                        <td>` + elem.user.identifier + `</td>
                                        <td>` + new Date(elem.start_time).toLocaleString([], dateOptions).replace(/\//g, '-') + `</td>
                                        <td>` + new Date(elem.end_time).toLocaleString([], dateOptions).replace(/\//g, '-') + `</td>
                                        <td class='actions'>
                                            ` + link + `
                                        </td>
                                    </tr>
                                `);
                            } else if (body_name == "body_returned"){
                                body.append(`
                                    <tr` + (new Date(elem.end_time) < new Date() && elem.returned == null ? " class='late'" : "") + `>
                                        <td><img src='data:image/png;base64,` + elem.item.image + `' width=100/></td>
                                        <td>` + elem.item.identifier + `</td>
                                        <td>` + elem.item.description + `</td>
                                        <td>` + labels_list + `</td>
                                        <td>` + elem.user.identifier + `</td>
                                        <td>` + new Date(elem.start_time).toLocaleString([], dateOptions).replace(/\//g, '-') + `</td>
                                        <td>` + new Date(elem.end_time).toLocaleString([], dateOptions).replace(/\//g, '-') + `</td>
                                        <td>` + new Date(elem.returned).toLocaleString([], dateOptions).replace(/\//g, '-') + `</td>
                                    </tr>
                                `);
                            }
                        });
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed to fetch loans");
                    console.log(jqXHR.responseText);
                }
        });
    };

    function show_table(table_name){
        $('#body_' + current_table).hide();
        $('#header_' + current_table).hide();
        $('#' + current_table + '_button').removeClass('active');
        current_table = table_name;
        $('#body_' + current_table).show();
        $('#header_' + current_table).show();
        $('#' + current_table + '_button').addClass('active');
    }

    function sort_setter( sort_field_param ){
        var oldHtmlFieldId = '#' + sort_field +'_sort';
        var newHtmlFieldId = '#' + sort_field_param +'_sort';
        
        $(oldHtmlFieldId).removeClass('asc');
        $(oldHtmlFieldId).removeClass('desc');
        $(newHtmlFieldId).removeClass('asc');
        $(newHtmlFieldId).removeClass('desc');

        sort_dir = sort_field != sort_field_param ? "asc" : sort_dir == "asc" ? "desc" : "asc";
        sort_field = sort_field_param;

        $(newHtmlFieldId).addClass(sort_dir);
    }

    $('document').ready(function(){

        let dateTimeBoundarySet = function(datePicker, date, htmlObject){
            let pickerId = htmlObject[0].id;
            let start_time_date = null;
            let end_time_date = null;
            
            let reg_start_time_date = /(\d{4}-\d{2}-\d{2})/.exec($('#start_time').val());
            if(reg_start_time_date != null)
                start_time_date = reg_start_time_date[0];
            let reg_end_time_date = /(\d{4}-\d{2}-\d{2})/.exec($('#end_time').val());
            if(reg_end_time_date != null)
                end_time_date = reg_end_time_date[0];

            if(pickerId == "start_time"){
                if(end_time_date != null)
                    datePicker.setOptions({maxDate: end_time_date});
                else
                    datePicker.setOptions({maxDate: false});
            } else if(pickerId == "end_time"){
                if(start_time_date != null)
                    datePicker.setOptions({minDate: start_time_date});
                else
                    datePicker.setOptions({minDate: false});
            }
        }

        let showDateTime = function(datePicker, htmlObject){
            dateTimeBoundarySet(this, datePicker, htmlObject);
        }

        let changeDateTime = function(datePicker, htmlObject){
            dateTimeBoundarySet(this, datePicker, htmlObject);
            $('#preset-dates').val('custom');
            $('#item_search').keyup();
        }

        $(".datepicker").datetimepicker({
            format: 'Y-m-d H:i',
            onShow: showDateTime,
            onChangeDateTime: changeDateTime
        });

         $('#search').keyup(function(){
            var searchkey = $(this).val();
            searchLoans(searchkey);
         });

         $('#item_type').change(function(){
            $('#search').keyup();
         });

         $('#item_sort').click( function(e) {
            sort_setter('item');
            $('#search').keyup();
         });
         $('#description_sort').click( function(e) {
            sort_setter('description');
            $('#search').keyup();
         });
         $('#user_sort').click( function(e) {
            sort_setter('user');
            $('#search').keyup();
         });
         $('#start_time_sort').click( function(e) {
            sort_setter('start_time');
            $('#search').keyup();
         });
         $('#end_time_sort').click( function(e) {
            sort_setter('end_time');
            $('#search').keyup();
         });
         $('#returned_sort').click( function(e) {
            sort_setter('returned');
            $('#search').keyup();
         });

         $('#hid input').click( function(e) {
            $('#search').keyup();
         });

         $('#search').keyup();
    });
</script>
