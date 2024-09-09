<?php

use Cake\Core\Configure;
use Cake\Routing\Router;

/** @var \App\Model\Entity\Spot $event */
?>
<ul>
    <?php foreach ($events as $event) : ?>
        <?php
        $url = Router::url(['controller' => 'Events', 'action' => 'detail', $event->id, 'plugin' => null, 'prefix' => false]);
        $image = $event->file ? $this->Html->image($event->file->filePath, ['alt' => h($event->file_alt)]) : $this->Utility->noImage();
        ?>
        <li>
            <a href="<?= $url ?>">

                <div class="txt-part">
                    <p class="date"><?= h($event->published->format('Y年m月d日')) ?></p>
                    <h4 class="ttl"><?= h($event->title) ?></h4>

                    <?php if ($event->event_date_type === Configure::read('Master.Events.TypeKey.rangeKey')) : ?>
                        <p class="calendar">
                            <?php
                            if ($event->event_start_date && $event->event_end_date) {
                                $startDate = $event->event_start_date->format('Y年m月d日') . "(" . $this->Utility->getDayOfWeek($event->event_start_date->format('w')) . ")";
                                $endDate = $event->event_end_date->format('Y年m月d日') . "(" . $this->Utility->getDayOfWeek($event->event_end_date->format('w')) . ")";
                                echo $startDate . '～' . $endDate;
                            } elseif ($event->event_start_date) {
                                $startDate = $event->event_start_date->format('Y年m月d日') . "(" . $this->Utility->getDayOfWeek($event->event_start_date->format('w')) . ")";
                                echo $startDate . '～';
                            } elseif ($date->event_end_date) {
                                $endDate = $event->event_end_date->format('Y年m月d日') . "(" . $this->Utility->getDayOfWeek($event->event_end_date->format('w')) . ")";
                                echo '～' . $endDate;
                            }

                            ?>
                        </p>
                    <?php elseif ($event->event_date_type === Configure::read('Master.Events.TypeKey.stepKey')) : ?>
                        <?php if ($event->event_dates) : ?>
                            <?php foreach ($event->event_dates as $eventDate) : ?>
                                <p class="calendar">
                                    <?php
                                    $date = $eventDate->date->format('Y年m月d日') . "(" . $this->Utility->getDayOfWeek($eventDate->date->format('w')) . ")";
                                    echo $date;
                                    ?>
                                </p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif ($event->event_date_type === Configure::read('Master.Events.TypeKey.textKey')) : ?>
                        <p class="calendar">
                            <?= nl2br(h($event->event_date_text)) ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($event->master_areas || $event->master_area || $event->master_event_categories || $event->master_event_category) : ?>
                        <ul class="list-icon">

                            <?php if ($event->master_areas) : ?>
                                <?php foreach ($event->master_areas as $area) : ?>
                                    <li>
                                        <span class="<?= h($area->class ? $area->class : "category") ?>">
                                            <?= h($area->title) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($event->master_area) : ?>
                                <li>
                                    <span class="<?= h($event->master_area->class ? $event->master_area->class : "category") ?>">
                                        <?= h($event->master_area->title) ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if ($event->master_event_categories) : ?>
                                <?php foreach ($event->master_event_categories as $category) : ?>
                                    <li>
                                        <span class="<?= h($category->class ? $category->class : "category") ?>">
                                            <?= h($category->title) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($event->master_event_category) : ?>
                                <li>
                                    <span class="<?= h($event->master_event_category->class ? $event->master_event_category->class : "category") ?>">
                                        <?= h($event->master_event_category->title) ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                        </ul>
                    <?php endif; ?>
                </div>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
