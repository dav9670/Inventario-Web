<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

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

    protected function _getAvailable()
    {
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Mentors']])
            ->where('Loans.item_type like \'mentors\' and Loans.item_id = :id and Loans.start_time <= NOW() and Loans.returned is null')
            ->bind(':id', $this->id);
        $nbloans = $myloans->count();
        
        return $nbloans == 0 && is_null($this->deleted);
    }

    protected function _getSkillsList()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Skills']);
        if (is_array($this->skills))
        {
            $skillnames = array();
            foreach ($this->skills as $skill)
            {
                $skillnames[] = $skill->name;
            }

            return $skillnames;
        }
        return array();
    }

    protected function _getLoanCount()
    {
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Mentors']])
            ->where('Loans.item_type like \'mentors\' and Loans.item_id = :id')
            ->bind(':id', $this->id);
        $nbloans = $myloans->count();
        
        return $nbloans;
    }

    protected $_virtual = ['available', 'skills_list', 'loan_count'];

    public function isAvailableBetween($start_time, $end_time)
    {
        $start_time = new Time($start_time);
        $end_time = new Time($end_time);
        
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Mentors']])
            ->where('Loans.item_type like \'mentors\' and Loans.item_id = :id and (Loans.start_time <= :end_time and Loans.end_time >= :start_time)')
            ->bind(':id', $this->id)
            ->bind(':start_time', $start_time->i18nFormat(null, Configure::read('App.defaultTimezone')))
            ->bind(':end_time', $end_time->i18nFormat(null, Configure::read('App.defaultTimezone')));
        $nbloans = $myloans->count();
        
        return $nbloans == 0 && is_null($this->deleted);
    }
}
