<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LicencesProductsFixture
 *
 */
class LicencesProductsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'licence_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'licences_products_product_key' => ['type' => 'index', 'columns' => ['product_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['licence_id', 'product_id'], 'length' => []],
            'licences_products_licence_key' => ['type' => 'foreign', 'columns' => ['licence_id'], 'references' => ['licences', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'licences_products_product_key' => ['type' => 'foreign', 'columns' => ['product_id'], 'references' => ['products', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'licence_id' => 1,
                'product_id' => 1
            ],
            [
                'licence_id' => 1,
                'product_id' => 2
            ],
            [
                'licence_id' => 1,
                'product_id' => 3
            ],
            [
                'licence_id' => 1,
                'product_id' => 4
            ],
            [
                'licence_id' => 2,
                'product_id' => 1
            ],
            [
                'licence_id' => 2,
                'product_id' => 2
            ],
            [
                'licence_id' => 2,
                'product_id' => 3
            ],
            [
                'licence_id' => 2,
                'product_id' => 4
            ],
            [
                'licence_id' => 3,
                'product_id' => 1
            ],
            [
                'licence_id' => 3,
                'product_id' => 2
            ],
            [
                'licence_id' => 3,
                'product_id' => 3
            ],
            [
                'licence_id' => 3,
                'product_id' => 4
            ],
            [
                'licence_id' => 4,
                'product_id' => 5
            ],
            [
                'licence_id' => 4,
                'product_id' => 6
            ],
            [
                'licence_id' => 4,
                'product_id' => 7
            ],
            [
                'licence_id' => 4,
                'product_id' => 8
            ],
            [
                'licence_id' => 5,
                'product_id' => 5
            ],
            [
                'licence_id' => 5,
                'product_id' => 6
            ],
            [
                'licence_id' => 5,
                'product_id' => 7
            ],
            [
                'licence_id' => 5,
                'product_id' => 8
            ],
            [
                'licence_id' => 6,
                'product_id' => 9
            ],
            [
                'licence_id' => 6,
                'product_id' => 10
            ],
            [
                'licence_id' => 7,
                'product_id' => 9
            ],
            [
                'licence_id' => 7,
                'product_id' => 10
            ]
        ];
        parent::init();
    }
}
