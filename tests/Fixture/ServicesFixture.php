<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServicesFixture
 *
 */
class ServicesFixture extends TestFixture
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
                'name' => 'Projector',
                'description' => 'BENQ projector, can be opened by pressing a button at the entrance of the room'
            ],
            [
                'id' => 2,
                'name' => 'Whiteboard',
                'description' => 'Pencil and eraser included'
            ],
            [
                'id' => 3,
                'name' => 'Computers Windows',
                'description' => 'Include Office 365, Visual Studio, VMware, photoshop'
            ],
            [
                'id' => 4,
                'name' => 'Lab Linux',
                'description' => '30 computer on Linux'
            ],
            [
                'id' => 5,
                'name' => 'Lab Mac',
                'description' => '35 Mac Mini for programming on Xcode'
            ],
            [
                'id' => 6,
                'name' => 'Office chairs',
                'description' => 'Office chairs for more comfort'
            ],
            [
                'id' => 7,
                'name' => 'Basic chairs'
            ],
            [
                'id' => 8,
                'name' => 'Big Tables',
                'description' => 'Tables for 8 persons'
            ],
            [
                'id' => 9,
                'name' => 'Basic tables',
                'description' => 'Table for 1 or 2 persons'
            ],
            [
                'id' => 10,
                'name' => 'Phone',
                'description' => 'Place next to the front door'
            ]
        ];
        parent::init();
    }
}
