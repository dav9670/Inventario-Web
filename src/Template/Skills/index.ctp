<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill[]|\Cake\Collection\CollectionInterface $skills
 */
?>

<div class="skills index large-12 medium-11 columns content">
    
    <div class="left">
        <h3><?= __('Skills') ?></h3>
    </div>
    <div class="right">
        <?= $this->Html->link(__('Mentors') . ' 🡆', ['controller' => 'Mentors', 'action' => 'index']) ?>
    </div>

    <div style="clear: both;"></div>

    <div class="search-container">
        <a href="/skills/add">
            <img class="plus" src="/img/plus.png" alt="Plus">
        </a>

        <div class="search-bar">
            <label for="search"><?= __('Search') ?></label>
            <input type="text" name="search" id="search">
        </div>
    </div>
    <table id="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><a id='name_sort' class='asc'><?= __("Name") ?></a></th>
                <th scope="col"><a id='description_sort'><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Mentor count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    var sort_field = "name";
    var sort_dir = "asc";

    function searchSkills( keyword ){
        var data = keyword;
        $.ajax({
                method: 'get',
                url : "/skills/search.json",
                data: {keyword:data, sort_field: sort_field, sort_dir: sort_dir},
                success: function( response ){
                    var table = $("#table tbody");
                    table.empty();
                    $.each(response.skills, function(idx, elem){
                        let nameCell = "<td><a href='/skills/" + elem.id + "'>" + elem.name + "</a></td>";
                        let descriptionCell = "<td><a href='/skills/" + elem.id + "'>" + elem.description + "</a></td>";
                        let mentorCountCell = "<td><a href='/skills/" + elem.id + "'>" + elem.mentor_count + "</a></td>";
                        let actionsCell = "<td class=\"actions\">";

                        let deleteLink = '<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', -1], ['confirm' => __('Are you sure you want to delete # {0}?', -1)]) ?>';
                        deleteLink = deleteLink.replace(/-1/g, elem.id);
                        
                        actionsCell = actionsCell.concat(deleteLink);
                        actionsCell = actionsCell.concat("</td>");

                        table.append("<tr>" + nameCell + descriptionCell + mentorCountCell + actionsCell + "</tr>");
                    });
                }
        });
    };

    
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
            searchSkills( searchkey );
         });

         $('#name_sort').click( function(e) {
            sort_setter('name');
            $('#search').keyup();
         });
         $('#description_sort').click( function(e) {
            sort_setter('description');
            $('#search').keyup();
         });
         /*$('#mentor_count_sort').click( function(e) {
            sort_setter('mentor_count');
            $('#search').keyup();
         });*/

         $('#search').keyup();
    });
</script>