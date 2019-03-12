<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SanitizeTable extends Table 
{
    public function beforeMarshal($event, $data, $options)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = h($value);
            }
        }
    }
}

?>