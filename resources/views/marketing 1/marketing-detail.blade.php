<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center space-x-4">
        <a href="{{ route('marketing.whatsapp') }}" class="inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800 hover:text-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <span>{{ __('Detail Campaign') }}</span>
    </h2>
    </x-slot>
        <div class="max-w-4xl mx-auto my-8 p-8 bg-white border rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold">
                    {{$campaign->campaign_name}}
                </h2>
                <p class="text-gray-500">Created at</p>
                <p class="text-gray-500">{{$campaign->created_at}}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500">Last updated </p>
                <p class="text-gray-500">{{$campaign->updated_at ? $campaign->updated_at : '-'}}</p>
            </div>
        </div>

        <!-- From and To Information -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="font-bold">Start Date</p>
                <p class="text-gray-700">{{$campaign->start_date}}</p>
            </div>

            <div class="text-right">
                <p class="font-bold">End Date</p>
                <p class="text-gray-700">{{$campaign->end_date}}</p>
            </div>
        </div>
        <div class="my-3">
            <p class="font-bold">Message</p>
            <p class="text-gray-700">{{$campaign->description}}</p>
        </div>
        <p class="font-bold py-3">Sent Directly</p>
        @if($unscheduledDetails->isNotEmpty())
        <!-- Projects Table -->
        <table class="min-w-full table-auto mb-6">
            <thead>
                <tr class="border-b">
                    <th class="py-2 text-left text-sm font-semibold text-gray-700">Customer Name</th>
                    <th class="py-2 text-left text-sm font-semibold text-gray-700">Customer Phone</th>
                    <th class="py-2 text-left text-sm font-semibold text-gray-700">Send Date</th>
                    <th class="py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($unscheduledDetails as $detail)
                <tr class="border-b">
                    <td class="py-2 text-sm">{{ $detail->customer_name }}</td>
                    <td class="py-2 text-sm">{{ $detail->customer_phone }}</td>
                    <td class="py-2 text-sm">{{ $detail->send_date }}</td>
                    <td class="py-2 text-sm">{{ $detail->status ? 'Sent' : 'No'}}</td>
                </tr>
            @endforeach
            </tbody>
            </table>
            @else 
            <div>
                <p>Campaign hasn't sent to anyone yet.</p>
            </div>
            @endif
            <p class="font-bold py-3">Scheduled </p>
            @if($scheduledDetails->isNotEmpty())
                <table class="min-w-full table-auto mb-6">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm font-semibold text-gray-700">Customer Name</th>
                        <th class="py-2 text-left text-sm font-semibold text-gray-700">Customer Phone</th>
                        <th class="py-2 text-left text-sm font-semibold text-gray-700">Created Date</th>
                        <th class="py-2 text-left text-sm font-semibold text-gray-700">Scheduled Date</th>
                        <th class="py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($scheduledDetails as $detail)
                    <tr class="border-b">
                        <td class="py-2 text-sm">{{ $detail->customer_name }}</td>
                        <td class="py-2 text-sm">{{ $detail->customer_phone }}</td>
                        <td class="py-2 text-sm">{{ $detail->send_date }}</td>
                        <td class="py-2 text-sm">{{ $detail->scheduled_date }}</td>
                        <td class="py-2 text-sm">{{ $detail->status ? 'Sent' : 'No'}}</td>
                    </tr>
                @endforeach
                </tbody>
                </table>
                @else
                <p>No scheduled campaign</p>
                @endif
            </tbody>
        </table>
</x-app-layout>
