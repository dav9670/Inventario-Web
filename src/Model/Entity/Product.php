<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string $platform
 * @property string|null $description
 *
 * @property \App\Model\Entity\Licence[] $licences
 */
class Product extends Entity
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
        'platform' => true,
        'description' => true,
        'licences' => true
    ];


    protected function _getLicenceCount()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Licences']);
        if (is_array($this->licences))
        {
            return count($this->licences);
        }
        return 0;
    }

    protected $_virtual = ['licence_count'];
}
