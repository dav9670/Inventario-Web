<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MentorsSkillsFixture
 *
 */
class MentorsSkillsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'mentor_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'skill_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'mentors_skills_skill_key' => ['type' => 'index', 'columns' => ['skill_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['mentor_id', 'skill_id'], 'length' => []],
            'mentors_skills_mentor_key' => ['type' => 'foreign', 'columns' => ['mentor_id'], 'references' => ['mentors', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'mentors_skills_skill_key' => ['type' => 'foreign', 'columns' => ['skill_id'], 'references' => ['skills', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'mentor_id' => 1,
                'skill_id' => 3
            ],
            [
                'mentor_id' => 2,
                'skill_id' => 1
            ],
            [
                'mentor_id' => 2,
                'skill_id' => 2
            ],
            [
                'mentor_id' => 2,
                'skill_id' => 4
            ],
            [
                'mentor_id' => 3,
                'skill_id' => 1
            ],
            [
                'mentor_id' => 3,
                'skill_id' => 2
            ],
            [
                'mentor_id' => 3,
                'skill_id' => 3
            ],
            [
                'mentor_id' => 3,
                'skill_id' => 4
            ],
            [
                'mentor_id' => 3,
                'skill_id' => 5
            ],
            [
                'mentor_id' => 4,
                'skill_id' => 7
            ],
            [
                'mentor_id' => 4,
                'skill_id' => 10
            ],
            [
                'mentor_id' => 5,
                'skill_id' => 6
            ]
        ];
        parent::init();
    }
}
