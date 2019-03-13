<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Loans Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 *
 * @method \App\Model\Entity\Loan get($primaryKey, $options = [])
 * @method \App\Model\Entity\Loan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Loan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Loan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Loan|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Loan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Loan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Loan findOrCreate($search, callable $callback = null, $options = [])
 */
class LoansTable extends SanitizeTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('loans');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        

        $this->belongsTo('Mentors', [
            'foreignKey' => 'item_id', 
            'conditions' => ['Loans.item_type' => 'Mentors']
        ]);
        
        $this->belongsTo('Licences', [
            'foreignKey' => 'item_id', 
            'conditions' => ['Loans.item_type' => 'Licences']
        ]);

        $this->belongsTo('Rooms', [
            'foreignKey' => 'item_id', 
            'conditions' => ['Loans.item_type' => 'Rooms']
        ]);

        $this->belongsTo('Equipments', [
            'foreignKey' => 'item_id', 
            'conditions' => ['Loans.item_type' => 'Equipments']
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->dateTime('start_time')
            ->requirePresence('start_time', 'create')
            ->allowEmptyDateTime('start_time', false);
            

        $validator
            ->dateTime('end_time')
            ->requirePresence('end_time', 'create')
            ->allowEmptyDateTime('end_time', false)
            ->greaterThanField('end_time','start_time', __('End Time cannot be before Start Time.'))
            ->add('end_time',[
                'oneHourIntervals' => [
                    'rule' => 'oneHourIntervals',
                    'provider' => 'table',
                    'message' => __('The loan period must be in 1 hour intervals')
                ]
            ]);

        $validator
            ->dateTime('returned')
            ->allowEmptyDateTime('returned')
            ->add('returned',[
                'returnOnce' => [
                    'rule' => 'returnOnce',
                    'provider' => 'table',
                    'message' => __('The loan can only be returned once')
                ]
            ]);

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create');

        $validator
            ->scalar('item_type')
            ->maxLength('item_type', 50)
            ->requirePresence('item_type', 'create')
            ->allowEmptyString('item_type', false);

        $validator
            ->integer('item_id')
            ->requirePresence('item_id', 'create')
            ->add('item_id',[
                'itemNotAlreadyLoaned' => [
                    'rule' => 'itemNotAlreadyLoaned',
                    'provider' => 'table',
                    'message' => __('The item is already loaned during the period')
                ]
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function oneHourIntervals($value, $context)
    {
        $start_time = new Time($context['data']['start_time']);
        $end_time = new Time($context['data']['end_time']);
        $diff = $start_time->diff($end_time);
        $nb_hours = ($diff->days * 24) + $diff->h;
        return $nb_hours >= 1 && $diff->i == 0;
    }

    public function itemNotAlreadyLoaned($value, $context)
    {
        $table = TableRegistry::get(ucfirst($context['data']['item_type']));
        $item = $table->get($value);
        return $item->isAvailableBetween($context['data']['start_time'], $context['data']['end_time']);
    }

    public function returnOnce($value, $context)
    {
        $table = TableRegistry::get("Loans");
        $loan = $table->get($context["data"]["id"]);
        
        return $loan["returned"] == null;
    }
}
