<?php

namespace SensioLabs\DeprecationDetector\RuleSet\Loader;

use SensioLabs\DeprecationDetector\RuleSet\RuleSet;

/**
 * Interface LoaderInterface.
 *
 * @author Christopher Hertel <christopher.hertel@sensiolabs.de>
 */
interface LoaderInterface
{
    /**
     * @param string $path
     *
     * @return RuleSet
     *
     * @throws CouldNotLoadRuleSetException
     */
    public function loadRuleSet($path);
}
