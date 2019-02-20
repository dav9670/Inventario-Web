<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Skill Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @property \App\Model\Entity\Mentor[] $mentors
 */
class Skill extends Entity
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
        'mentors' => true
    ];


    protected function _getMentorCount()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Mentors']);
        if (is_array($this->mentors))
        {
            return count($this->mentors);
        }
        return 0;
    }

    protected $_virtual = ['mentor_count'];
}
