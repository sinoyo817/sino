<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Behavior\Translate\TranslateTrait;

/**
 * Sample Entity
 *
 * @property string $id
 * @property string $title
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string|null $master_area_id
 * @property string|null $master_category_id
 * @property string|null $file_id
 * @property string|null $file_alt
 * @property string|null $summary
 * @property string|null $summary_files
 * @property \Cake\I18n\FrozenDate|null $published
 * @property \Cake\I18n\FrozenDate|null $start_date
 * @property \Cake\I18n\FrozenDate|null $end_date
 * @property string|null $status
 * @property string|null $public
 * @property string|null $searchtext
 * @property \Cake\I18n\FrozenTime|null $created
 * @property string|null $created_by_admin
 * @property string|null $created_by_user
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $modified_by_admin
 * @property string|null $modified_by_user
 * @property int $cid
 *
 * @property \App\Model\Entity\MasterArea[] $master_areas
 * @property \App\Model\Entity\File $file
 * @property \App\Model\Entity\MasterAreasSamplesPrivate[] $master_areas_samples_private
 * @property \App\Model\Entity\MasterCategoriesSample[] $master_categories_samples
 * @property \App\Model\Entity\MasterCategoriesSamplesPrivate[] $master_categories_samples_private
 */
class Sample extends AppEntity
{
    use TranslateTrait;
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'title' => true,
        'value01' => true,
        'value02' => true,
        'value03' => true,
        'value04' => true,
        'value05' => true,
        'master_area_id' => true,
        'master_category_id' => true,
        'file_id' => true,
        'file_alt' => true,
        'summary' => true,
        'summary_files' => true,
        'published' => true,
        'start_date' => true,
        'end_date' => true,
        'status' => true,
        'public' => true,
        'searchtext' => true,
        'created' => true,
        'modified' => true,
        'cid' => true,
        'master_areas' => true,
        // 'master_categories' => true,
        'master_topic_categories' => true,
        'file' => true,
        'master_areas_samples' => true,
        'master_areas_samples_private' => true,
        'master_categories_samples' => true,
        'master_categories_samples_private' => true,
        'id' => true,
        '_translations' => true,
        '_i18n' => true,
    ];
}
