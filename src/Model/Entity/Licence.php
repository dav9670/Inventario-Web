<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Licence Entity
 *
 * @property int $id
 * @property string $name
 * @property string $key_text
 * @property string|null $description
 * @property string $image
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime|null $end_time
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Product[] $products
 */
class Licence extends Entity
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
        'key_text' => true,
        'description' => true,
        'image' => true,
        'start_time' => true,
        'end_time' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'products' => true
    ];

    protected function _getAvailable()
    {
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Licences']])
            ->where('Loans.item_type like \'licences\' and Loans.item_id = :id and Loans.start_time <= NOW() and Loans.returned is null')
            ->bind(':id', $this->id);
        $nbloans = $myloans->count();
        
        return $nbloans == 0 && is_null($this->deleted);
    }

    protected function _getProductsList()
    {
        TableRegistry::get($this->getSource())->loadInto($this, ['Products']);
        if (is_array($this->products))
        {
            $productnames = array();
            foreach ($this->products as $product)
            {
                $productnames[] = $product->name;
            }

            return $productnames;
        }
        return array();
    }

    protected function _getStatus()
    {
        $now = Time::now();
        if ($this->start_time > $now)
        {
            return __("Not yet active");
        }
        else if ($this->start_time < $now && $this->end_time > $now)
        {
            return __("Ongoing");
        }
        else if (is_null($this->end_time))
        {
            return __("Infinite");
        }
        else
        {
            return __("Expired Status");
        }
    }

    protected function _getLoanCount()
    {
        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Licences']])
            ->where('Loans.item_type like \'licences\' and Loans.item_id = :id')
            ->bind(':id', $this->id);
        $nbloans = $myloans->count();
        
        return $nbloans;
    }

    protected $_virtual = ['available', 'products_list', 'status', 'loan_count'];

    public function isAvailableBetween($start_time, $end_time)
    {
        $start_time = new Time($start_time);
        $end_time = new Time($end_time);

        $loans = TableRegistry::get('Loans');
        $myloans = $loans->find('all', ['contains' => ['Licences']])
            ->where('Loans.item_type like \'licences\' and Loans.item_id = :id and (Loans.start_time <= :end_time and Loans.end_time >= :start_time)')
            ->bind(':id', $this->id)
            ->bind(':start_time', $start_time->i18nFormat(null, "America/Toronto"))
            ->bind(':end_time', $end_time->i18nFormat(null, "America/Toronto"));
        $nbloans = $myloans->count();
        
        return $nbloans == 0 && is_null($this->deleted);
    }
}
