<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

/**
 * Class AjaxController
 * @package App\Http\Controllers\Frontend
 */
class AjaxController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $func = $_POST['method'];
        return $this->$func(json_decode($_POST['oVals'], true));
    }

    public function ajaxCall($script)
    {
        echo "<script>" . $script . "</script>";
    }

    public function fetchOrganizerUpComingEvent($oval)
    {
        $this->ajaxCall("$('.spinner_upcoming_events').show()");
        $this->ajaxCall("$('.upcomingEventsCon').html('');");
        $organizerId = $oval["user_id"];
        $limit = $oval["limit"] ?? 6;

        $baseQuery = Event::upcoming()
            ->where("user_id", $organizerId);


        $count = $baseQuery->count();
        $events = $baseQuery->limit($limit)
            ->get();

        if ($limit >= $count) {
            $this->ajaxCall("$('#load_more_upcoming').hide()");
        }

        foreach ($events as $event) {
            $view = json_encode(view('front.event_card', compact('event'))->render());
            $this->ajaxCall("$('.upcomingEventsCon').append($view);");
        }

        $this->ajaxCall("$('.spinner_upcoming_events').hide();");
    }

    public function fetchOrganizerUpPreviousEvent($oval)
    {
        $this->ajaxCall("$('.spinner_previous_events').show()");
        $this->ajaxCall("$('.previousEventsCon').html('');");
        $organizerId = $oval["user_id"];
        $limit = $oval["limit"] ?? 6;

        $baseQuery = Event::previous()
            ->where("user_id", $organizerId);


        $count = $baseQuery->count();
        $events = $baseQuery->limit($limit)
            ->get();

        if ($limit >= $count) {
            $this->ajaxCall("$('#load_more_previous').hide()");
        }

        foreach ($events as $event) {
            $view = json_encode(view('front.event_card', compact('event'))->render());
            $this->ajaxCall("$('.previousEventsCon').append($view);");
        }

        $this->ajaxCall("$('.spinner_previous_events').hide();");
    }


}
