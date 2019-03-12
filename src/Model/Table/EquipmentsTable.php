<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Equipments Model
 *
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsToMany $Categories
 *
 * @method \App\Model\Entity\Equipment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Equipment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Equipment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Equipment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Equipment|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Equipment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Equipment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Equipment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EquipmentsTable extends SanitizeTable
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

        $this->setTable('equipments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Categories', [
            'foreignKey' => 'equipment_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'equipments_categories'
        ]);

        $this->hasMany('Loans', [
                'className' => 'Loans', 
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
            ->scalar('name')
            ->maxLength('name', 50, __('Name is too long. (Max 50 characters)'))
            ->requirePresence('name', 'create', __('Name cannot be empty.'))
            ->allowEmptyString('name', false, __('Name cannot be empty.'));

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
}
