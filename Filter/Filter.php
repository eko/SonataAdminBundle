<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Filter;

use Application\Sonata\DatagridBundle\Filter\BaseFilter;

abstract class Filter extends BaseFilter
{
    /**
     * {@inheritdoc}
     */
    public function getParentAssociationMappings()
    {
        return $this->getOption('parent_association_mappings', array());
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldMapping()
    {
        $fieldMapping = $this->getOption('field_mapping');

        if (!$fieldMapping) {
            throw new \RunTimeException(sprintf('The option `field_mapping` must be set for field : `%s`', $this->getName()));
        }

        return $fieldMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationMapping()
    {
        $associationMapping = $this->getOption('association_mapping');

        if (!$associationMapping) {
            throw new \RunTimeException(sprintf('The option `association_mapping` must be set for field : `%s`', $this->getName()));
        }

        return $associationMapping;
    }
}
