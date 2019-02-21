<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CategoriesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\CategoriesController Test Case
 */
class CategoriesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Categories',
        'app.Equipments',
        'app.EquipmentsCategories',
        'app.Loans'
    ];


    public function setUp()
    {
        parent::setUp();

        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // Set session data
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'david@gmail.com',
                    'password' => '$2y$10$Cx3OagaMhBWn64l.MiHpRu36oBdp7J3GCg/cjHpKfTY8CVBxWzloy',
                    'admin_status' => 'admin'
                ]
            ]
        ]);
    }

    /**
     * Test search method
     *
     * @return void
     */
    public function testSearch()
    {
        //Positive test

        $categoriesTable = TableRegistry::get('Categories');
        $expectedCategories = $categoriesTable->find('all')
            ->where('name like "Computer" or name like "Electronic"')
            ->order(['name' => 'asc'])
            ->toList();

        $this->get('/categories/search.json?keyword=electro&sort_field=name&sort_dir=asc');

        $receivedCategoriesDecoded = json_decode((string)$this->_response->getBody());
        $receivedCategories = [];

        foreach($receivedCategoriesDecoded->categories as $category){
            array_push($receivedCategories, $categoriesTable->get($category->id));
        }

        $this->assertEquals($expectedCategories, $receivedCategories);

        //Negative test
        $categoriesTable = TableRegistry::get('Categories');

        $this->get('/categories/search.json?keyword=zzzzzzzzzzzzzzzzz&sort_field=name&sort_dir=asc');

        $receivedCategoriesDecoded = json_decode((string)$this->_response->getBody());

        $this->assertCount(0, $receivedCategoriesDecoded->categories);
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        //Positive test
        $post = [
            'id' => 11,
            'name' => 'Tablet',
            'description' => 'A phone but bigger.',
            'hourly_rate' => 4.00
        ];

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 11])->first();
        $this->assertNull($category);

        $this->post('/categories/add', $post);

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 11])->first();
        $this->assertNotNull($category);


        //Negative test
        $post = [
            'id' => 12,
            'name' => 'Electric',
            'description' => 'This is a name that is already taken.',
            'hourly_rate' => 4.00
        ];

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 12])->first();
        $this->assertNull($category);

        $this->post('/categories/add', $post);

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 12])->first();
        $this->assertNull($category);
    }

    /**
     * Test consult method
     *
     * @return void
     */
    public function testConsult()
    {
        //Positive test
        $post = [
            'id' => 1,
            'name' => 'Tablet',
            'description' => 'A phone but bigger.',
            'hourly_rate' => 4.00
        ];

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 1])->first();
        $this->assertNotNull($category);
        $this->assertEquals('Computer', $category->name);
        $this->assertEquals('An electronical device now use in everyday life.', $category->description);
        $this->assertEquals(15.20, $category->hourly_rate);

        $this->post('/categories/1', $post);

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 1])->first();
        $this->assertNotNull($category);
        $this->assertEquals('Tablet', $category->name);
        $this->assertEquals('A phone but bigger.', $category->description);
        $this->assertEquals(4.00, $category->hourly_rate);


        //Negative test
        $post = [
            'id' => 2,
            'name' => 'Electric',
            'description' => 'This is a name that is already taken.',
            'hourly_rate' => 4.00
        ];

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 1])->first();
        $this->assertNotNull($category);
        $this->assertEquals('Phone', $category->name);
        $this->assertEquals('Used to call someone, phone is now more a micro computer than a phone.', $category->description);
        $this->assertEquals(13.33, $category->hourly_rate);

        $this->post('/categories/2', $post);

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 1])->first();
        $this->assertNotNull($category);
        $this->assertEquals('Phone', $category->name);
        $this->assertEquals('Used to call someone, phone is now more a micro computer than a phone.', $category->description);
        $this->assertEquals(13.33, $category->hourly_rate);
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        //Positive test
        $post = [
            'id' => 1
        ];

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 1])->first();
        $this->assertNotNull($category);

        $this->post('/categories/delete/1', $post);

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => 1])->first();
        $this->assertNull($category);


        //Negative test
        $post = [
            'id' => -1
        ];

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => -1])->first();
        $this->assertNull($category);

        $this->post('/categories/delete/-1', $post);

        $categories = TableRegistry::get('Categories');
        $category = $categories->find()->where(['id' => -1])->first();
        $this->assertNull($category);

        $this->assertResponseError();
    }
}
