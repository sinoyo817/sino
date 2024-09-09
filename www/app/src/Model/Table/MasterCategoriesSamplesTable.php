<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MasterSampleCategories Model
 *
 * @property \App\Model\Table\SamplesTable&\Cake\ORM\Association\BelongsToMany $Samples
 * @property \App\Model\Table\SamplesPrivateTable&\Cake\ORM\Association\BelongsToMany $SamplesPrivate
 *
 * @method \App\Model\Entity\MasterSampleCategory newEmptyEntity()
 * @method \App\Model\Entity\MasterSampleCategory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MasterSampleCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MasterSampleCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MasterSampleCategory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MasterSampleCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MasterSampleCategory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Medii\TextSerialize\Model\Behavior\TextSerializeBehavior
 * @mixin \App\Model\Behavior\CommonAssociationBehavior
 * @mixin \Medii\File\Model\Behavior\FileBehavior
 * @mixin \Medii\Approval\Model\Behavior\ApprovalBehavior
 */
class MasterCategoriesSamplesTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('master_categories_samples');
    }
}
