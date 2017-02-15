<?php

namespace Onyx\Persistence\DTOHydrators;

use Onyx\Persistence\Fields;
use Onyx\Persistence\TestDto;
use Onyx\Persistence\DTOHydrator;

require_once __DIR__ . '/../TestDto.php';
require_once __DIR__ . '/Transformers.php';

class ByFieldTest extends \PHPUnit\Framework\TestCase
{
    public function testHydrate()
    {
        $dataset = array
        (
            'stringField' => 'myString',
            'sub_array1' =>
            [
                'unused_field' => 5.2,
                'integer_field' => '42'
            ],
            'boolean_field_array' =>  [true, false]
        );

        $hydrator = new ByField($this->getFields());
        $hydrator->enableExceptions();

        $dto = $this->hydrate($hydrator, $dataset);

        $this->assertSame('myString', $dto->stringField);
        $this->assertSame(42, $dto->integerField);
        $this->assertSame(array(true, false), $dto->booleanFieldArray);
    }

    /**
     * @dataProvider providerTestErrors
     */
    public function testHydrateNull($dataset)
    {
        $hydrator = new ByField($this->getFields());
        $hydrator->disableExceptions();

        $dto = $this->hydrate($hydrator, $dataset);

        $this->assertNull($dto);
    }

    /**
     * @dataProvider providerTestErrors
     * @expectedException \Onyx\Persistence\Exceptions\InvalidDataException
     */
    public function testHydrateWithExceptions(array $dataset)
    {
        $hydrator = new ByField($this->getFields());
        $hydrator->enableExceptions();

        $this->hydrate($hydrator, $dataset);
    }

    public function providerTestErrors()
    {
        return array
        (
            'Invalid string' => array(array('stringField' => 7337)),
            'Invalid integer' => array(array('sub_array1' => ['integer_field' => 7337])),
            'Invalid array' => array(array('boolean_field_array' => 'toto')),
            'Invalid array item type' => array(array('boolean_field_array' => ['toto'])),
            'Invalid path' => array(array('boolean_field_array' => ['toto'])),
        );
    }

    /**
     * @dataProvider providerTestValueTransformer
     */
    public function testValueTransformer(array $transformers, array $expected)
    {
        $dataset = array
        (
            'stringField' => 'myString',
            'sub_array1' =>
            [
                'unused_field' => 5.2,
                'integer_field' => '42'
            ],
            'boolean_field_array' =>  [true, false]
        );

        $hydrator = new ByField($this->getFields());
        $hydrator->enableExceptions();

        foreach($transformers as $transformer)
        {
            $hydrator->addValueTransformer($transformer);
        }

        $dto = $this->hydrate($hydrator, $dataset);

        $this->assertSame($expected[0], $dto->stringField);
        $this->assertSame($expected[1], $dto->integerField);
        $this->assertSame($expected[2], $dto->booleanFieldArray);

        $hydrator = new ByField($this->getFields());
        $hydrator->enableExceptions();
    }

    public function providerTestValueTransformer()
    {
        return array(
            'leet' =>  array(
                array(new LeetTransformer()),
                array('myString', 42, array(true, false)),
            ),
            'opposite' => array(
                array(new OppositeNumberTransformer()),
                array('myString', -42, array(true, false)),
            ),
            'uppercase then leet' => array(
                array(new UppercaseTransformer(), new LeetTransformer()),
                array('MYS7R1NG', 42, array(true, false)),
            ),
            'leet then uppercase then opposite' => array(
                array(new LeetTransformer(), new UppercaseTransformer(), new OppositeNumberTransformer()),
                array('MYSTRING', -42, array(true, false)),
            ),
            'opposite then leet then uppercase' => array(
                array(new OppositeNumberTransformer(), new LeetTransformer(), new UppercaseTransformer()),
                array('MYSTRING', -42, array(true, false)),
            ),
        );
    }

    private function getFields()
    {
        return array
        (
            'stringField' => new Fields\StringField('stringField'),
            'integerField' => new Fields\IntegerField(array('sub_array1', 'integer_field')),
            'booleanFieldArray' => new Fields\ArrayField('boolean_field_array', new Fields\Boolean())
        );
    }

    private function hydrate(DTOHydrator $dtoHydrator, array $dataset)
    {
        $dto = new TestDto();
        $dto = $dtoHydrator->hydrate($dto, $dataset);

        return $dto;
    }
}
