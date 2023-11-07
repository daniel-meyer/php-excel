<?php

namespace app\services;

class Exporter
{
    public function exportToFile($filePath): bool
    {
        return Json::saveToFile($filePath, $this->getData());
    }

    private function getData(): array
    {
        return [
            [
                'type' => 1,
                'version' => '1',
                'defaultFieldValue' => '',
                'events' => $this->getEvents()
            ]
        ];
    }

    private function getEvents(): array
    {
        return array_map(fn($event) => $this->getEvent($event), Repository::fetchEvents());
    }

    private function getEvent(array $event): array
    {
        return [
            'label' => $event['col1'],
            'fields' => $this->getFields($event['id'])
        ];
    }

    private function getFields(int $eventId): array
    {
        return array_map(fn($field) => $this->getField($field), Repository::fetchEventFields($eventId));
    }

    private function getField(array $field): array
    {
        return [
            'label' => $field['name'],
            'type' => $field['type'],
            'value' => intval($field['type']) === 3 ? $field['value'] : null,
        ];
    }
}
