<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipment[]|\Cake\Collection\CollectionInterface $equipments
 */
?>

<div class="equipments index large-12 medium-11 columns content">
    
    <div class="left">
        <h3><?= __('Equipments') ?></h3>
    </div>
    <div class="right">
        <?= $this->Html->link(__('Categories') . ' 🡆', ['controller' => 'Categories', 'action' => 'index']) ?>
    </div>

    <div style="clear: both;"></div>

    <div class="search-container">
        <a href="/equipments/add">
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

    <a href="#" onclick="toggle_visibility('hid');"><?= __("Filters")?></a>
    <div id="hid" class="hidden" >
        <form action="/action_page.php">
            <input type="checkbox" id="FieldAvailable" checked><?= __('Search Available') ?>
            <input type="checkbox" id="FieldEquipments" checked><?= __('Search by Equipments') ?><br>
            <input type="checkbox" id="FieldUnavailable" checked><?= __('Search Unavailable') ?>
            <input type="checkbox" id="FieldCategories"><?= __('Search by Categories') ?><br>
        </form>
    </div>
    <div class="tab">
        <button id='table_activated_button' class="tablinks active" onclick="show_table('table_activated')"><?= __('Activated') ?></button>
        <button id='table_archived_button' class="tablinks" onclick="show_table('table_archived')"><?= __('Archived') ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>

                    <th scope="col"><a id='name_sort' class='asc'><?= __("Name") ?></a></th>
                    <th scope="col"><a id='description_sort'><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Categories") ?></th>
                    <th scope="col"><?= __("Available") ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody id="table_activated">
            </tbody>
            <tbody id="table_archived" hidden="hidden">
            </tbody>
        </table>
    </div>
</div>

<script>
    var sort_field = "name";
    var sort_dir = "asc";

    var current_table = "table_activated";

    function searchEquipments( keyword ){
        var data = keyword;

        var filters = {
            search_available: $('#FieldAvailable').is(':checked'),
            search_unavailable: $('#FieldUnavailable').is(':checked'),
            search_equipments: $('#FieldEquipments').is(':checked'),
            search_categories: $('#FieldCategories').is(':checked')
        };

        $.ajax({
                method: 'get',
                url : "/equipments/search.json",
                data: {keyword:data, sort_field:sort_field, sort_dir:sort_dir, filters: filters},
                success: function( response ){
                    
                    for(var i=0; i<2; i++){
                        var table_name = "";
                        var array_name = "";
                        if(i == 0){
                            table_name = "table_activated";
                            array_name = "equipments";
                        } else if(i == 1){
                            table_name = "table_archived";
                            array_name = "archivedEquipments";
                        }
                        var table = $("#" + table_name);
                        table.empty();

                        equipmentsArray = response[array_name];
                        $.each(equipmentsArray, function(idx, elem){

                            var categories_list = "";
                            var three_categories = elem.categories_list.slice(0,3);
                            if (elem.categories_list.length > 3) {
                                categories_list = three_categories.join("; ") + "...";
                            } else {
                                categories_list = three_categories.join("; ");
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
                            if(elem.deleted == null){
                                link = link.concat('<?= $this->Html->link(__('Deactivate'), ['action' => 'deactivate', -1], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', -2)]) ?> ');
                            } else {
                                link = link.concat('<?= $this->Html->link(__('Reactivate'), ['action' => 'reactivate', -1], ['confirm' => __('Are you sure you want to reactivate {0}?', -2)]) ?> ');
                                if(elem.loan_count == 0){
                                    link = link.concat('<br/><?= $this->Html->link(__('Delete'), ['action' => 'delete', -1], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', -2)]) ?> ');
                                }
                            }
                            link = link.replace(/-1/g, elem.id);
                            link = link.replace(/-2/g, elem.name);

                            table.append(`
                                <tr>
                                    <td><a href='/equipments/` + elem.id + `'><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.name + `' width=100/></a></td>
                                   
                                    <td><a href='/equipments/` + elem.id + `'>` + elem.name + `</a></td> 
                                    <td><a href='/equipments/` + elem.id + `'>` + elem.description + `</a></td>
                                    <td><a href='/equipments/` + elem.id + `'>` + categories_list + `</a></td>
                                    <td><a href='/equipments/` + elem.id + `'><img src='/img/` + imgTag + `' alt='` + imgAlt + `' width=20 height=20></a></td>
                                    <td class='actions'>
                                        ` + link + `
                                    </td>
                                </tr>
                            `);
                        });
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed to fetch equipments");
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
         $('#search').keyup(function(){
            var searchkey = $(this).val();
            searchEquipments( searchkey );
         });
         $('#name_sort').click( function(e) {
            sort_setter('name');
            $('#search').keyup();
         });
         $('#description_sort').click( function(e) {
            sort_setter('description');
            $('#search').keyup();
         });
         $('#FieldAvailable').click( function(e) {
            $('#search').keyup();
         });
         $('#FieldUnavailable').click( function(e) {
            $('#search').keyup();
         });
         $('#FieldEquipments').click( function(e) {
            $('#search').keyup();
         });
         $('#FieldCategories').click( function(e) {
            $('#search').keyup();
         });

         $('#search').keyup();
    });

    function toggle_visibility(id) {
        var e = document.getElementById(id);
        if(e.style.display == 'block')
            e.style.display = 'none';
        else
            e.style.display = 'block';
    }
</script>
