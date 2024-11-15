<!-- resources/views/components/project-showcase.blade.php -->
<div class="relative min-h-[600px] w-full"
     x-data="{
        activeProjects: [],
        initializeProjects() {
            const projectsData = {{ Js::from($projects) }};
            this.activeProjects = projectsData.map((project, index) => ({
                ...project,
                offsetX: index * 30,
                offsetY: index * 30,
                zIndex: 1000 + index,
                isMinimized: false,
                isDragging: false,
                isLoading: true
            }));
        }
     }"
     x-init="initializeProjects">

    <template x-for="(project, index) in activeProjects" :key="index">
        <div class="absolute bg-white dark:bg-gray-800 rounded-lg shadow-2xl overflow-hidden transition-shadow"
             :class="project.isMinimized ? 'h-12' : 'w-[800px]'"
             :style="`transform: translate(${project.offsetX}px, ${project.offsetY}px); z-index: ${project.zIndex};`"
             @mousedown="project.zIndex = Math.max(...activeProjects.map(p => p.zIndex)) + 1;">

            <!-- Window Header -->
            <div :class="project.headerClass || 'bg-gradient-to-r from-gray-700 to-gray-900'"
                 class="p-3 cursor-move flex items-center justify-between"
                 @mousedown.prevent="
                    project.isDragging = true;
                    const startX = $event.pageX - project.offsetX;
                    const startY = $event.pageY - project.offsetY;

                    const mouseMoveHandler = (e) => {
                        project.offsetX = e.pageX - startX;
                        project.offsetY = e.pageY - startY;
                    };

                    const mouseUpHandler = () => {
                        project.isDragging = false;
                        window.removeEventListener('mousemove', mouseMoveHandler);
                        window.removeEventListener('mouseup', mouseUpHandler);
                    };

                    window.addEventListener('mousemove', mouseMoveHandler);
                    window.addEventListener('mouseup', mouseUpHandler);
                 ">
                <h3 class="text-white font-bold" x-text="project.headerContent ? project.headerContent.title : project.name"></h3>
                <div class="flex space-x-2">
                    <button @click="project.isMinimized = !project.isMinimized"
                            class="w-4 h-4 rounded-full bg-yellow-500 hover:bg-yellow-600 transition-colors">
                    </button>
                    <button @click="activeProjects = activeProjects.filter((p, i) => i !== index)"
                            class="w-4 h-4 rounded-full bg-red-500 hover:bg-red-600 transition-colors">
                    </button>
                </div>
            </div>

            <!-- Window Content -->
            <div x-show="!project.isMinimized"
                 x-collapse
                 class="p-6 space-y-4">
                <!-- Show subtitle if available -->
                <div x-show="project.headerContent && project.headerContent.subtitle"
                     class="text-sm text-gray-500 dark:text-gray-400 mb-2"
                     x-text="project.headerContent.subtitle">
                </div>

                <!-- iframe Container -->
                <div class="relative w-full h-[400px] bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden">
                    <!-- Loading Indicator -->
                    <div x-show="project.isLoading"
                         class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-900">
                        <svg class="animate-spin h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Project iframe -->
                    <iframe :src="project.url"
                            class="w-full h-full"
                            @load="project.isLoading = false"
                            sandbox="allow-same-origin allow-scripts"
                            referrerpolicy="no-referrer">
                    </iframe>
                </div>

                <p class="text-gray-600 dark:text-gray-300" x-text="project.description"></p>

                <!-- Project Links -->
                <div class="flex space-x-4">
                    <a :href="project.url"
                       target="_blank"
                       class="inline-flex items-center space-x-2 text-blue-500 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>Open in New Tab</span>
                    </a>
                </div>

                <!-- Technologies -->
                <div x-show="project.technologies" class="pt-4">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="tech in project.technologies" :key="tech.name">
                            <span :class="`px-2 py-1 bg-${tech.color}-100 text-${tech.color}-800 rounded-full text-sm`"
                                  x-text="tech.name"></span>
                        </template>
                    </div>
                </div>

                <!-- Contributions if available -->
                <div x-show="project.contributions" class="pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Key Contributions:</h4>
                    <ul class="space-y-1">
                        <template x-for="contribution in project.contributions" :key="contribution">
                            <li class="text-sm text-gray-600 dark:text-gray-300" x-text="contribution"></li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </template>
</div>
