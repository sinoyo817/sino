<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MasterAreasSamples Model
 *
 * @property \App\Model\Table\SamplesTable&\Cake\ORM\Association\BelongsTo $Samples
 * @property \App\Model\Table\MasterAreasTable&\Cake\ORM\Association\BelongsTo $MasterAreas
 *
 * @method \App\Model\Entity\MasterAreasSample newEmptyEntity()
 * @method \App\Model\Entity\MasterAreasSample newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MasterAreasSample[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MasterAreasSample get($primaryKey, $options = [])
 * @method \App\Model\Entity\MasterAreasSample findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MasterAreasSample patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MasterAreasSample[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MasterAreasSample|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MasterAreasSample saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MasterAreasSample[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MasterAreasSample[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MasterAreasSample[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MasterAreasSample[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Medii\TextSerialize\Model\Behavior\TextSerializeBehavior
 * @mixin \App\Model\Behavior\CommonAssociationBehavior
 * @mixin \Medii\File\Model\Behavior\FileBehavior
 * @mixin \Medii\Approval\Model\Behavior\ApprovalBehavior
 */
class MasterAreasSamplesTable extends AppTable
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

        $this->setTable('master_areas_samples');
    }
}
