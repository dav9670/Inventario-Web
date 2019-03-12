<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
echo $this->Html->css('jquery.datetimepicker.min.css');
echo $this->Html->script('jquery.datetimepicker.full.js', array('inline' => false));
echo $this->Html->script('moment-with-locales.js', array('inline' => false));
?>
<div class="loans index large-12 medium-11 columns content">
<h3><?= __($user->email) ?></h3>
<button type="button" id="passwordButton" class='right passwordButton' onClick='changePassword()' ><?=__('Change password')?></button>
    <div class="related">
        <h4><?= __('Related Loans') ?></h4>
    </div>

    <div style="clear: both;"></div>

    <div>
        <div>
            <label for="search"><?= __('Search') ?></label>
            <input type="text" name="search" id="search">
        </div>
    </div>

    <a href="#" onclick="$('#hid').toggle()"><?= __("Filters")?></a>
    <div id="hid" hidden>
        <form>
            <input type="checkbox" id="field_items" checked><?=__('Search by Items') ?>
            <input type="checkbox" id="field_labels"><?=__('Search by Labels') ?>
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
        <button id='returned_button' class="tablinks" onclick="show_table('returned')"><?= __("Returned Tab") ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead id="header_current">
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a class='asc item_sort' onclick="sort_reload('item');"><?= __("Item") ?></a></th>
                    <th scope="col" class="description-header"><a class='description_sort' onclick="sort_reload('description');"><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Labels") ?></th>
                    <th scope="col"><a class='user_sort' onclick="sort_reload('user');"><?= __("User") ?></a></th>
                    <th scope="col" class="date-header"><a class='start_time_sort' onclick="sort_reload('start_time');"><?= __("Start time") ?></a></th>
                    <th scope="col" class="date-header"><a class='end_time_sort' onclick="sort_reload('end_time');"><?= __("End time") ?></a></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <thead id="header_returned" hidden>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a class='asc item_sort' onclick="sort_reload('item');"><?= __("Item") ?></a></th>
                    <th scope="col" class="description-header"><a class='description_sort' onclick="sort_reload('description');"><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Labels") ?></th>
                    <th scope="col"><a class="user_sort" onclick="sort_reload('user');"><?= __("User") ?></a></th>
                    <th scope="col" class="date-header"><a class='start_time_sort' onclick="sort_reload('start_time');"><?= __("Start time") ?></a></th>
                    <th scope="col" class="date-header"><a class='end_time_sort' onclick="sort_reload('end_time');"><?= __("End time") ?></a></th>
                    <th scope="col" class="date-header"><a class='returned_sort' onclick="sort_reload('returned');"><?= __('Returned time') ?></a></th>
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
     var sort = {
        field: "item",
        dir: "asc"
    };

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
                data: {keyword:keyword, sort_field:sort.field, sort_dir:sort.dir, filters: filters},
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

                            if(body_name == "body_current"){
                                body.append(`
                                    <tr` + (new Date(elem.end_time) < new Date() && elem.returned == null ? " class='late'" : "") + `>
                                        <td><img src='data:image/png;base64,` + elem.item.image + `' width=100/></td>
                                        <td>` + elem.item.identifier + `</td>
                                        <td>` + elem.item.description + `</td>
                                        <td>` + labels_list + `</td>
                                        <td>` + elem.user.identifier + `</td>
                                        <td>` + moment(elem.start_time).format("YYYY-MM-DD HH:mm") + `</td>
                                        <td>` + moment(elem.end_time).format("YYYY-MM-DD HH:mm") + `</td>
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
                                        <td>` + moment(elem.start_time).format("YYYY-MM-DD HH:mm") + `</td>
                                        <td>` + moment(elem.end_time).format("YYYY-MM-DD HH:mm") + `</td>
                                        <td>` + moment(elem.returned).format("YYYY-MM-DD HH:mm") + `</td>
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
    
    function sort_setter( sort_field ){
        var oldHtmlFieldId = '.' + sort.field +'_sort';
        var newHtmlFieldId = '.' + sort_field +'_sort';
        
        $(oldHtmlFieldId).removeClass('asc');
        $(oldHtmlFieldId).removeClass('desc');
        $(newHtmlFieldId).removeClass('asc');
        $(newHtmlFieldId).removeClass('desc');

        sort.dir = sort.field != sort_field ? "asc" : sort.dir == "asc" ? "desc" : "asc";
        sort.field = sort_field;

        $(newHtmlFieldId).addClass(sort.dir);
    }

    function sort_reload(sort_field){
        sort_setter(sort_field);
        $('#search').keyup();
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
                datePicker.setOptions({maxDate: false});
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

         $('#hid input').click( function(e) {
            $('#search').keyup();
         });

         $('#search').keyup();
         
    });

    function changePassword(){
        window.location.href = "/users/changePassword";
    }
</script>

