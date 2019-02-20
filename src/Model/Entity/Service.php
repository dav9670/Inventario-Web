<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Service Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @property \App\Model\Entity\Room[] $rooms
 */
class Service extends Entity
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
        'rooms' => true
    ];


    protected function _getRoomCount()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Rooms']);
        if (is_array($this->rooms))
        {
            return count($this->rooms);
        }
        return 0;
    }

    protected $_virtual = ['room_count'];
}
