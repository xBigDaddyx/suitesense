<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Widgets;

use App\Enums\ReservationStatus;
use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Room;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class ReservationCalendarWidget extends FullCalendarWidget
{
    public Room $room;



    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {

        return Reservation::query()
            ->where('room_id', $this->room->id)
            ->where('check_in', '>=', $fetchInfo['start'])
            ->where('check_out', '<=', $fetchInfo['end'])
            ->where('status', ReservationStatus::CONFIRMED->value)
            ->get()
            ->map(
                fn(Reservation $event) => [
                    'title' => $event->guest->name,
                    'guest_name' => $event->guest->name,
                    'guest_phone' => $event->guest->phone,
                    'start' => $event->check_in,
                    'end' => $event->check_out,
                    'url' => ReservationResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    'displayEventTime' => 'true'
                ]
            )
            ->all();
    }
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridMonth,dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
            'eventTimeFormat' => [
                'hour' => '2-digit',
                'minute' => '2-digit',
                'second' => '2-digit',
                'hour12' => 'false'
            ]

        ];
    }
    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+'Check In : '+event.start.toLocaleString()+' / Check Out : '+event.end.toLocaleString()+"' }");

        }
    JS;
    }
}
