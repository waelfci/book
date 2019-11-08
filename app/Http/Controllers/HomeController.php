<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function save(Request $request)
    {
        $response = [];

        $week_days = $request->week_days;

        $start_date = $request->start_date;

        $count = 0;

        $date = '';

        foreach (range(1, $request->number_of_chapters) as $chapter) {

            $response[$chapter] = [];

            foreach (range(1, $request->sessions) as $session) {

                if(isset($week_days[$count])) {
                    $week_day = $week_days[$count];

                } else {
                    reset($week_days);
                    $count = 0;

                    $week_day = $week_days[$count];
                }

                $start_date = date("Y-m-d", strtotime('next ' . $this->getDayNames()[$week_day], strtotime($start_date)));

                $response[$chapter]['sessions'][$session] = date("l", strtotime($start_date)) . ' (' . $start_date . ')';

                $count++;
            }
        }

        return response()->json(['data' => $response]);
    }

    public function getWeekDays()
    {
        $numeric_days = [0, 1, 2, 3, 4, 5, 6];

        $days_arr = [];

        foreach ($numeric_days as $day) {

            $days_arr[] = ['id' => $day, 'name' => date("l", strtotime("Sunday +{$day} days"))];
        }

        return response()->json(['days' => $days_arr]);
    }

    private function getDayNames()
    {
        $numeric_days = [0, 1, 2, 3, 4, 5, 6];

        $days_names = [];

        foreach ($numeric_days as $day) {

            $days_names[$day] = date("l", strtotime("Sunday +{$day} days"));
        }

        return $days_names;
    }
}
