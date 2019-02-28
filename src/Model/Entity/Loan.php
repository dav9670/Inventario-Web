<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;

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

    public function _getTimePresetsForRooms($from, $to, $roomName){
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

        $result = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];

        if($startCalcul->hour < 8){
            $startCalcul->hour = 8;
        }
        while ($startCalcul < $endCalcul) {
            $hour = 8;
            while ($hour <= 20) {
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
        $roomResult = [
            $roomName => $result
        ];
        
        return $roomResult;
    }

}
