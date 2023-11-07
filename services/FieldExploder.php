<?php

namespace app\services;

class FieldExploder
{
    public function process(): void
    {
        foreach (Repository::fetchEvents() as $event) {
            $this->processEvent($event['id'], $event['col2']);
        }
    }

    private function processEvent(int $eventId, string $eventData): void
    {
        $this->explodeFields($eventId, $this->fixJson($this->trimEventData($eventData)));
    }

    private function trimEventData(string $eventData): string
    {
        $eventData = substr($eventData, strrpos($eventData, ".push(") + 6);
        if (str_contains($eventData, ');')) {
            $eventData = strstr($eventData, ');', true);
        }

        return trim(str_replace(['<script>', '</script>'], '', $eventData));
    }

    private function fixJson(string $eventData): string
    {
        $replace = [
            '"model":[{Listofselectedmodels}]' => '"model":"",',
            '"trim":[{Listofselectedtrim}]' => '"trim":"",',
            '"fuel_type":[{selectedoptions}]' => '"fuel_type":"",',
            '"distance":[{selectedoptions}]' => '"distance":"",',
            '"colors":[{Listofselectedcolors}]' => '"colors":"",',
            '"body_styles":[{Listofselectedstyles}]' => '"body_styles":"",',
            '"door_options":[{Listofselecteddooroptions}]' => '"door_options":"",',
            'selected_features":[{Listofselectedfeatures}]' => 'selected_features":"",',
            '"selected_engine_size":[{Listofselectedenginesize}]' => '"selected_engine_size":"",',
            '"selected_accessories":[{Listofselectedaccessories}]' => '"selected_accessories":"",',
            '"product_category":[{Listofselectedcategories}]' => '"product_category":"",',
            '"product_collection":[{Listofselectedproductcollection}]' => '"product_collection":"",',
            '"gender_select":[{Listofselectedgenders}]' => '"gender_select":"",',
            '"selected_size":[{Listofselectedsize}]' => '"selected_size":"",',
            ':True,' => ':true,',
            ',}' => '}',
            ',ecommerce:' => ',"ecommerce":',
            "''" => '"',
            '""' => '","',
        ];
        $remove = [
            ' ',
            "\n",
            "\r",
            "\t",
        ];
        $eventData = preg_replace('!//.*!', '', $eventData);
        $eventData = str_replace($remove, '', $eventData);
        $eventData = str_replace(array_keys($replace), array_values($replace), $eventData);
        if (str_ends_with($eventData, ',')) {
            $eventData = trim($eventData, ',');
        }
        if (!str_ends_with($eventData, '}')) {
            $eventData .= '}';
        }
        return  $eventData;
    }


    private function explodeFields(int $eventId, string $eventData): void
    {
        $structure = json_decode($eventData);

        if (!$structure) {
            echo '<div class="alert alert-danger">Cannot convert event (' . $eventId . ') data to JSON</div>';
            echo "<pre>";
            print_r($eventData);
            echo "</pre>";
            return;
        }
        $structure = (array)$structure;
        ksort($structure);

        DB::update('dl_xlsx_data', ['col5' => json_encode($structure)], 'id=' . $eventId);
        $this->insertNestedFields($eventId, $structure);

    }

    private function insertNestedFields(int $eventId, $structure, $parentId = null): void
    {
        foreach ($structure as $key => $value) {
            if (is_numeric($key)) {
                // impression items
                if (intval($key)) {
                    continue;
                }
                $type = 3;
                $id = $parentId;
            } else {
                // standard fields
                $type = $this->getFieldType($value);
                $id = $this->insertField($eventId, $key, $parentId, $type, $value);
            }
            if ($type) {
                // go deeper
                $this->insertNestedFields($eventId, $value, $id);
            }
        }
    }

    private function getFieldType($fieldValue): int
    {
        return match (true) {
            is_object($fieldValue) => 1,
            is_array($fieldValue) => 2,
            default => 0,
        };
    }

    private function insertField(int $eventId, string $fieldName, $parentId, $type, $value): int
    {
        return DB::insert('dl_event_fields', [
            'event_id' => $eventId,
            'parent_id' => $parentId,
            'name' => $fieldName,
            'type' => $type,
            'value' => is_string($value) ? $value : null
        ]);
    }



}
