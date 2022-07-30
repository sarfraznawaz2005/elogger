<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 my-4">

    <div class="overflow-x-auto relative">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">Project Name</th>
                <th scope="col" class="py-3 px-6" style="text-align: right;">Total Hours</th>
            </tr>
            </thead>
            <tbody>

            @foreach($projects as $project)
                <tr class="bg-gray-100 border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="py-2 px-6">
                        <span
                            class="font-bold text-sm font-semibold rounded mx-0">
                            {{$project['project_name']}}
                        </span>
                    </td>
                    <td class="py-2 px-6" style="text-align: right;">
                        <span
                            class="font-bold bg-green-100 text-green-800 text-sm font-semibold mx-0 px-2 py-1 rounded dark:bg-green-200 dark:text-green-900">
                            {{number_format($project['hours'], 2)}}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br><br>

    <div id="piechart"></div>
    <div id="linechart"></div>

</div>

@push('styles')
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
@endpush

@if (count($projects))

    <script src="https://www.gstatic.com/charts/loader.js"></script>

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

        @if ($allUsersHours && user()->isAdmin())
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
                "title": "All Users Hours",
                "vAxis": {title: "Hours"},
                "hAxis": {title: "User", "minValue": 1, "maxValue": 5},
                "width": "100%",
                "height": 500
            };

            // Display the chart inside the <div> element with id="piechart"
            const chart = new google.visualization.ColumnChart(document.getElementById('linechart'));
            chart.draw(data, options);
        });

        @endif

        function random_rgba() {
            const o = Math.round, r = Math.random, s = 255;
            return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + r().toFixed(1) + ')';
        }
    </script>

@endif
