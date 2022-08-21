<div class="overflow-x-auto relative mb-6" wire:ignore>

    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
        <tr>
            <th scope="col" class="py-3 px-6">Project Name</th>
            <th scope="col" class="py-3 px-6" style="text-align: right;">Total Hours</th>
        </tr>
        </thead>
        <tbody>

        @forelse($projects as $project)
            <tr class="bg-gray-100 border-b">
                <td class="py-2 px-6">
                        <span
                            class="font-bold text-sm font-semibold rounded mx-0 text-gray-600">
                            {{$project['project_name']}}
                        </span>
                </td>
                <td class="py-2 px-6" style="text-align: right;">
                        <span
                            class="font-bold green-light-box text-gray-800 text-sm font-semibold mx-0 px-2 py-1 text-center rounded w-20 inline-block">
                            {{number_format($project['hours'], 2)}}
                        </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="99">
                    <div class="w-auto inline-block flex justify-center items-center bg-gray-100 p-2">
                        <p class="text-sm">
                            You don't have any projects with hours uploaded this month yet.
                        </p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

<div class="mx-auto p-0 border-0 mb-6">
    <div class="bg-gray-200 text-gray-700 pt-3 px-6 font-semibold rounded-t-lg">
        Project Wise Distribution Chart
    </div>

    <div class="p-5 pt-2 bg-gray-200 rounded-b-lg text-gray-800">
        <div id="projects_chart">
            <div class="flex justify-center items-center mb-4" x-data="{show: @entangle('loading')}">
                <div x-show="show">
                    <x-icons.spinner/>
                    <span class="font-semibold">Loading...</span>
                </div>
            </div>

            <x-bc-error/>
        </div>
    </div>
</div>

@if (count($allUsersHours) && user()->isAdmin())
    <div class="mx-auto p-0 border-0">
        <div class="bg-gray-200 text-gray-700 pt-3 px-6 font-semibold rounded-t-lg">
            All Users Hours Chart
        </div>

        <div class="p-5 pt-2 bg-gray-200 rounded-b-lg text-gray-800">
            <div id="users_chart">
                <div class="flex justify-center items-center mb-4" x-data="{show: @entangle('loading')}">
                    <div x-show="show">
                        <x-icons.spinner/>
                        <span class="font-semibold">Loading...</span>
                    </div>
                </div>

                <x-bc-error/>
            </div>
        </div>
    </div>
@endif

<div wire:init="loadCharts">

    <script src="/js/charts.js" defer></script>

    @if (!$loading)

        <script>
            google.charts.load('current', {'packages': ['corechart']});
        </script>

        @if (count($projects))
            <script>
                google.charts.setOnLoadCallback(function () {
                    const data = google.visualization.arrayToDataTable([
                        ['Person', 'Hours', {role: 'style'}],
                        <?php
                        $color = substr(md5(mt_rand()), 0, 6);

                        foreach ($projects as $index => $project) {
                            $color = mt_rand(0, 4) . mt_rand(0, 4) . mt_rand(0, 4);
                            echo "['$project[project_name]', $project[hours], '$color'],\n";
                        }
                        ?>
                    ]);

                    // Optional; add a title and set the width and height of the chart
                    const options = {
                        "legend": "top",
                        "title": "",
                        "animation": {
                            "duration": 2000,
                            "startup": true
                        },
                        "chartArea": {
                            "backgroundColor": "#fff"
                        },
                        "pieHole": 0.4,
                        "vAxis": {title: "Hours"},
                        "hAxis": {title: "Project", "minValue": 1, "maxValue": 5},
                        "height": 500
                    };

                    // Display the chart inside the <div> element with id="piechart"
                    const chart = new google.visualization.PieChart(document.querySelector('#projects_chart'));
                    chart.draw(data, options);
                });
            </script>
        @endif

        @if (count($allUsersHours) && user()->isAdmin())
            <script>
                google.charts.setOnLoadCallback(function () {
                    const data = google.visualization.arrayToDataTable([
                        ['Person', 'Hours', {role: 'style'}],
                        <?php
                        foreach ($allUsersHours as $index => $user) {
                            $color = substr(md5($index + mt_rand()), 0, 6);
                            echo "['$user[name]', $user[hours], '$color'],\n";
                        }
                        ?>
                    ]);

                    const options = {
                        "legend": "none",
                        "title": "",
                        "animation": {
                            "duration": 2000,
                            "startup": true
                        },
                        "chartArea": {
                            "backgroundColor": "#fff"
                        },
                        "vAxis": {title: "Hours"},
                        "hAxis": {title: "User", "minValue": 1, "maxValue": 5},
                        "height": 500
                    };

                    const chart = new google.visualization.ColumnChart(document.querySelector('#users_chart'));
                    chart.draw(data, options);
                });
            </script>
        @endif
    @endif
</div>
