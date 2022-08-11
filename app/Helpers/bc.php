<?php

use App\Models\User;

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


function getCurlInstance(): CurlHandle|bool
{
    $email = auth()->check() ? user()->email : 'riaz@eteamid.com';

    $session = curl_init();

    curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($session, CURLOPT_USERAGENT, "eteamid.basecamphq.com ($email)");
    curl_setopt($session, CURLOPT_USERPWD, apiKey() . ":X");
    curl_setopt($session, CURLOPT_HTTPHEADER, ['Accept: application/xml', 'Content-Type: application/xml']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($session, CURLOPT_FOLLOWLOCATION, false);

    return $session;
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

    $session = getCurlInstance();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HTTPGET, true);
    curl_setopt($session, CURLOPT_HEADER, false);

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

function postInfo($action, $xmlData): array|bool
{
    if (!credentialsOk()) {
        return false;
    }

    $url = 'https://' . companyName() . '.basecamphq.com/' . $action;

    $session = getCurlInstance();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HEADER, true);
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $xmlData);

    $data = curl_exec($session);

    curl_close($session);

    return [
        'code' => curl_getinfo($session, CURLINFO_HTTP_CODE),
        'content' => $data
    ];
}

function deleteResource($action): int|bool
{
    if (!credentialsOk()) {
        return false;
    }

    $url = 'https://' . companyName() . '.basecamphq.com/' . $action;

    $session = getCurlInstance();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'DELETE');

    curl_exec($session);
    curl_close($session);

    return curl_getinfo($session, CURLINFO_HTTP_CODE);
}

function getResourceCreatedId($content): array|string|null
{
    preg_match('#location: .+#', $content, $matches);

    if (isset($matches[0])) {
        $id = @array_slice(explode('/', $matches[0]), -1)[0];

        return preg_replace('/\D/', '', $id);
    }

    return '';
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

function checkConnection(): bool
{
    // account must be setup first.
    if (!hasBasecampSetup()) {
        return true;
    }

    $data = getInfo('me');

    return isset($data['email-address']);
}

function getWorkedHoursData($bcUserId = 0): array|string
{
    $userId = bcUserId($bcUserId);
    $sDate = date('Y-m-1');
    $eDate = date('Y-m-d');

    $query = "report?&subject_id=$userId&from=$sDate&to=$eDate&commit=Create+report";

    return getInfo('time_entries', $query);
}

function getWorkedHoursDataForPeriod($startDate, $endDate): array|string
{
    $userId = bcUserId();

    $query = "report?&subject_id=$userId&from=$startDate&to=$endDate&commit=Create+report";
    //dd($query);

    return getInfo('time_entries', $query);
}

function getTotalWorkedHoursThisMonth($bcUserId = 0, $forceRefresh = false): int|string
{
    $hours = 0;

    if (!$forceRefresh) {
        $user = User::query()->where('basecamp_api_user_id', $bcUserId)->first();

        if ($user) {
            $hours = $user->projects->sum('hours');
        }

        return number_format($hours, 2);
    }

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

/** @noinspection ALL */
function getTotalWorkedHoursForSingleDateCurrentMonth($dayNumber = 0, $bcUserId = 0): int|string
{
    $hours = 0;

    $userId = bcUserId($bcUserId);
    $date = $dayNumber ? date("Y-m-$dayNumber") : date('Y-m-d');

    $query = "report?&subject_id=$userId&from=$date&to=$date&commit=Create+report";

    $data = getInfo('time_entries', $query);

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

function getProjectName($id): string
{
    $data = getInfo("projects/$id");

    return ucwords($data['name']) ?? '';
}

/** @noinspection ALL */
function getPersonName($id)
{
    $data = getInfo("people/$id");

    return $data['first-name'] ?? '';
}

/** @noinspection ALL */
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
                    $finalData[$array['id']] = ucwords($array['name']);
                }
            }
        } else if (isset($project['id'], $project['company']) && $project['status'] === 'active') {
            $finalData[$project['id']] = ucwords($project['name']);
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
            $finalData[$entry['id']] = ucwords($entry['name']);
        } else {
            foreach ($data['todo-list'] as $xml) {
                $array = (array)$xml;

                if (isset($array['id'])) {
                    $finalData[$array['id']] = ucwords($array['name']);
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

        if (isset($entry['id'])) {
            if ($entry['completed'] !== 'true') {
                $finalData[$entry['id']] = ucwords($entry['content']);
            }
        } else {
            foreach ($data['todo-item'] as $xml) {
                $array = (array)$xml;

                if (isset($array['id']) && $array['completed'] !== 'true') {
                    $finalData[$array['id']] = ucwords($array['content']);
                }
            }
        }
    }

    asort($finalData);

    return $finalData;
}

function getAllUsers(array $excludedUserIds = []): array
{
    $finalData = [];

    $data = getInfo("people");

    if (isset($data['person'])) {

        // for when single record is returned
        $entry = (array)$data['person'];

        if (isset($entry['id'], $entry['first-name'])) {
            $finalData[$entry['id']] = ucwords($entry['first-name']) . ' ' . ucwords($entry['last-name']);
        } else {
            foreach ($data['person'] as $xml) {
                $array = (array)$xml;

                // consider only company employees
                if ($array['company-id'] !== user()->basecamp_org_id) {
                    continue;
                }

                if (isset($array['first-name'])) {

                    if ($excludedUserIds && in_array($array['id'], $excludedUserIds, true)) {
                        continue;
                    }

                    $finalData[$array['id']] = ucwords($array['first-name']) . ' ' . ucwords($array['last-name']);
                }
            }
        }
    }

    asort($finalData);

    return $finalData;
}

