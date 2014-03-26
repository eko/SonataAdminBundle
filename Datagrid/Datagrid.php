<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Datagrid;

use Application\Sonata\DatagridBundle\Datagrid\Datagrid as BaseDatagrid;
use Application\Sonata\DatagridBundle\Pager\PagerInterface as BasePagerInterface;
use Application\Sonata\DatagridBundle\ProxyQuery\ProxyQueryInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\CallbackTransformer;

class Datagrid extends BaseDatagrid
{
    /**
     * @var FieldDescriptionCollection
     */
    protected $columns;

    /**
     * @param ProxyQueryInterface        $query
     * @param FieldDescriptionCollection $columns
     * @param BasePagerInterface         $pager
     * @param FormBuilder                $formBuilder
     * @param array                      $values
     */
    public function __construct(ProxyQueryInterface $query, FieldDescriptionCollection $columns, BasePagerInterface $pager, FormBuilder $formBuilder, array $values = array())
    {
        parent::__construct($query, $pager, $formBuilder, $values);

        $this->columns = $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPager()
    {
        if ($this->bound) {
            return;
        }

        foreach ($this->getFilters() as $name => $filter) {
            list($type, $options) = $filter->getRenderSettings();

            $this->formBuilder->add($filter->getFormName(), $type, $options);
        }

        $this->formBuilder->add('_sort_by', 'hidden');
        $this->formBuilder->get('_sort_by')->addViewTransformer(new CallbackTransformer(
            function($value) { return $value; },
            function($value) { return $value instanceof FieldDescriptionInterface ? $value->getName() : $value; }
        ));

        $this->formBuilder->add('_sort_order', 'hidden');
        $this->formBuilder->add('_page', 'hidden');
        $this->formBuilder->add('_per_page', 'hidden');

        $this->form = $this->formBuilder->getForm();
        $this->form->submit($this->values);

        $data = $this->form->getData();

        foreach ($this->getFilters() as $name => $filter) {
            $this->values[$name] = isset($this->values[$name]) ? $this->values[$name] : null;
            $filter->apply($this->query, $data[$filter->getFormName()]);
        }

        if (isset($this->values['_sort_by'])) {
            if (!$this->values['_sort_by'] instanceof FieldDescriptionInterface) {
                throw new UnexpectedTypeException($this->values['_sort_by'],'FieldDescriptionInterface');
            }

            if ($this->values['_sort_by']->isSortable()) {
                $this->query->setSortBy(array(
                    'parentAssociationMapping' => $this->values['_sort_by']->getSortParentAssociationMapping(),
                    'fieldMapping'             => $this->values['_sort_by']->getSortFieldMapping()
                ));
                $this->query->setSortOrder(isset($this->values['_sort_order']) ? $this->values['_sort_order'] : null);
            }
        }

        $this->pager->setMaxPerPage(isset($this->values['_per_page']) ? $this->values['_per_page'] : 25);
        $this->pager->setPage(isset($this->values['_page']) ? $this->values['_page'] : 1);
        $this->pager->setQuery($this->query);
        $this->pager->init();

        $this->bound = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
