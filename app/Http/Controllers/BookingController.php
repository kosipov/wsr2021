<?php

namespace App\Http\Controllers;

use App\Http\Formatters\BookingFormatter;
use App\Http\Validators\FlightValidator;
use App\Http\Validators\PassengerValidator;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    private const DEFAULT_LENGTH_BOOKING_CODE = 5;

    private BookingFormatter $formatter;
    private PassengerValidator $passengerValidator;
    private FlightValidator $flightValidator;

    /**
     * @param BookingFormatter $formatter
     * @param PassengerValidator $passengerValidator
     * @param FlightValidator $flightValidator
     */
    public function __construct(BookingFormatter $formatter, PassengerValidator $passengerValidator, FlightValidator $flightValidator)
    {
        $this->formatter = $formatter;
        $this->passengerValidator = $passengerValidator;
        $this->flightValidator = $flightValidator;
    }


    public function createBooking(Request $request): JsonResponse
    {
        $flightFromRequest = $request->get('flight_from', []);
        $flightBackRequest = $request->get('flight_back', []);
        $passengersRequest = $request->get('passengers', []);

        if (!$this->flightValidator->validate($flightFromRequest)) {
            return response()->json($this->formatter->formatValidationError(
                'Validation error',
                $this->flightValidator->getMessages()
            ));
        }
        $flightFrom = Flight::find($flightFromRequest['id']);

        if ($flightFrom->availability < count($passengersRequest)) {
            return response()->json($this->formatter->formatValidationError('Validation error',
                [
                    'flight_from' => ['No free places']
                ]
            ));
        }
        foreach ($passengersRequest as $passenger) {
            if (!$this->passengerValidator->validate($passenger)) {
                return response()
                    ->json($this->formatter->formatValidationError(
                        'Validation error',
                        $this->passengerValidator->getMessages()
                    ));
            }
        }

        $booking = new Booking();

        if ($this->flightValidator->validate($flightBackRequest)) {
            $flightBack = Flight::find($flightBackRequest['id']);

            if ($flightBack->availability < count($passengersRequest)) {
                return response()->json($this->formatter->formatValidationError('Validation error',
                    [
                        'flight_back' => ['No free places']
                    ]
                ));
            }

            $booking->flight_back = $flightBack->id;
            $booking->date_back = $flightBackRequest['date'];
        }
        $booking->flight_from = $flightFrom->id;
        $booking->date_from = $flightFromRequest['date'];

        $lengthCode = self::DEFAULT_LENGTH_BOOKING_CODE;
        $word = range('A', 'Z');
        shuffle($word);
        $booking->code = substr(implode($word), 0, $lengthCode);

        $booking->save();

        foreach ($passengersRequest as $passenger) {
            $pass = new Passenger();
            $pass->booking_id = $booking->id;
            $pass->first_name = $passenger['first_name'];
            $pass->last_name = $passenger['last_name'];
            $pass->birth_date = $passenger['birth_date'];
            $pass->document_number = $passenger['document_number'];
            $pass->save();
            $flightFrom->availability = $flightFrom->availability - 1;
            $flightFrom->save();
            if (isset($flightBack)) {
                $flightBack->availability = $flightBack->availability - 1;
                $flightBack->save();
            }
        }

        return response()->json(['data' => $this->formatter->formatSuccessBooking($booking->code)], Response::HTTP_CREATED);
    }

    public function getUserBooking(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $documentNum = $user->document_number;

        /** @var Collection<Passenger> $passengers */
        $passengers = Passenger::where('document_number', $documentNum)->get();

        if ($passengers->isEmpty()) {
            return response()->json(['data' => []], Response::HTTP_OK);
        }

        $bookings = $passengers->map(fn (Passenger $passenger) => $passenger->booking()->first());

        return response()->json(['data' => $this->formatter->formatBookingList($bookings)], Response::HTTP_OK);
    }
}
