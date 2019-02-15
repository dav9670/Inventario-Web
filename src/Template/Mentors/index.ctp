<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mentor[]|\Cake\Collection\CollectionInterface $mentors
 */
?>

<div class="mentors index large-9 medium-8 columns content">
    <h3><?= __('Mentors') ?></h3>
    
    <?= $this->Form->control('search');?>
    <a href="#" onclick="toggle_visibility('hid');"><?= __("Filters")?></a>
    <div id="hid" class="hidden" >
        <form action="/action_page.php">
            <input type="checkbox" name="Champ" value="avialable" Checked>Search Avialable
            <input type="checkbox" name="Champ"  value="mentor" checked>Search by Mentors<br>
            <input type="checkbox" name="Champ"  value="unavialable" checked>Search Unavialable
            <input type="checkbox" name="Champ"  value="skills" >Search by Skills<br>
        </form>
    </div>

    <table id = "table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('first_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('description') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mentors as $mentor): ?>
            <tr>
                <td><?= $this->Number->format($mentor->id) ?></td>
                <td><?= h($mentor->email) ?></td>
                <td><?= h($mentor->first_name) ?></td>
                <td><?= h($mentor->last_name) ?></td>
                <td><?= h($mentor->description) ?></td>
                <td><?= h($mentor->created) ?></td>
                <td><?= h($mentor->modified) ?></td>
                <td><?= h($mentor->deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $mentor->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mentor->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mentor->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mentor->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>

<script>
    $('document').ready(function(){
         $('#search').keyup(function(){
            var searchkey = $(this).val();
            searchMentors( searchkey );
         });
        function searchMentors( keyword ){
            var data = keyword;

        var checkedList = $.map($(':input[name="champ"]'),
        function(){
            var $this = $(this);
            return {
            name :$this.data('champ'), // You can add the subcatagory as a data dash attribute or any other unique identifier in  html of the checkbox before appending
            IsSelected  : $this.is(':checked')
        }});

            $.ajax({
                    method: 'get',
                    url : "/mentors/search.json",
                    data: {keyword:data, 'champ':checkedList},
                    success: function( response ){
                        var table = $("#table tbody");
                        table.empty();
                        $.each(response.mentors, function(idx, elem){
                            let idCell = "<td>" + elem.id + "</td>";
                            let emailCell = "<td>" + elem.email + "</td>";
                            let first_nameCell = "<td>" + elem.first_name + "</td>";
                            let last_nameCell = "<td>" + elem.last_name + "</td>";
                            let descriptionCell = "<td>" + elem.description + "</td>";
                            let createdCell = "<td>" + elem.created + "</td>";
                            let modifiedCell = "<td>" + elem.modified + "</td>";
                            let deletedCell = "<td>" + elem.deleted + "</td>";
                            
                            let actionsCell = "<td class=\"actions\">";
                            let viewLink = '<?= $this->Html->link(__('View'), ['action' => 'view', -1]) ?>';
                            viewLink = viewLink.replace("-1", elem.id);
                            let editLink = '<?= $this->Html->link(__('Edit'), ['action' => 'edit', -1]) ?>';
                            editLink = editLink.replace("-1", elem.id);
                            let deleteLink = '<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', -1], ['confirm' => __('Are you sure you want to delete # {0}?', -1)]) ?>';
                            deleteLink = deleteLink.replace(/-1/g, elem.id);
                            
                            actionsCell = actionsCell.concat(viewLink);
                            actionsCell = actionsCell.concat(" ");
                            actionsCell = actionsCell.concat(editLink);
                            actionsCell = actionsCell.concat(" ");
                            actionsCell = actionsCell.concat(deleteLink);
                            actionsCell = actionsCell.concat("</td>");

                            table.append("<tr>" + idCell + emailCell + first_nameCell + last_nameCell + descriptionCell + createdCell + modifiedCell + deletedCell + actionsCell + "</tr>");
                        });
                    }
            });
        };
    });
</script>
<script>
    function toggle_visibility(id) {
        var e = document.getElementById(id);
        if(e.style.display == 'block')
            e.style.display = 'none';
        else
            e.style.display = 'block';
    }
</script>
