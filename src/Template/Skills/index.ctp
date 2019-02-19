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
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('description') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mentors') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skills as $skill): ?>
                <tr class='clickable-row' data-url='/skills/<?= h($skill->id) ?>'>
                    <td><a href='/skills/<?= h($skill->id) ?>'><?= h($skill->name) ?></a></td>
                    
                    <td><a href='/skills/<?= h($skill->id) ?>'><?= h($skill->description) ?></a></td>

                    <td><a href='/skills/<?= h($skill->id) ?>'><?= h($skill->mentors->count()) ?></a></td>

                    <td class="actions">
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]) ?>
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
            searchSkills( searchkey );
         });
        function searchSkills( keyword ){
            var data = keyword;
            $.ajax({
                    method: 'get',
                    url : "/skills/search.json",
                    data: {keyword:data},
                    success: function( response ){
                        var table = $("#table tbody");
                        table.empty();
                        $.each(response.skills, function(idx, elem){
                            let nameCell = "<td><a href='/skills/" + elem.id + "'>" + elem.name + "</a></td>";
                            let descriptionCell = "<td><a href='/skills/" + elem.id + "'>" + elem.description + "</a></td>";
                            let actionsCell = "<td class=\"actions\">";

                            let deleteLink = '<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', -1], ['confirm' => __('Are you sure you want to delete # {0}?', -1)]) ?>';
                            deleteLink = deleteLink.replace(/-1/g, elem.id);
                            
                            actionsCell = actionsCell.concat(deleteLink);
                            actionsCell = actionsCell.concat("</td>");

                            table.append("<tr>" + nameCell + descriptionCell + actionsCell + "</tr>");
                        });
                    }
            });
        };
    });
</script>