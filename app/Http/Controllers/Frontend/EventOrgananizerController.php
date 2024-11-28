<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class EventOrgananizerController
 * @package App\Http\Controllers\Frontend
 */
class EventOrgananizerController extends Controller
{
    public function __invoke(Event $event, $name)
    {
        $organizer = $event->organization;

        if (!$organizer) {
            abort(404);
        }

        return view("front.organizer_details", compact("organizer"));
    }
}
