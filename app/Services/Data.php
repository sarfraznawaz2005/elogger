<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 12/23/2017
 * Time: 6:08 PM
 */

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class Data
{
    public static function getUserMonthlyHours($userId = 0): float|string
    {
        $userId = $userId ?: user()->basecamp_api_user_id;

        return getTotalWorkedHoursThisMonth($userId);
    }

    public static function getUserProjectlyHours($forceRefresh = false)
    {
        $projects = user()->projects;

        if ($forceRefresh || !$projects->count()) {
            $projects = getTotalWorkedHoursThisMonthAllProjects();

            // reset hours
            DB::statement("update projects set hours = '0' where user_id = " . user()->id);

            foreach ($projects as $project) {

                $projectInstance = Project::firstOrNew([
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

    public static function addUserProjects(): void
    {
        $projects = getAllProjects();

        foreach ($projects as $projectId => $name) {

            $projectInstance = Project::firstOrNew([
                'user_id' => user()->id,
                'project_id' => $projectId,
            ]);

            $projectInstance->user_id = user()->id;
            $projectInstance->project_id = $projectId;
            $projectInstance->project_name = $name;

            $projectInstance->save();
        }
    }

    public static function getAllUsers(array $excludedUserIds = []): array
    {
        $finalData = [];

        $data = getInfo("people");

        if (isset($data['person'])) {
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

                    $finalData[$array['id']] = ucfirst($array['first-name']) . ' ' . ucfirst($array['last-name']);
                }
            }
        }

        asort($finalData);

        return $finalData;
    }

    public static function checkConnection($bcUserId): bool
    {
        $name = getPersonName($bcUserId);

        return (bool)$name;
    }

    public static function refreshData(): void
    {
        set_time_limit(0);

        $excludedUserIds = [
            12026432
        ];

        $allUsersHours = [];

        // refresh all users hours
        $users = self::getAllUsers($excludedUserIds);
        //dd($users);

        if ($users && user()->isAdmin()) {
            foreach ($users as $userId => $user) {
                $nameArray = explode(' ', $user);
                $name = $nameArray[0] . ' ' . $nameArray[1][0];

                $hours = self::getUserMonthlyHours($userId);

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

            session(['all_users_hours' => $allUsersHours]);
        }

        // add all projects first
        $projects = getAllProjects();
        //dd($projects);

        foreach ($projects as $projectId => $name) {

            $projectInstance = Project::firstOrNew([
                'user_id' => user()->id,
                'project_id' => $projectId,
            ]);

            $projectInstance->user_id = user()->id;
            $projectInstance->project_id = $projectId;
            $projectInstance->project_name = $name;

            $projectInstance->save();
        }

        $monthHours = self::getUserMonthlyHours();
        session(['month_hours' => $monthHours]);

        if ($monthHours === 0.0) {
            session(['month_hours' => 'none']);
        }

        self::getUserProjectlyHours(true);
    }
}
