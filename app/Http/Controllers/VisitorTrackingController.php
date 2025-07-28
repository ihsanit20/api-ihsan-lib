<?php

namespace App\Http\Controllers;

use App\Models\VisitorDailyCount;
use App\Models\VisitorHourlyCount;
use App\Models\VisitorRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorTrackingController extends Controller
{
    public function track(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string',
        ]);

        $uuid = $request->uuid;
        $now = Carbon::now();
        $date = $now->toDateString(); // 2025-07-28
        $hour = $now->hour; // 0-23
        $datetime = $now->format('Y-m-d H:00:00'); // 2025-07-28 14:00:00

        $visitor = VisitorRecord::firstOrCreate(['uuid' => $uuid]);

        $trackedDaily = false;
        $trackedHourly = false;

        if ((string) $visitor->last_visit_date !== $date) {
            $daily = VisitorDailyCount::firstOrCreate(
                ['date' => $date],
                ['visits' => 0]
            );

            $daily->increment('visits');
            $visitor->last_visit_date = $date;
            $trackedDaily = true;
        }

        if ((string) $visitor->last_visit_date_hour !== $datetime) {
            $hourly = VisitorHourlyCount::firstOrCreate(
                ['date' => $date, 'hour' => $hour],
                ['visits' => 0]
            );

            $hourly->increment('visits');
            $visitor->last_visit_date_hour = $datetime;
            $trackedHourly = true;
        }

        if ($trackedDaily || $trackedHourly) {
            $visitor->save();
        }

        return response()->json([
            'message' => 'Tracked',
            'daily' => $trackedDaily,
            'hourly' => $trackedHourly,
        ]);
    }

    public function stats(Request $request)
    {
        $date = $request->query('date', Carbon::now()->toDateString());

        $total_visits_today = VisitorDailyCount::where('date', $date)->value('visits');

        $visitor_hourly_counts = VisitorHourlyCount::query()
            ->select([
                'hour',
                'visits',
            ])
            ->where('date', $date)
            ->orderBy('hour')
            ->get();

        return response()->json([
            'total_visits_today' => $total_visits_today ?? 0,
            'hourly_stats' => $visitor_hourly_counts,
        ]);
    }
}
