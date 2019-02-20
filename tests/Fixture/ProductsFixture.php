<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductsFixture
 *
 */
class ProductsFixture extends TestFixture
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
        'platform' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'name' => ['type' => 'fulltext', 'columns' => ['name', 'platform', 'description'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'name_2' => ['type' => 'unique', 'columns' => ['name', 'platform'], 'length' => []],
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
                'name' => 'MS Access',
                'platform' => 'Mac',
                'description' => 'Database software developed by Microsoft, for Mac.'
            ],
            [
                'id' => 2,
                'name' => 'MS Excel',
                'platform' => 'Mac',
                'description' => 'Spreadsheet software developed by Microsoft, for Mac.'
            ],
            [
                'id' => 3,
                'name' => 'MS PowerPoint',
                'platform' => 'Mac',
                'description' => 'Slideshow software developed by Microsoft, for Mac.'
            ],
            [
                'id' => 4,
                'name' => 'MS Word',
                'platform' => 'Mac',
                'description' => 'Document writing software developed by Microsoft, for Mac.'
            ],
            [
                'id' => 5,
                'name' => 'MS Access',
                'platform' => 'Windows 10',
                'description' => 'Database software developed by Microsoft, for Windows 10.'
            ],
            [
                'id' => 6,
                'name' => 'MS Excel',
                'platform' => 'Windows 10',
                'description' => 'Spreadsheet software developed by Microsoft, for Windows 10.'
            ],
            [
                'id' => 7,
                'name' => 'MS PowerPoint',
                'platform' => 'Windows 10',
                'description' => 'Slideshow software developed by Microsoft, for Windows 10.'
            ],
            [
                'id' => 8,
                'name' => 'MS Word',
                'platform' => 'Windows 10',
                'description' => 'Document writing software developed by Microsoft, for Windows 10.'
            ],
            [
                'id' => 9,
                'name' => 'Mockups',
                'platform' => 'Mac',
                'description' => 'Mockup software developed by Balsamiq, for Mac.'
            ],
            [
                'id' => 10,
                'name' => 'Wireframes',
                'platform' => 'Mac',
                'description' => 'Wireframing software developed by Balsamiq, for Mac.'
            ],
            [
                'id' => 11,
                'name' => 'Mockups',
                'platform' => 'Windows 10',
                'description' => 'Mockup software developed by Balsamiq, for Windows 10.'
            ],
            [
                'id' => 12,
                'name' => 'Wireframes',
                'platform' => 'Windows 10',
                'description' => 'Wireframing software developed by Balsamiq, for Windows 10.'
            ]
        ];
        parent::init();
    }
}
