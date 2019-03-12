<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
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
        <button id='table_activated_button' class="tablinks active" onclick="show_table('table_activated')"><?= __("Current") ?></button>
        <button id='table_returned_button' class="tablinks" onclick="show_table('table_returned')"><?= __("Returned") ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='item_sort' class='asc'><?= __("Item") ?></a></th>
                    <th scope="col" style="width:30%;"><a id='description_sort'><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Labels") ?></th>
                    <th scope="col"><a id='start_time_sort'><?= __("Start time") ?></a></th>
                    <th scope="col"><a id='end_time_sort'><?= __("End time") ?></a></th>
                    <th scope="col"><a id='overtime_sort'><?= __("Overtime Fee") ?></a></th>
                </tr>
            </thead>
            <tbody id="table_activated">
            </tbody>
            <tbody id="table_returned" hidden>
            </tbody>
        </table>
    </div>
</div>

<script>
    var sort_field = "item";
    var sort_dir = "asc";

    var current_table = "table_activated";

    function searchLoans( keyword ){
        var filters = {
            search_items: $('#field_items').is(':checked'),
            search_labels: $('#field_labels').is(':checked'),
            search_users: $('#field_users').is(':checked'),
            item_type: $('#item_type').children("option:selected").val(),
            start_time: $('#start_time').val(),
            end_time: $('#end_time').val(),
        };

        $.ajax({
                method: 'get',
                url : "/loans/search.json",
                data: {keyword:keyword, sort_field:sort_field, sort_dir:sort_dir, filters: filters},
                success: function( response ){

                    for(var i=0; i<2; i++){
                        var table_name = "";
                        var array_name = "";
                        if(i == 0){
                            table_name = "table_activated";
                            array_name = "loans";
                        } else if(i == 1){
                            table_name = "table_returned";
                            array_name = "returnedLoans";
                        }
                        var table = $("#" + table_name);
                        table.empty();

                        loansArray = response[array_name];
                        id = "<?php echo ($this->request->getSession()->read('Auth.User.id')); ?>";

                        $.each(loansArray, function(idx, elem){
                        
                            if(elem.user['id'] == id){
                                
                                var labels_list = "";
                                var three_labels = elem.item.labels.slice(0,3);
                                if (elem.item.labels.length > 3) {
                                    labels_list = three_labels.join("; ") + "...";
                                } else {
                                    labels_list = three_labels.join("; ");
                                }

                                table.append(`
                                    <tr` + (new Date(elem.end_time) < new Date() && elem.returned == null ? " class='late'" : "") + `>
                                        <td><img src='data:image/png;base64,` + elem.item.image + `' width=100/></td>
                                        <td>` + elem.item.identifier + `</td>
                                        <td>` + elem.item.description + `</td>
                                        <td>` + labels_list + `</td>
                                        <td>` + moment(elem.start_time).format("YYYY-MM-DD HH:mm") + `</td>
                                        <td>` + moment(elem.end_time).format("YYYY-MM-DD HH:mm") + `</td>
                                        <td>` + parseFloat(elem.overtime_fee).toFixed(2) + "$" + `</td>
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
        $('#' + current_table).hide();
        $('#' + current_table + '_button').removeClass('active');
        current_table = table_name;
        $('#' + current_table).show();
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

        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
			changeYear: true,
            onSelect: function(dateText,inst) {
                $('#preset-dates').val('custom');
                $('#start_time').datepicker('option', 'maxDate', $('#end_time').val());
                $('#end_time').datepicker('option', 'minDate', $('#start_time').val());
                $('#search').keyup();
            }
        });

        $('#start_time').datepicker('option', 'maxDate', $('#end_time').val());
        $('#end_time').datepicker('option', 'minDate', $('#start_time').val());

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
         $('#overtime_sort').click( function(e) {
            sort_setter('end_time');
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

