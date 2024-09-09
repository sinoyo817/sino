<?php
declare(strict_types=1);

namespace App\Controller\Adsys\Api;

use App\Controller\AppController;
use App\Model\Entity\Sample;
use App\Model\Filter\SamplesCollection;
use App\Model\Table\MasterTopicCategoriesTable;
use App\Model\Table\MasterAreasTable;

use Medii\Crud\Interfaces\ConfirmInterface;
use Medii\Crud\Interfaces\CreateInterface;
use Medii\Crud\Interfaces\PreviewInterface;
use Medii\Crud\Interfaces\ReadInterface;
use Medii\Crud\Interfaces\SearchInterface;
use Medii\Crud\Interfaces\StatusInterface;
use Medii\Crud\Interfaces\UpdateInterface;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;

/**
 * Samples Controller
 *
 * @method \App\Model\Entity\Sample[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SamplesController extends AppController
{
    protected $defaultTable = 'Samples';

    public $paginate = [
        'limit' => 20,
    ];

    /**
     * Index method
     *
     * @param \Medii\Crud\Interfaces\SearchInterface $search
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(SearchInterface $search)
    {
        $associated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $search->setfindOptions(['contain' => $associated]);
        $this->set('data', $search->search($this));
    }

    /**
     * Add method
     *
     * @param \Medii\Crud\Interfaces\CreateInterface $create
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(CreateInterface $create)
    {
        $associated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $create->setPatchEntityOptions([
            'associated' => $associated
        ]);

        $table = $this->fetchTable();
        $table->getEventManager()->on('Model.beforeMarshal', function (EventInterface $event, $data) {

            if (isset($data['master_areas']) && !isset($data['master_areas']['_ids'])) {
                $data['master_areas']['_ids'] = $data['master_areas'];
            }
            if (isset($data['master_topic_categories']) && !isset($data['master_topic_categories']['_ids'])) {
                $data['master_topic_categories']['_ids'] = $data['master_topic_categories'];
            }
        });

        $this->set('data', $create->save($this));
    }

    /**
     * Edit method
     *
     * @param \Medii\Crud\Interfaces\UpdateInterface $update
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(UpdateInterface $update)
    {
        $locale = $this->getRequest()->getQuery('locale');
        $isForeign = $locale && $locale !== I18n::getDefaultLocale();

        $commonAssociated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $findAssociated = $commonAssociated;
        $patchAssociated = $commonAssociated;

        $update->setfindOptions(['contain' => $findAssociated]);
        $update->setPatchEntityOptions([
            'associated' => $patchAssociated,
            'validate' => $isForeign ? 'locale' : 'default',
        ]);
        $table = $this->fetchTable();
        $table->getEventManager()->on('Model.beforeMarshal', function (EventInterface $event, $data) {

            if (isset($data['master_areas']) && !isset($data['master_areas']['_ids'])) {
                $data['master_areas']['_ids'] = $data['master_areas'];
            }
            if (isset($data['master_topic_categories']) && !isset($data['master_topic_categories']['_ids'])) {
                $data['master_topic_categories']['_ids'] = $data['master_topic_categories'];
            }
        });
        $table->getEventManager()->on('Model.afterMarshal', function (\Cake\Event\EventInterface $event, \Cake\Datasource\EntityInterface $entity, $data, $options) {
           
                $isForeignExists = $event->getData('options')['isForeignExists'] ?? true;
                $originalEntity = $event->getData('options')['originalEntity'] ?? [];

                // created_by_adminが無いとPolicyに引っかかるため追加
                if (!$isForeignExists && $originalEntity) {
                    $now = new Chronos();
                    $entity->created_by_admin = $originalEntity->created_by_admin;
                    $entity->created_by_user = $originalEntity->created_by_user;
                    $entity->public = "unpublished";
                    $entity->created = $now->format('Y-m-d H:i:s');
                    $entity->modified = $now->format('Y-m-d H:i:s');
                }
            
        });

        $update->ignoreAddDefaultLocaleData();

        
        $this->set('data', $update->save($this));
    }

    /**
     * Confirm method
     *
     * @param \Medii\Crud\Interfaces\ConfirmInterface $confirm
     * @return \Cake\Http\Response|null|void Redirects on successful confirm, renders view otherwise.
     */
    public function confirm(ConfirmInterface $confirm)
    {
        $locale = $this->getRequest()->getQuery('locale');
        $isForeign = $locale && $locale !== I18n::getDefaultLocale();
        
        $commonAssociated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $findAssociated = $commonAssociated;
        $patchAssociated = $commonAssociated;

        $confirm->setfindOptions(['contain' => $findAssociated]);
        $confirm->setPatchEntityOptions([
            'associated' => $patchAssociated,
            'validate' => $isForeign ? 'locale' : 'default',
        ]);
        $confirm->ignorePublicView();

        $table = $this->fetchTable();
        $table->getEventManager()->on('Model.beforeMarshal', function (EventInterface $event, $data) {

            if (isset($data['master_areas']) && !isset($data['master_areas']['_ids'])) {
                $data['master_areas']['_ids'] = $data['master_areas'];
            }
            if (isset($data['master_topic_categories']) && !isset($data['master_topic_categories']['_ids'])) {
                $data['master_topic_categories']['_ids'] = $data['master_topic_categories'];
            }
        });

        $this->set('data', $confirm->confirm($this));
    }

    /**
     * View method
     *
     * @param \Medii\Crud\Interfaces\ReadInterface $read
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(ReadInterface $read)
    {
        $associated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $read->setfindOptions(['contain' => $associated]);

        $this->set('data', $read->read($this));
    }

    /**
     * Preview method
     *
     * @param \Medii\Crud\Interfaces\PreviewInterface $preview
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function preview(PreviewInterface $preview)
    {
        $associated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $preview->setfindOptions(['contain' => $associated]);

        return  $preview->preview($this);
    }

    /**
     * Status method
     *
     * @param \Medii\Crud\Interfaces\StatusInterface $status
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function status(StatusInterface $status)
    {
        $associated = ['MasterAreas', 'MasterTopicCategories', 'MasterSelectAreas', 'MasterSelectCategories'];
        $status->setStatusOptions(['copyAssociated' => $associated, 'forceContain' => []]);

         $this->set('data', $status->status($this));
    }

    /**
     * Metadata method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function metadata()
    {
        $data = [];

        // 必要なマスタデータ等を追加していく
        $table = $this->fetchTable('MasterTopicCategories');
        $categories = $table->find()->find('public')->select(['id', 'title']);
        $data['master_categories'] = $categories;

        $table = $this->fetchTable('MasterAreas');
        $masterAreas = $table->find()->find('public')->select(['id', 'title']);
        $data['master_areas'] = $masterAreas;

         $this->set('data', $data);
    }
}
