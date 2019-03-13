<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rooms Model
 *
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\BelongsToMany $Services
 *
 * @method \App\Model\Entity\Room get($primaryKey, $options = [])
 * @method \App\Model\Entity\Room newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Room[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Room|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Room|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Room patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Room[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Room findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoomsTable extends SanitizeTable
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

        $this->setTable('rooms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Services', [
            'foreignKey' => 'room_id',
            'targetForeignKey' => 'service_id',
            'joinTable' => 'rooms_services'
        ]);

        $this->hasMany('Loans', [
                'className' => 'Loans', 
                'foreignKey' => 'item_id', 
                'conditions' => ['Loans.item_type' => 'Rooms']
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
            ->scalar('name')
            ->maxLength('name', 50, __('Name is too long. (Max 50 characters)'))
            ->requirePresence('name', 'create', __('Name cannot be empty.'))
            ->allowEmptyString('name', false, __('Name cannot be empty.'))
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('This name has already been used.')]);

        $validator
            ->scalar('description')
            ->maxLength('description', 255, __('Description is too long. (Max 255 characters)'))
            ->allowEmptyString('description');

        $validator
            ->scalar('image')
            ->maxLength('image', 16777215, __('Image is too large.'))
            ->requirePresence('image', 'create', __('Image is required.'))
            ->allowEmptyFile('image', 'create', false, __('Image is required.'))
            ->allowEmptyFile('image', 'update', true);

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

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
        $rules->add($rules->isUnique(['name']));

        $rules->addDelete(function($entity, $options) {
            return $entity->loan_count == 0;
        }, "loans_check");

        return $rules;
    }
}
