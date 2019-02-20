<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 *
 */
class CategoriesFixture extends TestFixture
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
        'hourly_rate' => ['type' => 'float', 'length' => 4, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
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
                'name' => 'Computer',
                'description' => 'An electronical device now use in everyday life.',
                'hourly_rate' => 15.20
            ],
            [
                'id' => 2,
                'name' => 'Phone',
                'description' => 'Used to call someone, phone is now more a micro computer than a phone.',
                'hourly_rate' => 13.33
            ],
            [
                'id' => 3,
                'name' => 'Books',
                'description' => 'Yes books still exists.',
                'hourly_rate' => 5.13
            ],
            [
                'id' => 4,
                'name' => 'Electronic',
                'description' => 'All devices that use electronical components.',
                'hourly_rate' => 21.36
            ],
            [
                'id' => 5,
                'name' => 'Electric',
                'description' => 'Use electricity to function',
                'hourly_rate' => 15.20
            ],
            [
                'id' => 6,
                'name' => 'Manual',
                'description' => 'Must be used through human strength',
                'hourly_rate' => 7.00
            ],
            [
                'id' => 7,
                'name' => 'Wireless',
                'description' => 'Uses a battery, can be recharged',
                'hourly_rate' => 10.50
            ],
            [
                'id' => 8,
                'name' => 'Wired',
                'description' => 'Must be plugged into an outlet to function',
                'hourly_rate' => 5.00
            ],
            [
                'id' => 9,
                'name' => '4K',
                'description' => 'Screen resolution of 3840 x 2160 pixels',
                'hourly_rate' => 14.00
            ],
            [
                'id' => 10,
                'name' => 'RGB',
                'description' => 'Reconfigurable lighting',
                'hourly_rate' => 2.00
            ],
        ];
        parent::init();
    }
}
