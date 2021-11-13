<?php

namespace App\Http\Formatters;

use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use Illuminate\Support\Collection;

class BookingFormatter extends AbstractFormatter
{
    public function formatSuccessBooking(string $code): array
    {
        return [
            'code' => $code
        ];
    }

    /**
     * @param Collection<Booking> $bookings
     * @return array
     */
    public function formatBookingList(Collection $bookings): array
    {
        return ['items' => $bookings->map(
            fn(Booking $booking) => $this->formatBooking($booking)
        )->values()->all()];
    }

    public function formatBooking(Booking $booking): array
    {
        $flightBack = $booking->flightBack()->first() ? $booking->flightBack()->first() : null;
        $flightBackCost = is_null($flightBack) ? 0 : $flightBack->cost;
        $cost = ($booking->flightFrom()->first()->cost + $flightBackCost) * $booking->passengers()->get()->count();
        $flights[] = $this->formatFlights($booking->flightFrom()->first(), $booking->date_from);
        if ($flightBack) {
            $flights[] = $this->formatFlights($booking->flightBack()->first(), $booking->date_back);
        }
        return [
            'code' => $booking->code,
            'cost' => $cost,
            'flights' => $flights,
            'passengers' => $this->formatPassengers($booking->passengers()->get())
        ];
    }

    public function formatFlights(Flight $flight, string $dateFrom): array
    {
        return [
            'flight_id' => $flight->id,
            'flight_code' => $flight->flight_code,
            'from' => $this->formatAirportInfo($flight->airportFrom()->first(), $dateFrom, $flight->time_from),
            'to' => $this->formatAirportInfo($flight->airportTo()->first(), $dateFrom, $flight->time_to),
            'cost' => $flight->cost,
            'availability' => $flight->availability
        ];
    }

    public function formatAirportInfo(Airport $airport, string $date, string $time): array
    {
        return [
            'city' => $airport->city,
            'airport' => $airport->name,
            'iata' => $airport->iata,
            'date' => $date,
            'time' => $time
        ];
    }

    /**
     * @param Collection<Passenger> $passengers
     * @return array
     */
    public function formatPassengers(Collection $passengers): array
    {
        return $passengers->map(
            fn (Passenger $passenger) => $this->formatPassenger($passenger)
        )->values()->all();
    }

    /**
     * @param Passenger $passenger
     * @return array
     */
    public function formatPassenger(Passenger $passenger): array
    {
        return [
            'id' => $passenger->id,
            'first_name' => $passenger->first_name,
            'last_name' => $passenger->last_name,
            'birth_date' => $passenger->birth_date,
            'document_number' => $passenger->document_number,
            'place_from' => $passenger->place_from,
            'place_back' => $passenger->place_back
        ];
    }
}
