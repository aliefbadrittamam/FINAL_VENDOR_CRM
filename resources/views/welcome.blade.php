<x-guest-layout>
  
  <div class="bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-16 md:py-20 lg:flex lg:items-center lg:justify-between">

      <!-- Text Content -->
      <div class="max-w-2xl mx-auto lg:mx-0 lg:max-w-lg lg:pr-8">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl lg:text-6xl">
          Simplify Your CRM Management
        </h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 sm:mt-6 sm:text-xl">
          Empower your business with cutting-edge CRM tools designed to streamline customer interactions, manage leads, and drive growth.
        </p>
        <div class="mt-6 flex flex-wrap gap-4">
          <a
            href="{{ route('login') }}"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
          >
            Get Started
          </a>
          <a
            href="{{ route('contact') }}"
            class="bg-gray-200 text-gray-900 px-6 py-3 rounded-lg hover:bg-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
          >
            Learn More
          </a>
        </div>
      </div>

      <!-- Image/Illustration -->
      <div class="mt-10 lg:mt-0 lg:flex lg:justify-end lg:pl-8">
        <div class="w-full max-w-md mx-auto lg:max-w-lg">
          <img
            src="{{ asset('img/visual_data.svg') }}"
            alt="CRM illustration"
            class="rounded-lg shadow-lg"
          />
        </div>
      </div>
    </div>

    <div class="text-center pt-20 p-8">
      <blockquote class="text-xl italic font-semibold text-gray-900 dark:text-white">
        <svg class="w-8 h-8 text-gray-400 dark:text-gray-600 mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 14">
            <path d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3h-1a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z"/>
        </svg>
        <p>"Choosing the right CRM vendor is not just about the software, it's about finding a partner who understands your business goals and evolves with your growth."</p>
      </blockquote>
    </div>
    
  </div>
  
      
</x-guest-layout>