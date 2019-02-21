<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EquipmentsCategoriesFixture
 *
 */
class EquipmentsCategoriesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'equipment_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'category_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'equipments_categories_category_key' => ['type' => 'index', 'columns' => ['category_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['equipment_id', 'category_id'], 'length' => []],
            'equipments_categories_category_key' => ['type' => 'foreign', 'columns' => ['category_id'], 'references' => ['categories', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'equipments_categories_equipment_key' => ['type' => 'foreign', 'columns' => ['equipment_id'], 'references' => ['equipments', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'equipment_id' => 1,
                'category_id' => 1
            ],
            [
                'equipment_id' => 1,
                'category_id' => 4
            ],
            [
                'equipment_id' => 1,
                'category_id' => 10
            ],
            [
                'equipment_id' => 2,
                'category_id' => 1
            ],
            [
                'equipment_id' => 2,
                'category_id' => 4
            ],
            [
                'equipment_id' => 2,
                'category_id' => 9
            ],
            [
                'equipment_id' => 3,
                'category_id' => 1
            ],
            [
                'equipment_id' => 3,
                'category_id' => 4
            ],
            [
                'equipment_id' => 4,
                'category_id' => 2
            ],
            [
                'equipment_id' => 4,
                'category_id' => 4
            ],
            [
                'equipment_id' => 5,
                'category_id' => 2
            ],
            [
                'equipment_id' => 5,
                'category_id' => 4
            ]
        ];
        parent::init();
    }
}
