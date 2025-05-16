@props(['animated' => true])

@php
$journey = [
    [
        'date' => 'Apr 2025 - Present',
        'title' => 'Senior Software Engineer',
        'subtitle' => 'PSTrax',
        'content' => '<p>Focusing on developing cutting-edge solutions for emergency service teams using Laravel and modern technologies. Leading development initiatives and implementing industry best practices.</p>',
        'tags' => ['Laravel', 'Fullstack', 'PhpStorm', 'Automation'],
        'color' => 'primary'
    ],
    [
        'date' => 'Jan 2023 - Apr 2025',
        'title' => 'Manager, Software Engineering',
        'subtitle' => 'Goodwill of Central and Northern Arizona',
        'content' => '
            <p>Led a team in developing and maintaining a career services website using PHP and Laravel. Implemented key features:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Team Leadership: Directed development team building user-facing platforms</li>
                <li>Bridging Design and Engineering: Facilitated communication between designers and engineers</li>
                <li>Mentorship: Guided engineers on best practices for clean, reusable, and well-tested code</li>
                <li>Agile Methodologies: Enhanced project delivery and team efficiency</li>
            </ul>
        ',
        'tags' => ['Team Leadership', 'PHP', 'Laravel', 'Agile', 'Mentorship'],
        'color' => 'secondary'
    ],
    [
        'date' => 'May 2022 - Jan 2023',
        'title' => 'Sr Software Engineer',
        'subtitle' => 'Goodwill of Central and Northern Arizona',
        'content' => '
            <p>Played a key role in building career services platform utilized by various Goodwill regions nationwide:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Full Stack Development: Built robust solutions using PHP, Laravel, Vue, and modern front-end technologies</li>
                <li>Code Quality: Conducted comprehensive code reviews ensuring high standards</li>
                <li>Agile Process: Served as Scrum Master for remote team, optimizing workflows</li>
                <li>Cross-Functional Collaboration: Worked with designers and product managers to enhance user experience</li>
            </ul>
        ',
        'tags' => ['PHP', 'Laravel', 'Vue.js', 'Scrum Master', 'Code Reviews'],
        'color' => 'teal'
    ],
    [
        'date' => 'Aug 2021 - May 2022',
        'title' => 'Software Engineer',
        'subtitle' => 'Goodwill of Central and Northern Arizona',
        'content' => '
            <p>Contributed to the development of career services platform:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Full Stack Development: Maintained solutions using PHP, Laravel, and front-end technologies</li>
                <li>Code Reviews: Participated in code reviews ensuring quality and best practices</li>
                <li>Agile Team Member: Collaborated with team members to optimize workflows</li>
                <li>Feature Implementation: Worked with cross-functional teams to implement enhancements</li>
            </ul>
        ',
        'tags' => ['Laravel', 'PHP', 'Agile', 'Feature Development'],
        'color' => 'blue'
    ],
    [
        'date' => 'Jan 2019 - Apr 2019',
        'title' => 'Programmer Analyst',
        'subtitle' => 'Insight Global',
        'content' => '<p>Worked as a consultant programmer analyst developing and maintaining web applications.</p>',
        'tags' => ['PhpStorm', 'Automation', 'Web Development'],
        'color' => 'indigo'
    ],
    [
        'date' => 'Aug 2017 - Dec 2018',
        'title' => 'Backend Developer',
        'subtitle' => 'atmosol',
        'content' => '
            <p>Focused on e-commerce platform development:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Platform Development: Built and maintained e-commerce platforms using BigCommerce and Magento</li>
                <li>Collaboration: Worked with marketing teams and designers to create cohesive brand experiences</li>
                <li>Resource Management: Managed resource allocation for billable hours</li>
                <li>Client Relations: Fostered strong client relationships by providing tailored technical solutions</li>
            </ul>
        ',
        'tags' => ['BigCommerce', 'Magento', 'E-Commerce', 'Client Relations'],
        'color' => 'purple'
    ],
    [
        'date' => 'May 2016 - Jul 2017',
        'title' => 'Software Developer',
        'subtitle' => 'Insight Global',
        'content' => '<p>Worked as a consultant for Rural/Metro AMR maintaining web development and administration responsibilities. Also served as SharePoint and Office 365 administrator.</p>',
        'tags' => ['Web Development', 'SharePoint', 'Office 365', 'Administration'],
        'color' => 'pink'
    ]
];
@endphp

<section id="journey" class="py-20 relative">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <h2 class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-6 inline-block">
                Professional Journey
            </h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg max-w-3xl mx-auto">
                A career built on combining technical expertise with strategic vision to deliver exceptional software solutions.
            </p>
        </div>

        <!-- Timeline implementation -->
        <div class="mt-12 relative">
            <!-- Vertical line -->
            <div class="absolute left-5 md:left-1/2 top-0 h-full w-0.5 bg-primary-500 dark:bg-primary-400 transform -translate-x-1/2"></div>

            <div class="space-y-16">
                @foreach ($journey as $index => $item)
                    @php
                        $color = $item['color'] ?? ($index % 2 == 0 ? 'primary' : 'secondary');
                        $side = $index % 2 == 0 ? 'right' : 'left';
                    @endphp

                    <div class="relative">
                        <!-- Dot on the timeline -->
                        <div class="absolute left-5 md:left-1/2 top-5 w-5 h-5 rounded-full bg-{{ $color }}-500 dark:bg-{{ $color }}-400 shadow-md transform -translate-x-1/2 z-10"></div>

                        <div class="flex flex-col md:flex-row items-center">
                            <!-- Date on left side for desktop -->
                            <div class="md:w-1/2 mb-4 md:mb-0 md:pr-12 text-right hidden md:block">
                                @if ($side == 'right')
                                    <span class="inline-block px-4 py-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-lg font-semibold shadow-sm">
                                        {{ $item['date'] ?? '' }}
                                    </span>
                                    <div class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $item['title'] ?? '' }}</div>
                                    <div class="text-gray-600 dark:text-gray-300">{{ $item['subtitle'] ?? '' }}</div>
                                @endif
                            </div>

                            <!-- Content container -->
                            <div class="md:w-1/2 {{ $side == 'right' ? 'ml-12 md:ml-0 md:pl-12' : 'ml-12 md:ml-0 md:pr-12 md:text-right' }}">
                                <!-- Mobile date display -->
                                <div class="md:hidden mb-2">
                                    <span class="inline-block px-4 py-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-lg font-semibold shadow-sm">
                                        {{ $item['date'] ?? '' }}
                                    </span>
                                    <div class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $item['title'] ?? '' }}</div>
                                    <div class="text-gray-600 dark:text-gray-300">{{ $item['subtitle'] ?? '' }}</div>
                                </div>

                                <!-- Content card -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                                    <div class="prose prose-sm dark:prose-invert max-w-none">
                                        {!! $item['content'] ?? '' !!}
                                    </div>

                                    @if (isset($item['tags']) && is_array($item['tags']) && count($item['tags']) > 0)
                                        <div class="mt-4 flex flex-wrap gap-2 {{ $side == 'left' ? 'md:justify-end' : '' }}">
                                            @foreach ($item['tags'] as $tag)
                                                <span class="px-3 py-1 text-xs font-medium bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-full">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Date on right side -->
                            <div class="md:w-1/2 mt-4 md:mt-0 md:pl-12 hidden md:block">
                                @if ($side == 'left')
                                    <span class="inline-block px-4 py-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-lg font-semibold shadow-sm">
                                        {{ $item['date'] ?? '' }}
                                    </span>
                                    <div class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $item['title'] ?? '' }}</div>
                                    <div class="text-gray-600 dark:text-gray-300">{{ $item['subtitle'] ?? '' }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Core Competencies Section -->
        <div class="mt-24 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Core Competencies</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                    <div class="w-14 h-14 mx-auto mb-4 bg-green-500 dark:bg-green-600 rounded-full flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Technical Leadership</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-center">
                        Guiding teams through complex technical challenges with modern Laravel solutions and architecture.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                    <div class="w-14 h-14 mx-auto mb-4 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Team Development</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-center">
                        Mentoring engineers and bridging the gap between design, product, and engineering teams.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                    <div class="w-14 h-14 mx-auto mb-4 bg-purple-500 dark:bg-purple-600 rounded-full flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Quality-Focused</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-center">
                        Implementing code reviews, automated testing, and CI/CD pipelines to maintain high-quality standards.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>