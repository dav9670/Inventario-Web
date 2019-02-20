<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mentor Entity
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string|null $description
 * @property string $image
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Skill[] $skills
 */
class Mentor extends Entity
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
        'email' => true,
        'first_name' => true,
        'last_name' => true,
        'description' => true,
        'image' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'skills' => true
    ];

    
}
