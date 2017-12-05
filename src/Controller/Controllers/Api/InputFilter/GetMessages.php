<?php

namespace Lisd\Controller\Controllers\Api\InputFilter;

use Zend\InputFilter\InputFilter;

class GetMessages extends InputFilter
{

    public function init()
    {
        $this->add([
            'name' => 'since',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Digits'
                ]
            ]
        ]);

    }

}
