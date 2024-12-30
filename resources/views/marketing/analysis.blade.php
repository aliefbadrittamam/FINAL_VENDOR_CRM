    
<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analysis Campaign') }}
            </h2>
            <div class="flex space-x-4">
            <div>
                <a href="/generate">
                <button type="button" class="mt-7 bg-green-500 text-white hover:bg-green-700 hover:text-white rounded px-4 py-2 inline-flex items-center">
                <!-- <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.07,8A8,8,0,0,1,20,12"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.93,16A8,8,0,0,1,4,12"></path>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5 3 5 8 10 8"></polyline>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="19 21 19 16 14 16"></polyline>
                </svg> -->
                    <svg class="w-5 h-5 inline-block mr-1" fill="#ffffff" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier"> <title>export-16px</title> 
                        <g id="Layer_2" data-name="Layer 2"> <g id="Layer_1-2" data-name="Layer 1"> 
                            <path d="M16,9.5v4A2.5,2.5,0,0,1,13.5,16H2.5A2.5,2.5,0,0,1,0,13.5V2.5A2.5,2.5,0,0,1,2.5,0h3A.5.5,0,0,1,6,.5a.5.5,0,0,1-.5.5h-3A1.5,1.5,0,0,0,1,2.5v11A1.5,1.5,0,0,0,2.5,15h11A1.5,1.5,0,0,0,15,13.5v-4a.5.5,0,0,1,1,0ZM5,9.5a.5.5,0,0,0,1,0v-2A3.5,3.5,0,0,1,9.5,4h4.79L12.15,6.15a.48.48,0,0,0,0,.7.48.48,0,0,0,.7,0l3-3A.36.36,0,0,0,16,3.69a.5.5,0,0,0,0-.38.36.36,0,0,0-.11-.16l-3-3a.48.48,0,0,0-.7,0,.48.48,0,0,0,0,.7L14.29,3H9.5A4.51,4.51,0,0,0,5,7.5Z"></path> 
                        </g> 
                        </g> 
                        </g>
                            </svg>
                        Export
                    </button>
                </a>   
        </div>

            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:marketing.analysis /> 
        </div>
    </div>
</x-app-layout>