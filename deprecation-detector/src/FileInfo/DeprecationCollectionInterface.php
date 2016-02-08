<?php

namespace SensioLabs\DeprecationDetector\FileInfo;

use SensioLabs\DeprecationDetector\FileInfo\Deprecation\ClassDeprecation;
use SensioLabs\DeprecationDetector\FileInfo\Deprecation\FunctionDeprecation;
use SensioLabs\DeprecationDetector\FileInfo\Deprecation\InterfaceDeprecation;
use SensioLabs\DeprecationDetector\FileInfo\Deprecation\MethodDeprecation;

interface DeprecationCollectionInterface
{
    /**
     * @return ClassDeprecation[]
     */
    public function classDeprecations();

    /**
     * @return InterfaceDeprecation[]
     */
    public function interfaceDeprecations();

    /**
     * @return MethodDeprecation[]
     */
    public function methodDeprecations();

    /**
     * @return FunctionDeprecation[]
     */
    public function functionDeprecations();
}
