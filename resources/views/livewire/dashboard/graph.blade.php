<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-4">

    <div class="overflow-x-auto relative">

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
                            class="font-bold text-sm font-semibold rounded mx-0">
                            {{$project['project_name']}}
                        </span>
                    </td>
                    <td class="py-2 px-6" style="text-align: right;">
                        <span
                            class="font-bold bg-green-100 text-green-800 text-sm font-semibold mx-0 px-2 py-1 rounded">
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
    <br>

    <x-panel title="Project Wise Distribution Chart">
        <div id="projects_chart">
            @if(session()->has('not_connected'))
                <div class="w-auto inline-block flex justify-center items-center mb-4">
                    <div class="p-3 text-sm text-white break-words flex items-center justify-center text-center rounded-lg bg-red-400">
                        <div>
                            <svg style="margin-bottom: 1px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="w-4 h-4 inline" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                            </svg>

                            We are unable to communicate with Basecamp API !
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-panel>

    @if (count($allUsersHours) && user()->isAdmin())
        <x-panel title="All Users Hours Chart">
            <div id="users_chart">
                @if(session()->has('not_connected'))
                    <div class="w-auto inline-block flex justify-center items-center mb-4">
                        <div class="p-3 text-sm text-white break-words flex items-center justify-center text-center rounded-lg bg-red-400">
                            <div>
                                <svg style="margin-bottom: 1px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="w-4 h-4 inline" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                </svg>

                                We are unable to communicate with Basecamp API !
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-panel>
    @endif

</div>

<script src="/js/charts.js"></script>

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
                    $color = substr(md5($index + mt_rand()), 0, 6);
                    echo "['$project[project_name]', $project[hours], '$color'],\n";
                }
                ?>
            ]);

            // Optional; add a title and set the width and height of the chart
            const options = {
                "legend": "top",
                "title": "",
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

            // Optional; add a title and set the width and height of the chart
            const options = {
                "legend": "none",
                "title": "",
                "vAxis": {title: "Hours"},
                "hAxis": {title: "User", "minValue": 1, "maxValue": 5},
                "height": 500
            };

            // Display the chart inside the <div> element with id="piechart"
            const chart = new google.visualization.ColumnChart(document.querySelector('#users_chart'));
            chart.draw(data, options);
        });
    </script>
@endif
