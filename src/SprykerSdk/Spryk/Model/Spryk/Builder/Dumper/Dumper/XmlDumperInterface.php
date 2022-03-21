<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper;

interface XmlDumperInterface
{
    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface> $resolvedFiles
     *
     * @return void
     */
    public function dump(array $resolvedFiles): void;
}
