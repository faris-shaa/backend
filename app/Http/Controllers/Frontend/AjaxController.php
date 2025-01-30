<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        $organizerId = $oval["user_id"];
        $limit = $oval["limit"] ?? 9;

        $baseQuery = Event::query()
            ->upcoming()
            ->where("user_id", $organizerId);
        
        if(isset($oval['start_date']))
        {
            $baseQuery = $baseQuery->whereDate('start_time', '<=', Carbon::parse($oval['start_date'])->format('Y-m-d'))
                       ->whereDate('end_time', '>=', Carbon::parse($oval['start_date'])->format('Y-m-d'));

        }

        

        $events = $baseQuery
            ->paginate($limit);

        $currentPage = $events->currentPage();
        $nextPage = $currentPage + 1;

        if ($currentPage == 1 and $events->count() == 0) {
            $this->ajaxCall("$('#empty_upcoming_search').show()");
            $this->ajaxCall("$('#load_more_upcoming').hide()");
        }

        if ($nextPage <= $events->lastPage()) {
            $this->ajaxCall("$('#load_more_upcoming').data('page',$nextPage)");
        } else {
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
        $organizerId = $oval["user_id"];
        $limit = $oval["limit"] ?? 3;

        $baseQuery = Event::query()
            ->previous()
            ->where("user_id", $organizerId);

        $events = $baseQuery->paginate($limit);

        $currentPage = $events->currentPage();
        $nextPage = $currentPage + 1;

        if ($currentPage == 1 and $events->count() == 0) {
            $this->ajaxCall("$('#empty_previous_search').show()");
            $this->ajaxCall("$('#load_more_previous').hide()");
        }

        if ($nextPage <= $events->lastPage()) {
            $this->ajaxCall("$('#load_more_previous').data('page',$nextPage)");
        } else {
            $this->ajaxCall("$('#load_more_previous').hide()");
        }

        foreach ($events as $event) {
            $view = json_encode(view('front.event_card', compact('event'))->render());
            $this->ajaxCall("$('.previousEventsCon').append($view);");
        }

        $this->ajaxCall("$('.spinner_previous_events').hide();");
    }
}