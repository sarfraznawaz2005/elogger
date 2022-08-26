<div class="overflow-x-auto relative mb-6" wire:ignore>

    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
        <tr>
            <th scope="col" class="py-3 px-6">Project Name</th>
            <th scope="col" class="py-3 px-6" style="text-align: right;">Total Hours</th>
        </tr>
        </thead>
        <tbody>

        @forelse($projects as $hours => $projectName)
            <tr class="bg-gray-100 border-b hover:bg-white">
                <td class="py-2 px-6">
                        <span
                            class="font-bold text-sm font-semibold rounded mx-0 text-gray-600">
                            {{$projectName}}
                        </span>
                </td>
                <td class="py-2 px-6" style="text-align: right;">
                        <span
                            class="font-bold gray-box text-gray-800 text-sm font-semibold mx-0 px-2 py-1 text-center rounded w-20 inline-block">
                            {{number_format($hours, 2)}}
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

<style>
.absolute-center {
	position:absolute;
	top: 53%;
	left: 50.3%;
	transform: translate(-50%, -50%);
}
</style>

<div class="mx-auto p-0 border-0 mb-6">
    <div class="bg-gray-200 text-gray-700 pt-3 px-6 font-semibold rounded-t-lg">
        Project Wise Distribution Chart
    </div>

    <div class="p-5 pt-0 bg-gray-200 rounded-b-lg">
        <div class="flex justify-center items-center mb-2" x-data="{show: @entangle('loading')}">
            <div x-show="show">
                <x-icons.spinner/>
                <span class="font-semibold">Loading...</span>
            </div>
        </div>

        <x-bc-error/>

        <div class="bg-gray-50 rounded-lg relative">
            <canvas id="projects_chart" class="p-6" height="400"></canvas>

			<div class="absolute-center text-center gray-box rounded-full p-3 hidden ring-1 ring-gray-300 w-20" id="doughnut_center">
				<p class="text-lg sm:text:sm">Total</p>
				<strong class="font-bold text-lg sm:text:sm">{{round(array_sum(array_keys($projects)))}}</strong>
			</div>
        </div>
    </div>
</div>

@if (count($allUsersHours) && user()->isAdmin())
    <div class="mx-auto p-0 border-0">
        <div class="bg-gray-200 text-gray-700 pt-3 px-6 font-semibold rounded-t-lg">
            All Users Hours Chart
        </div>

        <div class="p-5 pt-0 bg-gray-200 rounded-b-lg">
            <div class="flex justify-center items-center mb-2" x-data="{show: @entangle('loading')}">
                <div x-show="show">
                    <x-icons.spinner/>
                    <span class="font-semibold">Loading...</span>
                </div>
            </div>

            <x-bc-error/>

            <div class="bg-gray-50 rounded-lg">
                <canvas id="users_chart" class="p-6" height="400"></canvas>
            </div>
        </div>
    </div>
@endif

<div wire:init="loadCharts">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2" defer></script>

    @if (!$loading)

        @if (count($projects))
            <script>

                const ctx = document.getElementById('projects_chart');

                new Chart(ctx, {
                    type: 'doughnut',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: {{Js::from(array_values($projects))}},
                        datasets: [{
                            label: '',
                            data: {{Js::from(array_keys($projects))}},
                            borderWidth: 1,
                            backgroundColor: {{Js::from($pieColors)}}
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
							duration: 3000,
							onComplete: function() {document.getElementById("doughnut_center").style.display = "block";}
						},
						scales: {
							y: {
								ticks: {display: false},
								grid: {drawBorder: false}
							}
						},
						plugins: {
							datalabels: {
                                display: true,
                                anchor: 'center',
                                align: 'center',
                                formatter: Math.round,
                                backgroundColor: '#eee',
                                color: '#333',
                                borderRadius: 3,
                                font: {weight: 'bold', size: 14}
							}
						}
                    }
                });
            </script>
        @endif

        @if (count($allUsersHours) && user()->isAdmin())
            <script>
                const ctx = document.getElementById('users_chart');

                new Chart(ctx, {
                    type: 'bar',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: {{Js::from($allUsersHours->pluck('name')->toArray())}},
                        datasets: [{
                            label: 'Hours',
                            fill: true,
                            axis: 'y',
                            data: {{Js::from($allUsersHours->pluck('hours')->toArray())}},
                            borderWidth: 0,
                            backgroundColor: {{Js::from($barColors)}},
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {duration: 3000},
						plugins: {
							datalabels: {
								anchor: 'end',
								align: 'top',
								formatter: Math.round,
								color:'#777',
								font: {weight: 'bold', size: 15}
							}
						}
                    }
                });
            </script>
        @endif

    @endif
</div>
