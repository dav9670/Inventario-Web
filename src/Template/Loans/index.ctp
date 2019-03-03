<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan[]|\Cake\Collection\CollectionInterface $loans
 */
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
            <input type="checkbox" id="field_labels"><?=__('Search by Labels') ?>
            <input type="checkbox" id="field_users"><?=__('Search by Users') ?><br>

            <label for="date-from"><?= __('From') ?></label>
            <input id='date-from' type="text" class="datepicker">

            <label for="date-to"><?= __('To') ?></label>
            <input id='date-to' type="text" class="datepicker">
        </form>
    </div>
    <div class="tab">
        <button id='table_activated_button' class="tablinks active" onclick="show_table('table_activated')"><?= __("Activated") ?></button>
        <button id='table_returned_button' class="tablinks" onclick="show_table('table_returned')"><?= __("Returned") ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='item_sort' class='asc'><?= __("Item") ?></a></th>
                    <th scope="col"><a id='description_sort'><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Labels") ?></th>
                    <th scope="col"><a id='user_sort'><?= __("User") ?></a></th>
                    <th scope="col"><a id='start_date_sort'><?= __("Start date") ?></a></th>
                    <th scope="col"><a id='end_date_sort'><?= __("End date") ?></a></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody id="table_activated">
            </tbody>
            <tbody id="table_returned" hidden="hidden">
            </tbody>
        </table>
    </div>
</div>

<script>
    var sort_field = "item";
    var sort_dir = "asc";

    var current_table = "table_activated";

    function searchLoans( keyword ){
        var data = keyword;

        var filters = {
            search_loans: $('#field_items').is(':checked'),
            search_labels: $('#field_labels').is(':checked'),
            search_users: $('#field_users').is(':checked'),
            date_from: $('#date-from').val(),
            date_to: $('#date-to').val()
        };

        $.ajax({
                method: 'get',
                url : "/loans/search.json",
                data: {keyword:data, sort_field:sort_field, sort_dir:sort_dir, filters: filters},
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
                        $.each(loansArray, function(idx, elem){

                            var labels_list = "";
                            var three_labels = elem.labels_list.slice(0,3);
                            if (elem.labels_list.length > 3) {
                                labels_list = three_labels.join("; ") + "...";
                            } else {
                                labels_list = three_labels.join("; ");
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

                            var link = ""
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
                                <tr>
                                    <td><a href='/loans/` + elem.id + `'><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.first_name + ` ` + elem.last_name + `' width=100/></a></td>
                                    <td><a href='/loans/` + elem.id + `'>` + elem.email + `</a></td>
                                    <td><a href='/loans/` + elem.id + `'>` + elem.first_name + `</a></td>
                                    <td><a href='/loans/` + elem.id + `'>` + elem.last_name + `</a></td>
                                    <td><a href='/loans/` + elem.id + `'>` + elem.description + `</a></td>
                                    <td><a href='/loans/` + elem.id + `'>` + labels_list + `</a></td>
                                    <td><a href='/loans/` + elem.id + `'><img src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></a></td>
                                    <td class='actions'>
                                        ` + link + `
                                    </td>
                                </tr>
                            `);
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
                $('#date-from').datepicker('option', 'maxDate', $('#date-to').val());
                $('#date-to').datepicker('option', 'minDate', $('#date-from').val());
            }
        });

        $('#date-from').datepicker('option', 'maxDate', $('#date-to').val());
        $('#date-to').datepicker('option', 'minDate', $('#date-from').val());

         $('#search').keyup(function(){
            var searchkey = $(this).val();
            searchLoans( searchkey );
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
         $('#start_date_sort').click( function(e) {
            sort_setter('start_date');
            $('#search').keyup();
         });
         $('#end_date_sort').click( function(e) {
            sort_setter('end_date');
            $('#search').keyup();
         });

         $('#hid input').click( function(e) {
            $('#search').keyup();
         });

         $('#search').keyup();
    });
</script>
