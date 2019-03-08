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
                ->where('Loans.item_type like \'equipments\' and Loans.item_id = :id and Loans.start_time <= NOW() and Loans.returned is null')
                ->bind(':id', $this->id);
            $nbloans = $myloans->count();
        
            return $nbloans == 0 && is_null($this->deleted);
    }

    protected function _getCategoriesList()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Categories']);
        if (is_array($this->categories))
        {
            $categorynames = array();
            foreach ($this->categories as $category)
            {
                $categorynames[] = $category->name;
            }

            return $categorynames;
        }
        return array();
    }
    
    protected function _getLoanCount()
    {
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Equipments']])
            ->where('Loans.item_type like \'equipments\' and Loans.item_id = :id')
            ->bind(':id', $this->id);
        $nbloans = $myloans->count();
        
        return $nbloans;
    }

    protected $_virtual = ['available', 'categories_list', 'loan_count'];
}
