<?php

namespace app\services;

class DLExporter
{
    private const TRACKER_ID = 3;
    private const TAB_NAME = 'Bahrain';
    private static int $fieldPosition = 0;

    public function process(): void
    {
        foreach ($this->fetchEvents() as $event) {
            $fieldIdMap = [];
            $dlEventId = $this->insertEvent($event['col1']);
            foreach ($this->fetchEventFields($event['id']) as $field) {
                $fieldIdMap[$field['id']] = $this->insertField($dlEventId, $field['name'], $fieldIdMap[$field['parent_id']] ?? null, $field['type']);
            }
        }
    }

    private function fetchEvents(): array
    {
        return DB::fetchAll("SELECT * FROM dl_xlsx_data WHERE col5 IS NOT NULL AND tab_name='" . self::TAB_NAME . "'");
    }

    private function fetchEventFields(int $eventId): array
    {
        return DB::fetchAll("SELECT * FROM dl_event_fields WHERE event_id=$eventId");
    }

    private function insertEvent(string $label): int
    {
        self::$fieldPosition = 0;
        return DB::insert('event', [
            'tracker_id' => self::TRACKER_ID,
            'label' => $label,
        ]);
    }

    private function insertField(int $eventId, string $fieldName, $parentId, $type): int
    {
        return DB::insert('field', [
            'event_id' => $eventId,
            'parent_id' => $parentId,
            'label' => $fieldName,
            'type' => $type,
            'position' => self::$fieldPosition++
        ]);
    }

}
