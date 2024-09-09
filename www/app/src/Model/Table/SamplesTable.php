<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Samples Model
 *
 * @property \App\Model\Table\MasterAreasTable&\Cake\ORM\Association\BelongsTo $MasterAreas
 * @property \App\Model\Table\FilesTable&\Cake\ORM\Association\BelongsTo $Files
 * @property \App\Model\Table\MasterAreasSamplesPrivateTable&\Cake\ORM\Association\HasMany $MasterAreasSamplesPrivate
 * @property \App\Model\Table\MasterCategoriesSamplesTable&\Cake\ORM\Association\HasMany $MasterCategoriesSamples
 * @property \App\Model\Table\MasterCategoriesSamplesPrivateTable&\Cake\ORM\Association\HasMany $MasterCategoriesSamplesPrivate
 * @property \App\Model\Table\MasterAreasTable&\Cake\ORM\Association\BelongsToMany $MasterAreas
 *
 * @method \App\Model\Entity\Sample newEmptyEntity()
 * @method \App\Model\Entity\Sample newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Sample[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sample get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sample findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Sample patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sample[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sample|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sample saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sample[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sample[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sample[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sample[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Medii\TextSerialize\Model\Behavior\TextSerializeBehavior
 * @mixin \App\Model\Behavior\CommonAssociationBehavior
 * @mixin \Medii\File\Model\Behavior\FileBehavior
 * @mixin \Medii\Approval\Model\Behavior\ApprovalBehavior
 */
class SamplesTable extends AppTable
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

        $this->setTable('samples');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $fileFields = [];
        $approvalTables = [
            $this,
        ];
        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
            'className' => 'Medii/File.Files',
        ]);
        $fileFields[] = 'file_id';

        // 単数選択
        $this->belongsTo('MasterSelectAreas', [
            'className' => 'MasterAreas',
            'foreignKey' => 'master_area_id',
        ]);
        $this->belongsTo('MasterSelectCategories', [
            'className' => 'MasterTopicCategories',
            'foreignKey' => 'master_category_id',
        ]);

        // チェックボックス
        $this->belongsToMany('MasterTopicCategories', [
            'joinTable' => 'master_categories_samples',
        ])
            ->setForeignKey('sample_id')
            ->setTargetForeignKey('master_category_id')
            ->setCascadeCallbacks(true)
            ->setDependent(true);

        $this->hasMany('MasterCategoriesSamples')
            ->setForeignKey('sample_id')
            ->setDependent(true)
            ->setSaveStrategy('replace');
        $approvalTables[] = $this->MasterCategoriesSamples;

        $this->belongsToMany('MasterAreas', [
            'joinTable' => 'master_areas_samples',
        ])
            ->setForeignKey('sample_id')
            ->setTargetForeignKey('master_area_id')
            ->setCascadeCallbacks(true)
            ->setDependent(true);

        $this->hasMany('MasterAreasSamples')
            ->setForeignKey('sample_id')
            ->setDependent(true)
            ->setSaveStrategy('replace');
        $approvalTables[] = $this->MasterAreasSamples;

        $this->belongsToMany('MasterAreas', [
            'foreignKey' => 'sample_id',
            'targetForeignKey' => 'master_area_id',
            'joinTable' => 'master_areas_samples',
        ]);

        $this->addBehavior('Translate', [
        "allowEmptyTranslations" => true,
        "strategyClass" => \Cake\ORM\Behavior\Translate\ShadowTableStrategy::class
    ]);

        $this->addBehavior('Medii/TextSerialize.TextSerialize');
        $this->addBehavior('CommonAssociation', [
            'isAssociation' => [
                'blocks' => false,
                'metadata' => false,
            ],
        ]);
        $this->addBehavior('Medii/File.File', [
            'fileIdFields' => [
                'fields' => $fileFields,
            ],
            'commonAssociation' => [
                'blocks' => false,
                'metadata' => false,
            ],
        ]);
        $this->addBehavior('Medii/Approval.Approval', [
            'approvalTables' => $approvalTables,
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('value01')
            ->maxLength('value01', 255)
            ->allowEmptyString('value01');

        $validator
            ->scalar('value02')
            ->maxLength('value02', 255)
            ->allowEmptyString('value02');

        $validator
            ->scalar('value03')
            ->maxLength('value03', 255)
            ->allowEmptyString('value03');

        $validator
            ->scalar('value04')
            ->maxLength('value04', 255)
            ->allowEmptyString('value04');

        $validator
            ->scalar('value05')
            ->maxLength('value05', 255)
            ->allowEmptyString('value05');

        $validator
            ->uuid('master_area_id')
            ->allowEmptyString('master_area_id');

        $validator
            ->uuid('master_category_id')
            ->allowEmptyString('master_category_id');

        $validator
            ->uuid('file_id')
            ->allowEmptyFile('file_id');

        $validator
            ->scalar('file_alt')
            ->maxLength('file_alt', 255)
            ->allowEmptyFile('file_alt');

        $validator
            ->scalar('summary')
            ->allowEmptyString('summary');

        $validator
            ->scalar('summary_files')
            ->maxLength('summary_files', 4294967295)
            ->allowEmptyFile('summary_files');

        $validator
            ->date('published')
            ->allowEmptyDate('published');

        $validator
            ->date('start_date')
            ->allowEmptyDate('start_date');

        $validator
            ->date('end_date')
            ->allowEmptyDate('end_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('master_area_id', 'MasterAreas'), ['errorField' => 'master_area_id']);
        $rules->add($rules->existsIn('file_id', 'Files'), ['errorField' => 'file_id']);

        return $rules;
    }
}
