<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Users Model
 *
 * @property \App\Model\Table\LoansTable|\Cake\ORM\Association\HasMany $Loans
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Loans', [
            'foreignKey' => 'user_id'
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
            ->requirePresence('email', 'create',__('Please enter an Email.'))
            ->allowEmptyString('email', false)
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'], _('Please enter a valid Email: exemple@exemple.com'));

        $validator
            ->scalar('password')
            ->maxLength('password', 255, _('You need a password.'))
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false);

        $validator
            ->scalar('admin_status')
            ->maxLength('admin_status', 50)
            ->requirePresence('admin_status', 'create',__('You need to pick one.'))
            ->allowEmptyString('admin_status', false)
            ->add('admin_status',[
                'moreThanOneAdmin' => [
                    'rule' => 'moreThanOneAdmin',
                    'provider' => 'table',
                    'message' => __('There must always be at least one administrator.')
                ]
            ]);

        $validator
            ->scalar('image')
            ->maxLength('image', 16777215, __('Image is too large.'))
            ->requirePresence('image', 'create', __('Image is required.'))
            ->allowEmptyFile('image', 'create', false, __('Image is required.'))
            ->allowEmptyFile('image', 'update', true);

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

    public function moreThanOneAdmin($value, $context)
    {
        $table = TableRegistry::get('Users');
        if ($value == 'user')
        {
            $items = $table->find()->where(['admin_status' => 'admin'])->toList();
            if (count($items) <= 1)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }
}
