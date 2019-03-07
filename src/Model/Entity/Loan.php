<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * Loan Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime $end_time
 * @property \Cake\I18n\FrozenTime|null $returned
 * @property int $user_id
 * @property string $item_type
 * @property int $item_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Item $item
 */
class Loan extends Entity
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
        'start_time' => true,
        'end_time' => true,
        'returned' => true,
        'user_id' => true,
        'item_type' => true,
        'item_id' => true,
        'user' => true,
        'item' => true
    ];

    public function getItem(){
        if($this->mentor != null){
            return $this->mentor;
        } else if ($this->room != null){
            return $this->room;
        } else if ($this->licence != null){
            return $this->licence;
        } else if ($this->equipment != null){
            return $this->equipment;
        }

        return null;
    }

    public function _getTimePresetsForRooms($from, $to, $room){
        $startCalcul = "";
        $endCalcul = "";

        if($this->start_time >= $from){
            $startCalcul = $this->start_time;
        }else{
            $startCalcul = $from;
        }

        if($this->returned != null){
            if($this->returned < $to){
                $endCalcul = $this->returned;
            }else{
                $endCalcul = $to;
            }
        }else{
            if($this->end_time < $to){
                $endCalcul = $this->end_time;
            }else{
                $endCalcul = $to;
            }
        }

        $startCalcul = new Time($startCalcul);
        $endCalcul = new Time($endCalcul);

        $result = [0,0,0,0,0,0,0,0,0,0];

        if($startCalcul->hour < 8){
            $startCalcul->hour = 8;
        }
        while ($startCalcul < $endCalcul) {
            $hour = 8;
            while ($hour < 17) {
                if($startCalcul < $endCalcul){
                    $result[$hour-7] ++;
                    $result[0] ++;
                }
                $startCalcul->hour ++;
                $hour++;
            }
            $startCalcul->day ++;
            $startCalcul->hour = 8;
        }
        array_push($result, $room['deleted']);
        $roomName = $room['name'];

        $servicesPreview = "";

        $services = $room['services'];
        foreach ($services as $service){
            $servicesPreview .= (string)$service['name']."; ";
        }
        array_push($result, $servicesPreview);

        $roomResult = [
            $roomName => $result
        ];
        
        return $roomResult;
    }

    protected function _getOvertimeHoursLate()
    {
        if($this->returned == null){
            if($this->end_time != null && $this->end_time <= Time::now()){
                $now = Time::now();
                $diff = $now->diff($this->end_time);
                $overtime =  $diff->days * 24 + $diff->h;
                return $overtime;
            }
        }
    }

    protected function _getOvertimeHourlyRate()
    {
        $hourly_rate = 10;
        if($this->item_type == 'equipments'){
            $equipments = TableRegistry::get('Equipments');

            $equipment = $equipments->get($this->item_id, ['contain' => ['Categories']]);
            $categories = $equipment->categories;
            
            foreach($categories as $category){
                $hourly_rate += $category->hourly_rate;
            }
        }
        return floatval($hourly_rate);
    }

    protected function _getOvertimeFee()
    {
        $overtimeFee = $this->_getOvertimeHoursLate() * $this->_getOvertimeHourlyRate();

        return floatval($overtimeFee);
    }

    protected $_virtual = ['overtime_hours_late', 'overtime_hourly_rate', 'overtime_fee'];
}
