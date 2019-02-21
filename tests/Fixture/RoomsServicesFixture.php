<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoomsServicesFixture
 *
 */
class RoomsServicesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'room_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'service_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'rooms_services_service_key' => ['type' => 'index', 'columns' => ['service_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['room_id', 'service_id'], 'length' => []],
            'rooms_services_room_key' => ['type' => 'foreign', 'columns' => ['room_id'], 'references' => ['rooms', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'rooms_services_service_key' => ['type' => 'foreign', 'columns' => ['service_id'], 'references' => ['services', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'room_id' => 1,
                'service_id' => 1
            ],
            [
                'room_id' => 1,
                'service_id' => 2
            ],
            [
                'room_id' => 1,
                'service_id' => 5
            ],
            [
                'room_id' => 1,
                'service_id' => 6
            ],
            [
                'room_id' => 1,
                'service_id' => 9
            ],
            [
                'room_id' => 1,
                'service_id' => 10
            ],
            [
                'room_id' => 2,
                'service_id' => 1
            ],
            [
                'room_id' => 2,
                'service_id' => 4
            ],
            [
                'room_id' => 2,
                'service_id' => 6
            ],
            [
                'room_id' => 2,
                'service_id' => 9
            ],
            [
                'room_id' => 2,
                'service_id' => 10
            ],
            [
                'room_id' => 3,
                'service_id' => 1
            ],
            [
                'room_id' => 3,
                'service_id' => 3
            ],
            [
                'room_id' => 3,
                'service_id' => 6
            ],
            [
                'room_id' => 3,
                'service_id' => 9
            ],
            [
                'room_id' => 3,
                'service_id' => 10
            ],
            [
                'room_id' => 4,
                'service_id' => 1
            ],
            [
                'room_id' => 4,
                'service_id' => 6
            ],
            [
                'room_id' => 4,
                'service_id' => 8
            ],
            [
                'room_id' => 4,
                'service_id' => 10
            ],
            [
                'room_id' => 5,
                'service_id' => 7
            ],
            [
                'room_id' => 5,
                'service_id' => 8
            ],
            [
                'room_id' => 5,
                'service_id' => 10
            ]
        ];
        parent::init();
    }
}
