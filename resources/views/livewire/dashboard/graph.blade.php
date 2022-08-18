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
                            class="font-bold text-sm font-semibold rounded mx-0">
                            {{$project['project_name']}}
                        </span>
                </td>
                <td class="py-2 px-6" style="text-align: right;">
                        <span
                            class="font-bold bg-green-200 text-gray-700 text-sm font-semibold mx-0 px-2 py-1 text-center rounded w-20 inline-block">
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
    <div class="bg-gray-200 text-gray-500 pt-3 px-6 font-semibold rounded-t-lg">
        Project Wise Distribution Chart
    </div>

    <div class="p-5 pt-2 bg-gray-200 rounded-b-lg text-gray-800">
        <div id="projects_chart">
            <div class="flex justify-center items-center mb-4" x-data="{show: @entangle('loading')}">
                <div x-show="show">
                    <svg class="inline w-8 h-8 text-gray-600 animate-spin mr-2" viewBox="0 0 100 101" fill="blue" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="font-semibold">Loading...</span>
                </div>
            </div>

            <x-bc-error />
        </div>
    </div>
</div>

@if (count($allUsersHours) && user()->isAdmin())
    <div class="mx-auto p-0 border-0">
        <div class="bg-gray-200 text-gray-500 pt-3 px-6 font-semibold rounded-t-lg">
            All Users Hours Chart
        </div>

        <div class="p-5 pt-2 bg-gray-200 rounded-b-lg text-gray-800">
            <div id="users_chart">
                <div class="flex justify-center items-center mb-4" x-data="{show: @entangle('loading')}">
                    <div x-show="show">
                        <svg class="inline w-8 h-8 text-gray-600 animate-spin mr-2" viewBox="0 0 100 101" fill="blue" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="font-semibold">Loading...</span>
                    </div>
                </div>

                <x-bc-error />
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
    @endif
</div>
