<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Licences Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsToMany $Products
 *
 * @method \App\Model\Entity\Licence get($primaryKey, $options = [])
 * @method \App\Model\Entity\Licence newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Licence[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Licence|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Licence|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Licence patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Licence[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Licence findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LicencesTable extends Table
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

        $this->setTable('licences');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Products', [
            'foreignKey' => 'licence_id',
            'targetForeignKey' => 'product_id',
            'joinTable' => 'licences_products'
        ]);

        $this->hasMany('Loans', [
                'className' => 'Loans', 
                'foreignKey' => 'item_id', 
                'conditions' => ['Loans.item_type' => 'Licences']
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
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('key_text')
            ->maxLength('key_text', 50)
            ->requirePresence('key_text', 'create')
            ->allowEmptyString('key_text', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('image')
            ->maxLength('image', 16777215)
            ->requirePresence('image', 'create')
            ->allowEmptyFile('image', false);

        $validator
            ->dateTime('start_time')
            ->requirePresence('start_time', 'create')
            ->allowEmptyDateTime('start_time', false);

        $validator
            ->dateTime('end_time')
            ->allowEmptyDateTime('end_time');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        return $validator;
    }
}
