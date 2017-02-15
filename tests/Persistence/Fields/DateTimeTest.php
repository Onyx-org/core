<?php

namespace Onyx\Persistence\Fields;

class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    private
        $field;

    protected function setUp()
    {
        $this->field = new DateTime('test', DateTime::MYSQL_FORMAT);
    }

    public function testConvertNull()
    {
        $convertedValue = $this->field->convert(null);

        $this->assertSame($convertedValue, null);
    }

    /**
     * @dataProvider providerTestConvert
     */
    public function testConvert($value, \DateTime $expected = null)
    {
        $convertedValue = $this->field->convert($value);

        $this->assertSame($expected->getTimestamp(), $convertedValue->getTimestamp());
    }

    /**
     * @dataProvider providerTestConvertWithExceptions
     * @expectedException \Onyx\Persistence\Exceptions\InvalidDataException
     */
    public function testConvertWithExceptions($value)
    {
        $this->field->convert($value);
    }

    public function providerTestConvert()
    {
        return array(
            'Valid DateTime' => array($this->getDate('2014-09-05 10:17:00'), $this->getDate('2014-09-05 10:17:00')),
            'Valid date string' => array('2014-09-05 10:17:00', $this->getDate('2014-09-05 10:17:00'))
        );
    }

    public function providerTestConvertWithExceptions()
    {
        return array(
            'Invalid type - array' => array(array()),
            'Invalid type - int' => array(3),
            'Invalid format - "test"' => array('test'),
            'Invalid format - "01-01-2014 00:00:00"' => array('01-01-2014 00:00:00'),
            'Invalid format - "2014/01/01 00:00:00"' => array('2014/01/01 00:00:00'),
            'Strict validity check - "2014-13-01 00:00:00"' => array('2014-13-01 00:00:00'),
            'Strict validity check - "2014-01-32 00:00:00"' => array('2014-01-32 00:00:00'),
            'Strict validity check - "2014-01-01 25:00:00"' => array('2014-01-01 25:00:00'),
            'Strict validity check - "2014-01-01 00:60:00"' => array('2014-01-01 00:60:00'),
            'Strict validity check - "2014-01-01 00:00:60"' => array('2014-01-01 00:00:60')
        );
    }

    private function getDate($dateString)
    {
        $date = \DateTime::createFromFormat(DateTime::MYSQL_FORMAT, $dateString);

        return $date;
    }
}
