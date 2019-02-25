<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

/**
 * Products Model
 *
 * @property \App\Model\Table\LicencesTable|\Cake\ORM\Association\BelongsToMany $Licences
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductsTable extends Table
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Licences', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'licence_id',
            'joinTable' => 'licences_products'
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
            ->allowEmptyString('name', false, __('Name cannot be empty.'));

        $validator
            ->scalar('platform')
            ->maxLength('platform', 50, __('Platform name is too long. (Max 50 characters)'))
            ->requirePresence('platform', 'create', __('Platform name cannot be empty.'))
            ->allowEmptyString('platform', false, __('Platform name cannot be empty.'));

        $validator
            ->scalar('description')
            ->maxLength('description', 255, __('Description is too long. (Max 255 characters)'))
            ->allowEmptyString('description');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['name', 'platform'], __('This name & platform combination has already been used.') ));

        return $rules;
    }
}
