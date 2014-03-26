<?php
/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Datagrid;

use Application\Sonata\DatagridBundle\ProxyQuery\ProxyQueryInterface as BaseProxyQueryInterface;

/**
 * Interface used by the Datagrid to build the query
 */
interface ProxyQueryInterface extends BaseProxyQueryInterface
{
    /**
     * @return mixed
     */
    public function getSingleScalarResult();

    /**
     * @return mixed
     */
    public function getUniqueParameterId();

    /**
     * @param array $associationMappings
     *
     * @return mixed
     */
    public function entityJoin(array $associationMappings);
}
