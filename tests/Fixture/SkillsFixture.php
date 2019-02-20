<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SkillsFixture
 *
 */
class SkillsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'name_2' => ['type' => 'fulltext', 'columns' => ['name', 'description'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'name' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
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
                'id' => 1,
                'name' => 'Html',
                'description' => 'Hypertext Markup Language. Used to make the graphic structure of a website.'
            ],
            [
                'id' => 2,
                'name' => 'Javascript',
                'description' => 'Scripting language used mainly in web development. Can also be used to develop desktop applications.'
            ],
            [
                'id' => 3,
                'name' => 'Swift',
                'description' => 'Successor to Objective C, used to develop inside the MacOS / iOS environment.'
            ],
            [
                'id' => 4,
                'name' => 'Php',
                'description' => 'Porgramming language used in website backend.'
            ],
            [
                'id' => 5,
                'name' => 'Electrical engineering'
            ],
            [
                'id' => 6,
                'name' => 'First aid',
                'description' => 'Being able to react in an emergency situation involving bodily harm'
            ],
            [
                'id' => 7,
                'name' => 'Chemistry',
                'description' => 'The science of reactions that occurs between different elements'
            ],
            [
                'id' => 8,
                'name' => 'Haskell',
                'description' => 'Hypertext Markup Language. Used to make the graphic structure of a website.'
            ],
            [
                'id' => 9,
                'name' => 'Painting',
                'description' => 'Drawing using paint'
            ],
            [
                'id' => 10,
                'name' => 'Physics',
                'description' => 'The science of forces'
            ]
        ];
        parent::init();
    }
}
