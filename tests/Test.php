<?php

/**
 * Created by PhpStorm.
 * User: bybcr
 * Date: 15.4.2017
 * Time: 01:39
 */
class Test extends PHPUnit_Framework_TestCase
{
    public function testSample(){

        $data = array(
            'tcNo'          => '12345678901',
            'name'          => 'Murat',
            'surName'       => 'AYGÜN',
            'birthyear'     => '1994',
        );

        $confirm = \murataygun\TcKimlik::confirm("12345678901");
        $this->assertFalse($confirm);
        $confirm1 = \murataygun\TcKimlik::confirm($data);
        $this->assertFalse($confirm1);
        $validate = \murataygun\TcKimlik::confirm($data);
        $this->assertFalse($validate);

    }
}
