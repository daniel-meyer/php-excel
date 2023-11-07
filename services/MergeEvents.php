<?php

namespace app\services;

class MergeEvents
{
    private const SKIP_FIELDS = [
        'advertising_session_id',
        'application_id',
        'parent_application_id',
        'application_subid',
        'configurator_base_price',
        'configurator_final_price',
        'selected_accessories',
    ];

    public function process(): void
    {
        foreach (Repository::fetchUniqueEventNames() as $eventName) {
            $fieldsStructure = [];
            foreach (Repository::fetchEventsWithName($eventName) as $event) {
                $fieldsStructure = array_merge($fieldsStructure, json_decode($event['col5'], true));
            }
            $this->removeSkippedFields($fieldsStructure);
            $this->setEventFieldAsFirst($fieldsStructure);

            echo '<pre>';
            echo json_encode($fieldsStructure);
            echo '</pre>';
        }
    }

    private function removeSkippedFields(&$arr): void
    {
        foreach ($arr as $key => $value) {
            if (in_array($key, self::SKIP_FIELDS)) {
                unset($arr[$key]);
            } elseif (is_array($arr[$key])) {
                $this->removeSkippedFields($arr[$key]);
            }
        }
    }

    private function setEventFieldAsFirst(&$arr): void
    {
        ksort($arr);
        $arr = array_merge(['event' => $arr['event']], $arr);
    }
}
