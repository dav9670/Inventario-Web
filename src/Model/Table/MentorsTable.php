<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mentors Model
 *
 * @property \App\Model\Table\SkillsTable|\Cake\ORM\Association\BelongsToMany $Skills
 *
 * @method \App\Model\Entity\Mentor get($primaryKey, $options = [])
 * @method \App\Model\Entity\Mentor newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Mentor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Mentor|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mentor|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mentor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Mentor[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Mentor findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MentorsTable extends SanitizeTable
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

        $this->setTable('mentors');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Skills', [
            'foreignKey' => 'mentor_id',
            'targetForeignKey' => 'skill_id',
            'joinTable' => 'mentors_skills'
        ]);

        $this->hasMany('Loans', [
                'className' => 'Loans', 
                'foreignKey' => 'item_id', 
                'conditions' => ['Loans.item_type' => 'Mentors']
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
            ->email('email')
            ->maxLength('email', 50, __('Email is too long. (Max 50 characters)'))
            ->requirePresence('email', 'create', __('Email cannot be empty.'))
            ->allowEmptyString('email', false, __('Email cannot be empty.'))
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('This email has already been used.')]);

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 50, __('First Name is too long. (Max 50 characters)'))
            ->requirePresence('first_name', 'create', __('First Name cannot be empty.'))
            ->allowEmptyString('first_name', false, __('First Name cannot be empty.'));

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 50, __('Last Name is too long. (Max 50 characters)'))
            ->requirePresence('last_name', 'create', __('Last Name cannot be empty.'))
            ->allowEmptyString('last_name', false, __('Last Name cannot be empty.'));

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
