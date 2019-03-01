<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EquipmentsFixture
 *
 */
class EquipmentsFixture extends TestFixture
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
        'image' => ['type' => 'text', 'length' => 16777215, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'name' => ['type' => 'fulltext', 'columns' => ['name', 'description'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
                'name' => 'Computer 1',
                'description' => 'OS: 64-bit Windows 7 or 64-bit Windows 8 (8.1). Processor: AMD CPU AMD FX-8350 4 GHz Graphics: AMD GPU Radeon R9 290 RAM: 6GB Disk space: 400 GB.',
                'image' => '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',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ],
            [
                'id' => 2,
                'name' => 'Computer 2',
                'description' => 'OS: 64-bit Windows 7 or 64-bit Windows 8 (8.1). Processor: AMD CPU AMD FX-8350 4 GHz Graphics: AMD GPU Radeon R9 290 RAM: 6GB Disk space: 400 GB.',
                'image' => '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',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ],
            [
                'id' => 3,
                'name' => 'Computer 3',
                'description' => 'OS: 64-bit Windows 7 or 64-bit Windows 8 (8.1). Processor: AMD CPU AMD FX-8350 4 GHz Graphics: AMD GPU Radeon R9 290 RAM: 6GB Disk space: 400 GB.',
                'image' => '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',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ],
            [
                'id' => 4,
                'name' => 'iPhone 1',
                'description' => 'This is an Iphone 8.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAAEBAQQEBAcHBwgICAkJCQoKChAQEBERERISEhQUFBcXFxgYGBkZGRoaGhsbGxwcHB0dHR8fHyAgICIiIiMjIyQkJCYmJigoKCkpKSoqKiwsLC4uLi8vLzAwMDExMTIyMjMzMzQ0NDU1NTY2Njc3Nzg4ODk5OTo6Ojs7Ozw8PD09PT4+Pj8/P0BAQEFBQUNDQ0REREVFRUZGRkdHR0hISElJSUpKSk1NTU5OTk9PT1BQUFFRUVJSUlNTU1RUVFVVVVZWVldXV1hYWFlZWVpaWltbW1xcXF1dXV5eXl9fX2BgYGFhYWJiYmNjY2RkZGVlZWdnZ2hoaGlpaWpqamtra2xsbG1tbW5ubnBwcHFxcXJycnNzc3R0dHV1dXZ2dnd3d3h4eHl5eXp6ent7e3x8fH5+fn9/f4CAgIGBgYKCgoODg4SEhIWFhYaGhoeHh4iIiIuLi42NjY+Pj5CQkJGRkZKSkpSUlJaWlpeXl5iYmJmZmZqampubm5ycnJ6enqCgoKGhoaKioqOjo6SkpKWlpaampqenp6ioqKqqqqurq6ysrK+vr7GxsbKysrOzs7S0tLW1tba2tre3t7m5ubq6uru7u7y8vL29vb6+vr+/v8DAwMHBwcLCwsPDw8TExMXFxcbGxsfHx8jIyMnJycrKysvLy8zMzM3Nzc7Ozs/Pz9DQ0NHR0dLS0tTU1NXV1dbW1tfX19jY2NnZ2dra2tvb29zc3N3d3d7e3t/f3+Dg4OHh4eLi4uPj4+Tk5OXl5ebm5ufn5+jo6Onp6erq6uvr6+zs7O3t7e7u7u/v7/Dw8PHx8fLy8vPz8/T09PX19fb29vf39/j4+Pn5+fr6+vv7+/z8/P39/f7+/v///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAPbfpjUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAYdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjEuNWRHWFIAAAPnSURBVGhD7df5UxNnGMBxq61HQcUDtdhoSzxaiEhRUQsqaG0RTFHAUg7RKIpYJVLFAwMSUCOVWhXE2iIB5ZQCFgyXAWoSA2UNWSAJ+/w37ph3CG2WJO8mOuPM+/kleffZyXd2NrubTIN3gESwkAgWEsFCIlhIBAuJYCERLCSC5T2KmNHrFDwRMZXnjIMFLbh45EhkkbLfytF7Lp6IMPJPl8euGEMrDu5HhsF8wtcri0JLLu5G+ovOFnfcO7kxE605uRdp67y2elpAGlBhYX1oExc3IuMwdnt1wGnNIWEDyHbJmWG03R7vSEd5g5apn7/vBSijU6FLLNIWqtHIDs+IpSJz/fS0OvWBbw1AnV2kBXnI7O970NAOz8izxHTFRUH8SPkH7RYo25oNA5W1/6CZPfxIV616DO5ueVWfHxStVoWnG6E3PZjdPm4dc8GNMC2JIkE9c9XvUFrswd9NQ3KfQYCpj8EKK2IoK63b63NibVzznZBPjleBSmlsCaxm0HRqGJFew8313oE7n0LhqjxtnG+bEaSRt2knN+A3MCK5qUuiLm1by37q5uTu+zu+Tpgf6uiuOImTyEvry1BTnx5KFvwAcGPpI4DLEcV0a+7Rar2Dkz2Zk4hUoQGguk9tFaYDCI72Q+PeeADNl5JX7APExYSzyMuMAsb8V+bJD3cnBRWacjYpwZDnzX6XiurQDq5xHBkfvdE4WvFZ0ANokwi11Kx8Ch6GZqCh6xxGVHo4k9SqSloxBKBceB+So56A7tG/aOo6R5FmydWxkgV3oNinCqAuPBuaPlY6vyg4TBWhu9gHUkQcPSA80vMkOtYyVCP8g71loSkm7oh55FbCCEBWdJkxx//P4fyPtp9Zs4VngcUVYRTNdHF4EUBtxDFG53VOVxni/2Oegx8KzthFTJWya6Exne0p29jFgZgGQ+rmWo3UC4zWMS92EVXUvM83zqk25254AFAYcM7ctLgFSlcWoDEv/4no2Rttmu8tKBMd6avZLx7UpwhiNaZRC3SkiNAuvNgi5sc/S67oGfHuHhi84Ndgks+VfBeY12Edtjv6WeWULaKSJq0THdbJ/HUAFT7n9WpZeEYvmrlpItIvjvm7fd+sZ8qF1yk6Z+ZXrWAChtelZ28i0vfFpV+l8d80dqcEX6yISZxx04AGHjARoTYFiyPPt9IWwy7BvJ9qIvegs+EJE5GB7NlZL4BWdEJPNU1TikVVLj8unLKd+KfLMmoaE/xKravnJe5cff9ji4z+EhfmnaxCK4+yRYBRPwSjo39lvE2KvD0kgoVEsJAIFhLBQiJYSAQLiWAhESwkgoVEsJAIFhLBAPAa0btOcDFXRvUAAAAASUVORK5CYII=',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ],
            [
                'id' => 5,
                'name' => 'iPhone 2',
                'description' => 'This is an Iphone 8.',
                'image' => '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',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ],
            [
                'id' => 6,
                'name' => 'Data integration for dummies',
                'description' => 'his book will learn you how deal with data integration',
                'image' => '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',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ],
            [
                'id' => 7,
                'name' => 'Informatica',
                'description' => 't helps you to learn informatica',
                'image' => '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',
                'created' => '2019-02-16 17:18:33',
                'modified' => '2019-02-16 17:18:33'
            ]
        ];
        parent::init();
    }

    public function create($db)
    {
        parent::create($db);
        $db->execute("
        drop procedure if exists equipments_report;
        create procedure equipments_report(start datetime, end datetime, sort_field tinytext, sort_dir tinytext)
        begin
            set @query =concat('
            select c.name as \"cat\", nloans.nloaned as \"time_loans\", nlate.late as \"late_loans\", nlate.hlate as \"hour_loans\"
            from 
                equipments e,
                equipments_categories ec,
                categories c left join 
                (
                    select c.name as cat, count(e.id) as late,timestampdiff(HOUR, l.end_time, ifnull(l.returned, \"', start ,'\")) as hlate
                    from loans l, equipments e, equipments_categories ec, categories c
                    where e.id = ec.equipment_id
                        and c.id = ec.category_id
                        and e.id = l.item_id
                        and l.item_type like \"equipments\" 
                        and l.end_time <= \"', start ,'\"
                        and returned is null
                        group by c.name
                ) as nlate on c.name = nlate.cat
               left join (
                    select c.name as cat, count(e.id) as nloaned
                    from loans as l inner join equipments e on l.item_id = e.id, categories c, equipments_categories ec
                    where 
                        e.id = ec.equipment_id
                        and c.id = ec.category_id 
                        and l.item_type like \"equipments\" 
                        and l.start_time >= \"', start ,'\"
                        and l.end_time <= \"', end ,'\"
                        group by c.name
                ) as nloans on c.name = nloans.cat
                where 
                    e.id = ec.equipment_id 
                    and c.id = ec.category_id
                    group by c.name
                    order by ', sort_field ,' ', sort_dir);
        
            PREPARE stmt FROM @query;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
                    
        end;", 
            array('log' => false));
        
    }
}
