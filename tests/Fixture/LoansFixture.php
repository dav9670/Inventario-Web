<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LoansFixture
 *
 */
class LoansFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'start_time' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'end_time' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'returned' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'item_type' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'item_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'loans_user_key' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'loans_user_key' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'start_time' => '2018-10-01 12:00:00',
                'end_time' => '2018-10-31 12:00:00',
                'returned' => '2018-10-30 22:00:00',
                'user_id' => 1,
                'item_type' => 'mentors',
                'item_id' => 1
            ],
            [
                'id' => 2,
                'start_time' => '2018-11-01 12:00:00',
                'end_time' => '2018-11-03 12:00:00',
                'returned' => '2018-11-03 12:00:00',
                'user_id' => 6,
                'item_type' => 'mentors',
                'item_id' => 2
            ],
            [
                'id' => 3,
                'start_time' => '2018-11-04 12:00:00',
                'end_time' => '2018-11-05 12:00:00',
                'returned' => '2018-11-05 13:00:00',
                'user_id' => 5,
                'item_type' => 'mentors',
                'item_id' => 2
            ],
            [
                'id' => 4,
                'start_time' => '2019-02-17 12:00:00',
                'end_time' => '2019-02-20 12:00:00',
                'user_id' => 2,
                'item_type' => 'mentors',
                'item_id' => 4
            ],
            [
                'id' => 5,
                'start_time' => '2019-02-24 12:00:00',
                'end_time' => '2019-03-01 12:00:00',
                'user_id' => 3,
                'item_type' => 'mentors',
                'item_id' => 5
            ],

            [
                'id' => 6,
                'start_time' => '2019-02-18 12:00:00',
                'end_time' => '2019-02-21 12:00:00',
                'user_id' => 7,
                'item_type' => 'rooms',
                'item_id' => 3
            ],
            [
                'id' => 7,
                'start_time' => '2019-01-13 12:00:00',
                'end_time' => '2019-01-15 12:00:00',
                'returned' => '2019-01-15 12:00:00',
                'user_id' => 6,
                'item_type' => 'rooms',
                'item_id' => 1
            ],
            [
                'id' => 8,
                'start_time' => '2019-01-10 12:00:00',
                'end_time' => '2019-01-17 12:00:00',
                'returned' => '2019-01-15 14:00:00',
                'user_id' => 6,
                'item_type' => 'rooms',
                'item_id' => 1
            ],
            [
                'id' => 9,
                'start_time' => '2019-02-01 12:00:00',
                'end_time' => '2019-02-03 12:00:00',
                'returned' => '2019-02-04 09:00:00',
                'user_id' => 1,
                'item_type' => 'rooms',
                'item_id' => 2
            ],
            [
                'id' => 10,
                'start_time' => '2019-03-03 12:00:00',
                'end_time' => '2019-03-06 12:00:00',
                'user_id' => 2,
                'item_type' => 'rooms',
                'item_id' => 2
            ],

            [
                'id' => 11,
                'start_time' => '2018-11-01 12:00:00',
                'end_time' => '2018-11-05 12:00:00',
                'returned' => '2018-11-05 14:00:00',
                'user_id' => 2,
                'item_type' => 'licences',
                'item_id' => 3
            ],
            [
                'id' => 12,
                'start_time' => '2019-02-13 12:00:00',
                'end_time' => '2019-03-01 12:00:00',
                'user_id' => 5,
                'item_type' => 'licences',
                'item_id' => 5
            ],
            [
                'id' => 13,
                'start_time' => '2019-02-01 12:00:00',
                'end_time' => '2019-02-03 12:00:00',
                'returned' => '2019-02-04 09:00:00',
                'user_id' => 1,
                'item_type' => 'licences',
                'item_id' => 7
            ],
            [
                'id' => 14,
                'start_time' => '2019-02-23 12:00:00',
                'end_time' => '2019-03-03 12:00:00',
                'user_id' => 1,
                'item_type' => 'licences',
                'item_id' => 1
            ],
            [
                'id' => 15,
                'start_time' => '2019-02-07 12:00:00',
                'end_time' => '2019-02-09 12:00:00',
                'returned' => '2019-02-09 09:00:00',
                'user_id' => 4,
                'item_type' => 'licences',
                'item_id' => 2
            ],

            [
                'id' => 16,
                'start_time' => '2018-11-01 12:00:00',
                'end_time' => '2018-11-05 12:00:00',
                'returned' => '2018-11-05 14:00:00',
                'user_id' => 2,
                'item_type' => 'equipments',
                'item_id' => 3
            ],
            [
                'id' => 17,
                'start_time' => '2019-02-13 12:00:00',
                'end_time' => '2019-03-01 12:00:00',
                'user_id' => 4,
                'item_type' => 'equipments',
                'item_id' => 5
            ],
            [
                'id' => 18,
                'start_time' => '2019-02-01 12:00:00',
                'end_time' => '2019-02-03 12:00:00',
                'returned' => '2019-02-04 09:00:00',
                'user_id' => 4,
                'item_type' => 'equipments',
                'item_id' => 7
            ],
            [
                'id' => 19,
                'start_time' => '2019-02-23 12:00:00',
                'end_time' => '2019-03-03 12:00:00',
                'user_id' => 2,
                'item_type' => 'equipments',
                'item_id' => 1
            ],
            [
                'id' => 20,
                'start_time' => '2019-02-07 12:00:00',
                'end_time' => '2019-02-09 12:00:00',
                'returned' => '2019-02-09 09:00:00',
                'user_id' => 3,
                'item_type' => 'equipments',
                'item_id' => 2
            ],
        ];
        parent::init();
    }
}
