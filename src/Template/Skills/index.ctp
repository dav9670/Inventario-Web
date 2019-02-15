<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill[]|\Cake\Collection\CollectionInterface $skills
 */
?>

<div class="skills index large-9 medium-8 columns content">
    <h3><?= __('Skills') ?></h3>

    <?= $this->Form->control('search');?>
    <table id="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('description') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skills as $skill): ?>
            <tr>
                <td><?= $this->Number->format($skill->id) ?></td>
                <td><?= h($skill->name) ?></td>
                <td><?= h($skill->description) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $skill->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $skill->id]) ?>
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
                            let idCell = "<td>" + elem.id + "</td>";
                            let nameCell = "<td>" + elem.name + "</td>";
                            let descriptionCell = "<td>" + elem.description + "</td>";
                            
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

                            table.append("<tr>" + idCell + nameCell + descriptionCell + actionsCell + "</tr>");
                        });
                    }
            });
        };
    });
</script>