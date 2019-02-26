<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mentor[]|\Cake\Collection\CollectionInterface $mentors
 */
?>

<div class="mentors index large-12 medium-11 columns content">
    
    <div class="left">
        <h3><?= __('Mentors') ?></h3>
    </div>
    <div class="right">
        <?= $this->Html->link(__('Skills') . ' 🡆', ['controller' => 'Skills', 'action' => 'index']) ?>
    </div>

    <div style="clear: both;"></div>

    <div class="search-container">
        <a href="/mentors/add">
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
            <input type="checkbox" id="FieldAvailable" checked><?=__('Search Available') ?>
            <input type="checkbox" id="FieldMentors" checked><?=__('Search by Mentors') ?><br>
            <input type="checkbox" id="FieldUnavailable" checked><?=__('Search Unavailable') ?>
            <input type="checkbox" id="FieldSkills"><?=__('Search by Skills') ?><br>
        </form>
    </div>
    <div class="tab">
        <button id='table_activated_button' class="tablinks active" onclick="show_table('table_activated')"><?= __("Activated") ?></button>
        <button id='table_archived_button' class="tablinks" onclick="show_table('table_archived')"><?= __("Archived") ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='email_sort' class='asc'><?= __("Email") ?></a></th>
                    <th scope="col"><a id='first_name_sort'><?= __("First Name") ?></a></th>
                    <th scope="col"><a id='last_name_sort'><?= __("Last Name") ?></a></th>
                    <th scope="col"><a id='description_sort'><?= __("Description") ?></a></th>
                    <th scope="col"><?= __("Skills") ?></th>
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
    var sort_field = "email";
    var sort_dir = "asc";

    var current_table = "table_activated";

    function searchMentors( keyword ){
        var data = keyword;

        var filters = {
            search_available: $('#FieldAvailable').is(':checked'),
            search_unavailable: $('#FieldUnavailable').is(':checked'),
            search_mentors: $('#FieldMentors').is(':checked'),
            search_skills: $('#FieldSkills').is(':checked')
        };

        $.ajax({
                method: 'get',
                url : "/mentors/search.json",
                data: {keyword:data, sort_field:sort_field, sort_dir:sort_dir, filters: filters},
                complete: function(jq, status){
                    console.log(status);
                },
                success: function( response ){
                    
                    for(var i=0; i<2; i++){
                        var table_name = "";
                        var array_name = "";
                        if(i == 0){
                            table_name = "table_activated";
                            array_name = "mentors";
                        } else if(i == 1){
                            table_name = "table_archived";
                            array_name = "archivedMentors";
                        }
                        var table = $("#" + table_name);
                        table.empty();

                        mentorsArray = response[array_name];
                        $.each(mentorsArray, function(idx, elem){
                            let pic = "<img src='data:image/png;base64," + elem.image + "' alt='" + elem.first_name + " " + elem.last_name + "' width=100/>";
                            let picCell = "<td><a href='/mentors/" + elem.id + "'>" + pic + "</a></td>";

                            let emailCell = "<td><a href='/mentors/" + elem.id + "'>" + elem.email + "</a></td>";
                            let first_nameCell = "<td><a href='/mentors/" + elem.id + "'>" + elem.first_name + "</a></td>";
                            let last_nameCell = "<td><a href='/mentors/" + elem.id + "'>" + elem.last_name + "</a></td>";
                            let descriptionCell = "<td><a href='/mentors/" + elem.id + "'>" + elem.description + "</a></td>";

                            var skills_list = "";
                            var three_skills = elem.skills_list.slice(0,3);
                            if (elem.skills_list.length > 3) {
                                skills_list = three_skills.join(", ") + "...";
                            } else {
                                skills_list = three_skills.join(", ");
                            }
                            let skillsCell = "<td><a href='/mentors/" + elem.id + "'>" + skills_list + "</a></td>";

                            var imgTag = "";
                            if (elem.available) {
                                imgTag = "<img src='/img/good.png' alt='Available' width=20 height=20>";
                            } else {
                                imgTag = "<img src='/img/bad.png' alt='Not Available' width=20 height=20>";
                            }
                            let availableCell = "<td><a href='/mentors/" + elem.id + "'>" + imgTag + "</a></td>";
                            
                            
                            let actionsCell = "<td class=\"actions\">";
                            var link = ""
                            if(elem.deleted == null){
                                link = link.concat('<?= $this->Html->link(__('Deactivate'), ['action' => 'deactivate', -1], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', -2)]) ?> ');
                            } else {
                                link = link.concat('<?= $this->Html->link(__('Reactivate'), ['action' => 'reactivate', -1], ['confirm' => __('Are you sure you want to reactivate {0}?', -2)]) ?> ');
                                if(elem.loan_count == 0){
                                    link = link.concat('<?= $this->Html->link(__('Delete'), ['action' => 'delete', -1], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', -2)]) ?> ');
                                }
                            }

                            link = link.replace(/-1/g, elem.id);
                            link = link.replace(/-2/g, elem.email);

                            actionsCell = actionsCell.concat(link);
                            actionsCell = actionsCell.concat("</td>");

                            table.append("<tr>" + picCell + emailCell + first_nameCell + last_nameCell + descriptionCell + skillsCell + availableCell + actionsCell + "</tr>");
                        });
                    }
                    
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
            searchMentors( searchkey );
         });

         $('#email_sort').click( function(e) {
            sort_setter('email');
            $('#search').keyup();
         });
         $('#first_name_sort').click( function(e) {
            sort_setter('first_name');
            $('#search').keyup();
         });
         $('#last_name_sort').click( function(e) {
            sort_setter('last_name');
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
         $('#FieldMentors').click( function(e) {
            $('#search').keyup();
         });
         $('#FieldSkills').click( function(e) {
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
