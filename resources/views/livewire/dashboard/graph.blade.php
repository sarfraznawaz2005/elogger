<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-4">

    <div class="overflow-x-auto relative">

        <strong class="text-gray-600">Project Wise Hours Distribution</strong>

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
    <br><br>

    <div id="piechart"></div>
    <br>

    <x-panel title="All Users Hours Chart">
        <div id="linechart"></div>
    </x-panel>

</div>

@push('css')
    @if(count($projects))
        <style>
            #piechart {
                width: 600px;
                height: 400px;
                margin-left: 175px !important;
            }

            /*   make google charts responsie  */
            @media only screen and (max-width: 600px) {
                #piechart {
                    width: 100%;
                    height: auto;
                    margin-left: 0 !important;
                }
            }
        </style>
    @endif
@endpush

<script src="/js/charts.js"></script>

@if (count($projects))
    <script>
        // Load google charts
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawPieChart);

        function drawPieChart() {
            const data = google.visualization.arrayToDataTable([
                ['Project', 'Hours'],
                <?php
                foreach ($projects as $project) {
                    echo "['$project[project_name]', $project[hours]],\n";
                }
                ?>
            ]);

            // Optional; add a title and set the width and height of the chart
            const options = {
                'title': 'Project Wise Hours Distribution',
                'width': '30%',
                'height': '30%',
                'legend': 'left',
                'chartArea': {
                    left: "0",
                    top: "0",
                    height: "100%",
                    width: "100%"
                }
            };

            // Display the chart inside the <div> element with id="piechart"
            const chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
@endif

@if (count($allUsersHours))
    <script>
        // Load google charts
        google.charts.load('current', {'packages': ['corechart']});

        google.charts.setOnLoadCallback(function () {
            const data = google.visualization.arrayToDataTable([
                ['Person', 'Hours', {role: 'style'}],
                <?php
                foreach ($allUsersHours as $user) {
                    echo "['$user[name]', $user[hours], '$user[color]'],\n";
                }
                ?>
            ]);

            // Optional; add a title and set the width and height of the chart
            const options = {
                "legend": "none",
                "title": "",
                "vAxis": {title: "Hours"},
                "hAxis": {title: "User", "minValue": 1, "maxValue": 5},
                "width": "100%",
                "height": 500
            };

            // Display the chart inside the <div> element with id="piechart"
            const chart = new google.visualization.ColumnChart(document.getElementById('linechart'));
            chart.draw(data, options);
        });

    </script>
@endif
