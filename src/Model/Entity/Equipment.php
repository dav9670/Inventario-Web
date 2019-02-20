<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Equipment Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $image
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Category[] $categories
 */
class Equipment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'description' => true,
        'image' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'categories' => true
    ];

    protected function _getAvailable()
    {
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Equipments']])
            ->where('Loans.item_id = :id and Loans.start_time <= NOW() and Loans.returned is not null')
            ->bind(':id', $this->id);
        $nbloans = $myloans->count();
        
        return $nbloans > 0 && is_null($this->deleted);
    }

    protected $_virtual = ['available'];
}
