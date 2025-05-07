<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4">
        <!-- Focus on key client -->
        <x-filament::section>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white flex items-center">
                        <span class="mr-2">🌟</span> Client Focus: Sam Gray
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Next follow-up needed {{ now()->addDays(5)->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="mailto:sam@example.com" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                        @svg('heroicon-s-envelope', 'w-5 h-5 -ml-1')
                        Send Email
                    </a>
                    <a href="tel:+15555555555" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 shadow focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                        @svg('heroicon-s-phone', 'w-5 h-5 -ml-1')
                        Call
                    </a>
                    <button type="button" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-green-700 border-green-600 bg-white hover:bg-green-50 dark:text-green-500 dark:border-green-600 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-green-600 shadow">
                        @svg('heroicon-s-check', 'w-5 h-5 -ml-1')
                        Log Contact
                    </button>
                </div>
            </div>
        </x-filament::section>

        <!-- Recent emails -->
        <x-filament::section>
            <x-slot name="heading">Recent Email Threads</x-slot>

            <div class="space-y-3">
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex justify-between">
                        <div class="font-medium">Re: Website redesign proposal</div>
                        <div class="text-sm text-gray-500">Yesterday</div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1">
                        Thanks for the detailed proposal. I've reviewed it with the team and we're interested in moving forward with option B. Can we schedule a call to discuss the timeline and...
                    </p>
                </div>

                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex justify-between">
                        <div class="font-medium">Contract renewal</div>
                        <div class="text-sm text-gray-500">3 days ago</div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1">
                        Just a friendly reminder that our current contract is set to expire at the end of the month. I'd like to discuss renewal options and potentially expanding our...
                    </p>
                </div>
            </div>
        </x-filament::section>

        <!-- Current projects -->
        <x-filament::section>
            <x-slot name="heading">Current Projects</x-slot>

            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-4 py-3">Project</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Deadline</th>
                                <th scope="col" class="px-4 py-3">Next Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 font-medium">Website Redesign</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">In Progress</span>
                                </td>
                                <td class="px-4 py-3">Nov 30, 2025</td>
                                <td class="px-4 py-3">Send wireframes for approval</td>
                            </tr>
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 font-medium">SEO Optimization</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Planning</span>
                                </td>
                                <td class="px-4 py-3">Dec 15, 2025</td>
                                <td class="px-4 py-3">Complete keyword research</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-filament::section>

        <!-- Quick actions row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::section>
                <h3 class="text-lg font-medium">Create Invoice</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Generate a new invoice for this client</p>
                <button type="button" class="w-full inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                    @svg('heroicon-s-document-text', 'w-5 h-5')
                    New Invoice
                </button>
            </x-filament::section>

            <x-filament::section>
                <h3 class="text-lg font-medium">Schedule Meeting</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Book time on the calendar with this client</p>
                <button type="button" class="w-full inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                    @svg('heroicon-s-calendar', 'w-5 h-5')
                    Schedule
                </button>
            </x-filament::section>

            <x-filament::section>
                <h3 class="text-lg font-medium">Add Note</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Record information about this client</p>
                <button type="button" class="w-full inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                    @svg('heroicon-s-pencil', 'w-5 h-5')
                    Add Note
                </button>
            </x-filament::section>
        </div>

        <!-- Documents -->
        <x-filament::section>
            <x-slot name="heading">Client Documents</x-slot>

            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li class="py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        @svg('heroicon-s-document', 'w-5 h-5 text-gray-400 mr-3')
                        <span>2025_Contract_SamGray.pdf</span>
                    </div>
                    <div>
                        <button type="button" class="text-primary-600 dark:text-primary-500 hover:underline">Download</button>
                    </div>
                </li>
                <li class="py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        @svg('heroicon-s-document-chart-bar', 'w-5 h-5 text-gray-400 mr-3')
                        <span>Q3_Performance_Report.xlsx</span>
                    </div>
                    <div>
                        <button type="button" class="text-primary-600 dark:text-primary-500 hover:underline">Download</button>
                    </div>
                </li>
                <li class="py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        @svg('heroicon-s-document-text', 'w-5 h-5 text-gray-400 mr-3')
                        <span>Website_Requirements.docx</span>
                    </div>
                    <div>
                        <button type="button" class="text-primary-600 dark:text-primary-500 hover:underline">Download</button>
                    </div>
                </li>
            </ul>

            <div class="mt-4">
                <button type="button" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 shadow focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                    @svg('heroicon-s-arrow-up-tray', 'w-5 h-5')
                    Upload Document
                </button>
            </div>
        </x-filament::section>

        <!-- Export option at the bottom -->
        <div class="mt-4 text-right">
            <a href="{{ route('clients.export') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-600 shadow-sm focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                @svg('heroicon-s-document-arrow-down', 'w-5 h-5 -ml-1')
                Export Client Data
            </a>
        </div>
    </div>
</x-filament-panels::page>