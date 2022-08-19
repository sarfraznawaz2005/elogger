<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 08/06/2022
 * Time: 1:54 PM
 */

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Returns instance of logged in user.
 *
 * @return Authenticatable|User
 */
function user(): User|Authenticatable
{
    if (session()->has('user')) {
        return session('user');
    }

    session()->put('user', auth()->user());

    return auth()->user();
}

function getWorkingDaysCount($allMonth = false): int
{
    $workdays = [];
    $month = date('n'); // Month ID, 1 through to 12.
    $year = date('Y'); // Year in 4 digit 2009 format.
    //$startDate = new DateTime(date('Y-m-1'));

    if ($allMonth) {
        //$days = date('t');
        //$datetime2 = new DateTime(date("Y-m-$days"));
        //$interval = $startDate->diff($datetime2);
        //$day_count = $interval->days; // days from 1st of month to today
        $day_count = date('t');
    } else {
        $day_count = date('d');
    }

    //loop through all days
    for ($i = 1; $i <= $day_count; $i++) {
        $date = $year . '/' . $month . '/' . $i; //format date
        $get_name = date('l', strtotime($date)); //get week day
        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

        //if not a weekend add day to array
        if ($day_name !== 'Sun' && $day_name !== 'Sat') {
            $workdays[] = $i;
        }
    }

    return count($workdays);
}

function getWorkingDatesTillToday(): array
{
    $dates = [];

    $month = date('n');
    $year = date('Y');

    $daysCount = date('d');

    for ($i = 1; $i <= $daysCount; $i++) {
        $date = $year . '/' . $month . '/' . $i;
        $dayName = substr(date('l', strtotime($date)), 0, 3);

        if ($dayName !== 'Sun' && $dayName !== 'Sat') {
            $dates[] = date('d F Y', strtotime($date));
        }
    }

    return $dates;
}

function getDatesTillToday(): array
{
    $dates = [];

    $daysCount = date('d');

    for ($i = $daysCount; $i >= 1; $i--) {
        $date = date('Y') . '/' . date('n') . '/' . $i;
        $dates[] = date('d F Y', strtotime($date));
    }

    return $dates;
}

function isWeekend($date = ''): bool
{
    $date = $date ?: date('Y-m-d');

    $weekDay = date('w', strtotime($date));

    return ($weekDay === "0" || $weekDay === "6");
}

function getBCHoursDiff($date, $startTime, $endTime, $returnNegative = false): string
{
    $sTime = Carbon::parse($date . ' ' . $startTime);
    $eTime = Carbon::parse($date . ' ' . $endTime);

    $diffInMinutes = $sTime->diffInMinutes($eTime, false);

    if ($diffInMinutes < 0 && !$returnNegative) {
        return number_format(0, 2);
    }

    return number_format($diffInMinutes / 60, 2);
}

function getUserMonthUploadedHours($userId = 0, $forceRefresh = false): float|string
{
    $userId = $userId ?: user()->basecamp_api_user_id;

    return getTotalWorkedHoursThisMonth($userId, $forceRefresh);
}

function getUserProjectlyHours($forceRefresh = false)
{
    $projects = user()->projects;

    if ($forceRefresh || !$projects->count()) {
        $projects = getTotalWorkedHoursThisMonthAllProjects();

        // reset hours
        DB::statement("update projects set hours = '0' where user_id = " . user()->id);

        foreach ($projects as $project) {

            $projectInstance = Project::query()->firstOrNew([
                'user_id' => user()->id,
                'project_id' => $project['project_id'],
            ]);

            $project['user_id'] = user()->id;

            $projectInstance->fill($project);
            $projectInstance->save();
        }
    }

    return $projects;
}

function addUserProjects(): void
{
    $projects = getAllProjects();

    foreach ($projects as $projectId => $name) {

        $projectInstance = Project::query()->firstOrNew([
            'user_id' => user()->id,
            'project_id' => $projectId,
        ]);

        $projectInstance->user_id = user()->id;
        $projectInstance->project_id = $projectId;
        $projectInstance->project_name = $name;

        $projectInstance->save();
    }
}

function getProjectNameForTodo($projectId): string
{
    return Project::query()->where('project_id', $projectId)->first()->project_name;
}

function refreshData(): void
{
    // eg todos and todolists that were saved from entry page
    session()->forget('app');

    set_time_limit(0);

    $allUsersHours = [];

    // refresh all users hours
    $users = getAllUsers();
    //dd($users);

    if ($users && user()->isAdmin()) {
        foreach ($users as $userId => $user) {
            $nameArray = explode(' ', $user);
            $name = $nameArray[0] . ' ' . $nameArray[1][0];

            $hours = getUserMonthUploadedHours($userId, true);

            if ($hours) {
                $allUsersHours[] = [
                    'name' => $name,
                    'hours' => $hours,
                    'color' => substr(md5(mt_rand()), 0, 6),
                ];
            }

            // sort by max hours
            $allUsersHours = collect($allUsersHours)->sortByDesc('hours');
        }

        session()->put('all_users_hours', $allUsersHours);
    }

    addUserProjects();

    $monthHours = getUserMonthUploadedHours(0, true);
    session()->put('month_hours', $monthHours);

    if ($monthHours === 0.0 || $monthHours === '0.00') {
        session()->put('month_hours', 'none');
    }

    $uploadedHoursToday = getTotalWorkedHoursForSingleDateCurrentMonth();

    session()->put('uploaded_hours_today', $uploadedHoursToday);

    getUserProjectlyHours(true);
}

function workDayCountMonth($holidayCount = 0)
{
    $holidayCount = $holidayCount ?: user()->holidays_count;

    return getWorkingDaysCount(true) - $holidayCount;
}

function monthProjectedHours($workDayCountMonth, $holidayCount = 0, $forceRefresh = false, $bsasecampUserId = 0, $workingHoursCount = 0, $user = null): string
{
    $bsasecampUserId = $bsasecampUserId ?: user()->basecamp_api_user_id;
    $workingHoursCount = $workingHoursCount ?: user()->working_hours_count;
    $holidayCount = $holidayCount ?: user()->holidays_count;
    $user = $user ?: user();

    if (!hasBasecampSetup($user)) {
        return '0';
    }

    $pendingHoursMonth = $user->pendingTodosHoursMonth();
    $monthHoursUploaded = getUserMonthUploadedHours($bsasecampUserId, $forceRefresh);

    // projected until today
    $pendingHoursToday = $user->pendingTodosHoursToday();
    $add = $pendingHoursToday > $workingHoursCount ? $pendingHoursToday : $workingHoursCount;

    // do not add 8 in case of weekends
    $add -= isWeekend() && !$pendingHoursToday ? $workingHoursCount : 0;

    $projectedUntilToday = round($monthHoursUploaded + ($pendingHoursMonth - $pendingHoursToday) + $add);

    // if user has already uploaded hours for today, substract them from projection
    $projectedUntilToday -= getTotalWorkedHoursForSingleDateCurrentMonth(0, $user->basecamp_api_user_id) >= $workingHoursCount ? $workingHoursCount : 0;

    // projected of coming days
    $totalComingDays = round(($workDayCountMonth - (getWorkingDaysCount() - $holidayCount)) * $workingHoursCount);
    //dd($workDayCountMonth - (getWorkingDaysCount() - $holidayCount), $projectedUntilToday, $totalComingDays);

    return round($projectedUntilToday + $totalComingDays);
}

function workMonthRequiredHours($workDayCountMonth, $workingHoursCount = 0): float|int
{
    $workingHoursCount = $workingHoursCount ?: user()->working_hours_count;

    return $workDayCountMonth * $workingHoursCount;
}

function monthHoursUploaded()
{
    return session('month_hours') === 'none' ? '0.00' : session('month_hours');
}

function hasBasecampSetup($user = null): bool
{
    $user = $user ?: user();

    return $user->basecamp_api_key && $user->basecamp_api_user_id;
}

/** @noinspection ALL */
function getSql($builder): string
{
    $addSlashes = str_replace('?', "'?'", $builder->toSql());

    return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
}

/** @noinspection ALL */
function forgetUserData()
{
    session()->forget('app');
    session()->forget('all_users_hours');
    session()->forget('month_hours');
    session()->forget('uploaded_hours_today');

    sleep(1);
}

/** @noinspection ALL */
function dumpQueries(): void
{
    DB::enableQueryLog();

    DB::listen(static function ($query) {
        $bindings = collect($query->bindings)->map(function ($param) {
            if (is_numeric($param)) {
                return $param;
            }

            return "'$param'";
        });

        dump(Str::replaceArray('?', $bindings->toArray(), $query->sql));
    });
}
