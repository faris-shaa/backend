<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Setting;
use App\Models\User;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

/**
 * Class EventOrgananizerController
 * @package App\Http\Controllers\Frontend
 */
class EventOrgananizerController extends Controller
{
    public function __invoke($uuid, $name)
    {
        /** @var User $organizer */
        $organizer = User::where("external_id", $uuid)->first();

        if (!$organizer) {
            abort(404);
        }

        $this->setSeos($organizer);
        $recentGalleries =
            $organizer->events()
//                ->previous()
                ->whereNotNull("gallery")
                ->inRandomOrder()
                ->limit(10)
                ->get(["gallery", "name as eventName", "id"]);

        return view("front.organizer_details", compact("organizer", "recentGalleries"));
    }

    private function setSEOS(User $organizer)
    {
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($organizer->organization_name)
            ->addKeyword([
                $setting->app_name,
                $organizer->organization_name,
                $setting->app_name . ' - ' . $organizer->organization_name,
            ]);

        OpenGraph::setTitle($organizer->organization_name)
            ->setUrl(url()->current())
            ->addImage($organizer->imagePath . $organizer->image)
            ->setArticle([
                'organization' => $organizer->organization_name,
            ]);

        JsonLd::setTitle($organizer->organization_name)
            ->setType('Article');
    }
}
