<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SkillsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\SkillsController Test Case
 */
class SkillsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Skills',
        'app.Mentors',
        'app.MentorsSkills'
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
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        //Positive test
        $post = [
            'id' => 11,
            'name' => 'Memeology',
            'description' => 'The science of memes.'
        ];

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 11])->first();
        $this->assertNull($skill);

        $this->post('/skills/add', $post);

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 11])->first();
        $this->assertNotNull($skill);


        //Negative test
        $post = [
            'id' => 12,
            'name' => 'Physics',
            'description' => 'This is a name that is already taken.'
        ];

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 12])->first();
        $this->assertNull($skill);

        $this->post('/skills/add', $post);

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 12])->first();
        $this->assertNull($skill);
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
            'name' => 'Memeology',
            'description' => 'The science of memes.'
        ];

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 1])->first();
        $this->assertNotNull($skill);
        $this->assertEquals('Html', $skill->name);
        $this->assertEquals('Hypertext Markup Language. Used to make the graphic structure of a website.', $skill->description);

        $this->post('/skills/1', $post);

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 1])->first();
        $this->assertNotNull($skill);
        $this->assertEquals('Memeology', $skill->name);
        $this->assertEquals('The science of memes.', $skill->description);


        //Negative test
        $post = [
            'id' => 2,
            'name' => 'Physics',
            'description' => 'This is a name that is already taken.'
        ];

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 2])->first();
        $this->assertNotNull($skill);
        $this->assertEquals('Javascript', $skill->name);
        $this->assertEquals('Scripting language used mainly in web development. Can also be used to develop desktop applications.', $skill->description);

        $this->post('/skills/2', $post);

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 2])->first();
        $this->assertNotNull($skill);
        $this->assertEquals('Javascript', $skill->name);
        $this->assertEquals('Scripting language used mainly in web development. Can also be used to develop desktop applications.', $skill->description);
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

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 1])->first();
        $this->assertNotNull($skill);

        $this->post('/skills/delete/1', $post);

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => 1])->first();
        $this->assertNull($skill);


        //Negative test
        $post = [
            'id' => -1
        ];

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => -1])->first();
        $this->assertNull($skill);

        $this->post('/skills/delete/-1', $post);

        $skills = TableRegistry::get('Skills');
        $skill = $skills->find()->where(['id' => -1])->first();
        $this->assertNull($skill);

        $this->assertResponseError();
    }
}
