<?php
declare(strict_types=1);

namespace App\Model\Filter;

use Search\Model\Filter\FilterCollection;
use Cake\ORM\TableRegistry;

class SamplesCollection extends FilterCollection
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $alias = $this->_manager->getRepository()->getAlias();

        $table = TableRegistry::getTableLocator()->get($alias);

        $isForeign = $table->hasBehavior('Translate');

        $fieldMethod = $isForeign ? 'translationField' : 'aliasField';

        $this->add('q', 'Search.Like', [
            'before' => true,
            'after' => true,
            'mode' => 'or',
            'comparison' => 'LIKE',
            'wildcardAny' => '*',
            'wildcardOne' => '?',
            'multiValueSeparator' => ' ',
            // 'valueMode' => 'AND',
            'beforeProcess' => function (\Cake\ORM\Query\SelectQuery $query, array $args, \Search\Model\Filter\Base $filter) {
                if (isset($args['q'])) {
                    $args['q'] = mb_convert_kana($args['q'], 's');
                    return $args;
                }
            },
            'fields' => [$table->$fieldMethod("searchtext")]
        ]);

        $this->like(
            'title',
            [
                'fields' => $table->$fieldMethod('title'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'value01',
            [
                'fields' => $table->$fieldMethod('value01'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'value02',
            [
                'fields' => $table->$fieldMethod('value02'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'value03',
            [
                'fields' => $table->$fieldMethod('value03'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'value04',
            [
                'fields' => $table->$fieldMethod('value04'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'value05',
            [
                'fields' => $table->$fieldMethod('value05'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'master_area_id',
            [
                'fields' => $table->$fieldMethod('master_area_id'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'master_category_id',
            [
                'fields' => $table->$fieldMethod('master_category_id'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'file_id',
            [
                'fields' => $table->$fieldMethod('file_id'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'file_alt',
            [
                'fields' => $table->$fieldMethod('file_alt'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'status',
            [
                'fields' => $table->$fieldMethod('status'),
                'aliasField' => false,
            ]
        );
        $this->like(
            'public',
            [
                'fields' => $table->$fieldMethod('public'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'created_by_admin',
            [
                'fields' => $table->$fieldMethod('created_by_admin'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'created_by_user',
            [
                'fields' => $table->$fieldMethod('created_by_user'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'modified_by_admin',
            [
                'fields' => $table->$fieldMethod('modified_by_admin'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'modified_by_user',
            [
                'fields' => $table->$fieldMethod('modified_by_user'),
                'aliasField' => false,
            ]
        );
        $this->value(
            'cid',
            [
                'fields' => $table->$fieldMethod('cid'),
                'aliasField' => false,
            ]
        );
    }
}
