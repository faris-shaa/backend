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
        /** @var User $organizer */
        $organizer = $event->organization;

        if (!$organizer) {
            abort(404);
        }

        $recentGalleries = $organizer->events()
//            ->previous()
            ->whereNotNull("gallery")
            ->inRandomOrder()
            ->limit(10)
            ->get(["gallery", "name as eventName", "id"]);

        return view("front.organizer_details", compact("organizer", "recentGalleries"));
    }
}
