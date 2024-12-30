<div class="p-6">
    <h3 class="text-lg font-semibold mb-4">Recent Projects</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Project
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Vendor
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Value
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($projects as $project)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->project_header }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->vendor->vendor_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        Rp {{ number_format($project->project_value) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($project->project_duration_end < now())
                                bg-green-100 text-green-800
                            @else
                                bg-blue-100 text-blue-800
                            @endif">
                            {{ $project->project_duration_end < now() ? 'Completed' : 'On Progress' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>