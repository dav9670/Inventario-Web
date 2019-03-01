<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Category Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $hourly_rate
 *
 * @property \App\Model\Entity\Equipment[] $equipments
 */
class Category extends Entity
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
        'hourly_rate' => true,
        'equipments' => true
    ];


    protected function _getEquipmentCount()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Equipments']);
        if (is_array($this->equipments))
        {
            return count($this->equipments);
        }
        return 0;
    }

    protected function _getEquipmentCountAvailable()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Equipments']);
        if (is_array($this->equipments))
        {
            $count =0;
            foreach($this->equipments as $equipment){
                if ($equipment->available){
                    $count = $count +1;
                }
            }
            return $count;
        }
        return 0;
    }

    protected $_virtual = ['equipment_count', 'equipment_count_available'];
}
