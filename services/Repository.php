<?php

namespace app\services;

class Repository
{
    public static function fetchEvents(): array
    {
        return DB::fetchAll("SELECT * FROM dl_xlsx_data WHERE col4 IS NULL OR col4 NOT LIKE '%external site%'");
    }

    public static function fetchEventsForTab(string $tab): array
    {
        return DB::fetchAll("SELECT * FROM dl_xlsx_data WHERE col5 IS NOT NULL AND tab_name='$tab'");
    }

    public static function fetchEventFields(int $eventId): array
    {
        return DB::fetchAll("SELECT * FROM dl_event_fields WHERE event_id=$eventId");
    }

    public static function fetchUniqueEventNames(): array
    {
        return array_column(DB::fetchAll("SELECT DISTINCT col1 FROM dl_xlsx_data WHERE col5 IS NOT NULL AND tab_name <> 'HUB'"), 'col1');
    }

    public static function fetchEventsWithName(string $name): array
    {
        return DB::fetchAll("SELECT * FROM dl_xlsx_data WHERE col5 IS NOT NULL AND tab_name <> 'HUB' AND col1 = '$name'");
    }
}
