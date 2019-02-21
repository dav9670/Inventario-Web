<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProductsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\ProductsController Test Case
 */
class ProductsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Products',
        'app.Licences',
        'app.LicencesProducts',
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

        $productsTable = TableRegistry::get('Products');
        $expectedProducts = $productsTable->find('all')
            ->where('id = 1 or id = 5')
            ->order(['name' => 'asc'])
            ->toList();

        $this->get('/products/search.json?keyword=acce&sort_field=name&sort_dir=asc');

        $receivedProductsDecoded = json_decode((string)$this->_response->getBody());
        $receivedProducts = [];

        foreach($receivedProductsDecoded->products as $product){
            array_push($receivedProducts, $productsTable->get($product->id));
        }

        $this->assertEquals($expectedProducts, $receivedProducts);

        //Negative test
        $productsTable = TableRegistry::get('Products');

        $this->get('/products/search.json?keyword=zzzzzzzzzzzzzzzzz&sort_field=name&sort_dir=asc');

        $receivedProductsDecoded = json_decode((string)$this->_response->getBody());

        $this->assertCount(0, $receivedProductsDecoded->products);
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
            'id' => 13,
            'name' => 'VMWare',
            'platform' => 'Mac',
            'description' => 'Virtual Machine software, for Mac.'
        ];

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 13])->first();
        $this->assertNull($product);

        $this->post('/products/add', $post);

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 13])->first();
        $this->assertNotNull($product);


        //Negative test
        $post = [
            'id' => 14,
            'name' => 'MS Word',
            'platform' => 'Mac',
            'description' => 'This is a name/platform combination that is already taken.'
        ];

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 14])->first();
        $this->assertNull($product);

        $this->post('/products/add', $post);

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 14])->first();
        $this->assertNull($product);
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
            'name' => 'VMWare',
            'platform' => 'Mac',
            'description' => 'Virtual Machine software, for Mac.'
        ];

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 1])->first();
        $this->assertNotNull($product);
        $this->assertEquals('MS Access', $product->name);
        $this->assertEquals('Mac', $product->platform);
        $this->assertEquals('Database software developed by Microsoft, for Mac.', $product->description);

        $this->post('/products/1', $post);

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 1])->first();
        $this->assertNotNull($product);
        $this->assertEquals('VMWare', $product->name);
        $this->assertEquals('Mac', $product->platform);
        $this->assertEquals('Virtual Machine software, for Mac.', $product->description);


        //Negative test
        $post = [
            'id' => 2,
            'name' => 'MS Word',
            'platform' => 'Mac',
            'description' => 'This is a name/platform combination that is already taken.'
        ];

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 2])->first();
        $this->assertNotNull($product);
        $this->assertEquals('MS Excel', $product->name);
        $this->assertEquals('Mac', $product->platform);
        $this->assertEquals('Spreadsheet software developed by Microsoft, for Mac.', $product->description);

        $this->post('/products/2', $post);

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 2])->first();
        $this->assertNotNull($product);
        $this->assertEquals('MS Excel', $product->name);
        $this->assertEquals('Mac', $product->platform);
        $this->assertEquals('Spreadsheet software developed by Microsoft, for Mac.', $product->description);
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

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 1])->first();
        $this->assertNotNull($product);

        $this->post('/products/delete/1', $post);

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => 1])->first();
        $this->assertNull($product);


        //Negative test
        $post = [
            'id' => -1
        ];

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => -1])->first();
        $this->assertNull($product);

        $this->post('/products/delete/-1', $post);

        $products = TableRegistry::get('Products');
        $product = $products->find()->where(['id' => -1])->first();
        $this->assertNull($product);

        $this->assertResponseError();
    }
}
