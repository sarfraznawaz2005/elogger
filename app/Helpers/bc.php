<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 12/23/2017
 * Time: 1:54 PM
 *
 * Some functions to get data from basecamp classic.
 * Docs: https://github.com/basecamp/basecamp-classic-api
 *
 */

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Returns instance of logged in user.
 *
 * @return Authenticatable|User
 */
function user(): User|Authenticatable
{
    return auth()->user();
}

/**
 * Get's info from basecamp
 * @param $action
 * @param string $queryString
 * @return array|string
 */
function getInfo($action, string $queryString = ''): array|string
{
    if (!credentialsOk()) {
        return '';
    }

    $url = 'https://' . companyName() . '.basecamphq.com/' . $action . '/' . $queryString;

    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($session, CURLOPT_HTTPGET, 1);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_USERAGENT, 'eteamid.basecamphq.com (sarfraz@eteamid.com)');
    curl_setopt($session, CURLOPT_HTTPHEADER, ['Accept: application/xml', 'Content-Type: application/xml']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_USERPWD, apiKey() . ":X");
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($session);
    curl_close($session);

    @$response = simplexml_load_string($response);
    $response = (array)$response;

    //$array = json_decode(json_encode($response), 1);

    if (isset($response['head']['title'])) {
        return '';
    }

    return $response;
}

function postInfo($action, $xmlData): bool|string
{
    if (!credentialsOk()) {
        return '';
    }

    @unlink('headers');

    $url = 'https://' . companyName() . '.basecamphq.com/' . $action;

    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($session, CURLOPT_USERAGENT, 'eteamid.basecamphq.com (sarfraz@eteamid.com)');
    curl_setopt($session, CURLOPT_HTTPHEADER, ['Accept: application/xml', 'Content-Type: application/xml']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_USERPWD, apiKey() . ":X");
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($session, CURLOPT_POSTFIELDS, $xmlData);
    //curl_setopt($session, CURLOPT_HEADERFUNCTION, "HandleHeaderLine");

    curl_exec($session);
    curl_close($session);

    return file_get_contents('headers');
}

function HandleHeaderLine($curl, $header_line)
{
    file_put_contents('headers', $header_line);

    return $header_line;
}

function companyName()
{
    return user()->basecamp_org;
}

function apiKey()
{
    return user()->basecamp_api_key;
}

function bcUserId($bcUserId = 0)
{
    return $bcUserId ?: user()->basecamp_api_user_id;
}

function credentialsOk(): bool
{
    return !(!trim(companyName()) || !trim(apiKey()) || !trim(bcUserId()));
}

##############################################################
## DATA FUNCTIONS
##############################################################

function getWorkedHoursData($bcUserId = 0): array|string
{
    $userId = bcUserId($bcUserId);
    $sDate = date('Y-m-1');
    $eDate = date('Y-m-d');

    $query = "report?&subject_id=$userId&from=$sDate&to=$eDate&commit=Create+report";

    return getInfo('time_entries', $query);
}

function getTotalWorkedHoursThisMonth($bcUserId = 0): int|string
{
    $hours = 0;

    $data = getWorkedHoursData($bcUserId);

    if (isset($data['time-entry'])) {

        // for when single record is returned
        $entry = (array)$data['time-entry'];

        if (isset($entry['hours'])) {
            return number_format($entry['hours'], 2);
        }

        foreach ($data['time-entry'] as $timeEntryXML) {
            $array = (array)$timeEntryXML;

            if (isset($array['hours'])) {
                $hours += $array['hours'];
            }
        }

        $hours = number_format($hours, 2);
    }

    return $hours;
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

function getTotalWorkedHoursThisMonthAllProjects($bcUserId = 0): array
{
    $finalData = [];
    $projectsData = [];

    $data = getWorkedHoursData($bcUserId);

    if (isset($data['time-entry'])) {

        // for when single record is returned
        $entry = (array)$data['time-entry'];

        if (isset($entry['hours'])) {
            $projectsData[] = $entry;
        } else {
            foreach ($data['time-entry'] as $timeEntryXML) {
                $array = (array)$timeEntryXML;
                $projectsData[] = $array;
            }
        }
    }

    $projectsData = collect($projectsData)->groupBy('project-id');

    foreach ($projectsData as $projectId => $array) {

        $totalHours = $array->sum('hours');

        $finalData[] = [
            'project_id' => $projectId,
            'project_name' => getProjectName($projectId),
            'hours' => number_format($totalHours, 2),
        ];
    }

    return collect($finalData)->sortByDesc('hours')->toArray();
}

function getProjectName($id)
{
    $data = getInfo("projects/$id");

    return $data['name'] ?? '';
}

function getPersonName($id)
{
    $data = getInfo("people/$id");

    return $data['first-name'] ?? '';
}

function getTodoListName($id)
{
    $data = getInfo("todo_lists/$id");

    return $data['name'] ?? '';
}

function getTodoName($id)
{
    $data = getInfo("todo_items/$id");

    return $data['content'] ?? '';
}

function getAllProjects(): array
{
    $finalData = [];

    $data = getInfo("projects");

    if (isset($data['project'])) {

        $project = (array)$data['project'];

        if (isset($project[0])) {
            foreach ($data['project'] as $xml) {
                $array = (array)$xml;

                if (isset($array['id'], $array['company']) && $array['status'] === 'active') {
                    $finalData[$array['id']] = ucfirst($array['name']);
                }
            }
        } else if (isset($project['id'], $project['company']) && $project['status'] === 'active') {
            $finalData[$project['id']] = ucfirst($project['name']);
        }

    }

    asort($finalData);

    return $finalData;
}

function getProjectTodoLists($projectId): array
{
    $finalData = [];

    $data = getInfo("projects/$projectId/todo_lists", '?filter=pending');

    if (isset($data['todo-list'])) {

        // for when single record is returned
        $entry = (array)$data['todo-list'];

        if (isset($entry['id'], $entry['name'])) {
            $finalData[$entry['id']] = ucfirst($entry['name']);
        } else {
            foreach ($data['todo-list'] as $xml) {
                $array = (array)$xml;

                if (isset($array['id'])) {
                    $finalData[$array['id']] = ucfirst($array['name']);
                }
            }
        }
    }

    asort($finalData);

    return $finalData;
}

function getTodoListTodos($todolistId): array
{
    $finalData = [];

    $data = getInfo("todo_lists/$todolistId/todo_items");

    if (isset($data['todo-item'])) {

        // for when single record is returned
        $entry = (array)$data['todo-item'];

        if (isset($entry['id'], $entry['name'])) {
            if ($entry['completed'] !== 'true') {
                $finalData[$entry['id']] = ucfirst($entry['content']);
            }
        } else {
            foreach ($data['todo-item'] as $xml) {
                $array = (array)$xml;

                if (isset($array['id']) && $array['completed'] !== 'true') {
                    $finalData[$array['id']] = ucfirst($array['content']);
                }
            }
        }
    }

    asort($finalData);

    return $finalData;
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
