<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laride Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        skytint: '#fce7f3',
                        sky: '#ec4899',
                        skydeep: '#be185d',
                        cloud: '#fdf2f8',
                        ink: '#500724'
                    },
                    fontFamily: {
                        display: ['Quicksand', 'sans-serif'],
                        body: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #fbcfe8 0%, #f472b6 22%, #fbcfe8 45%, #fdf2f8 70%, #ffffff 100%);
            background-attachment: fixed;
        }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #f472b6; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #ec4899; }

        .blob {
            position: fixed;
            border-radius: 999px;
            filter: blur(80px);
            opacity: 0.28;
            z-index: 0;
            pointer-events: none;
        }

        /* Drifting heart shapes adapted to Barbie glam theme */
        .heart-shape {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            opacity: 0.55;
            filter: drop-shadow(0 10px 20px rgba(190, 24, 93, 0.08));
            animation: drift 40s ease-in-out infinite;
        }
        .heart-shape.slow { animation-duration: 55s; opacity: 0.4; }
        .heart-shape.reverse { animation-direction: reverse; }
        @keyframes drift {
            0%   { transform: translateX(0); }
            50%  { transform: translateX(30px); }
            100% { transform: translateX(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .heart-shape { animation: none; }
        }

        .task-card { aspect-ratio: 1 / 1; }

        /* --- Hover dominance & balance --- */
        #taskGrid .task-card {
            transition: transform 0.28s cubic-bezier(.2,.8,.2,1), box-shadow 0.28s ease, opacity 0.28s ease, filter 0.28s ease;
        }
        #taskGrid:hover .task-card {
            transform: scale(0.94);
            opacity: 0.65;
            filter: saturate(0.85);
        }
        #taskGrid .task-card:hover {
            transform: scale(1.07);
            opacity: 1;
            filter: saturate(1.1);
            box-shadow: 0 20px 34px -12px rgba(190, 24, 93, 0.30);
            z-index: 10;
            position: relative;
        }

        .circle-btn { transition: transform 0.15s ease, background-color 0.15s ease, color 0.15s ease; }
        .circle-btn:hover { transform: translateY(-2px); }
        .circle-btn:active { transform: translateY(0) scale(0.95); }
    </style>
</head>
<body class="text-ink min-h-screen flex flex-col md:flex-row relative overflow-x-hidden">

    <!-- Decorative background: soft glow blobs + floating hearts -->
    <div class="blob w-96 h-96 bg-sky -top-24 -left-24"></div>
    <div class="blob w-96 h-96 bg-skydeep top-1/2 -right-24"></div>
    <div class="blob w-72 h-72 bg-white bottom-0 left-1/3"></div>

    <svg class="heart-shape w-10 top-10 left-[8%]" viewBox="0 0 32 29.6" fill="white" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 27.6s-14-8.8-14-16.6C2 6 6 2 10.6 2c3.4 0 5.4 2 5.4 2s2-2 5.4-2C26 2 30 6 30 11c0 7.8-14 16.6-14 16.6z"/>
    </svg>
    <svg class="heart-shape slow reverse w-14 top-[22%] right-[6%]" viewBox="0 0 32 29.6" fill="white" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 27.6s-14-8.8-14-16.6C2 6 6 2 10.6 2c3.4 0 5.4 2 5.4 2s2-2 5.4-2C26 2 30 6 30 11c0 7.8-14 16.6-14 16.6z"/>
    </svg>
    <svg class="heart-shape w-8 top-[55%] left-[18%]" viewBox="0 0 32 29.6" fill="white" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 27.6s-14-8.8-14-16.6C2 6 6 2 10.6 2c3.4 0 5.4 2 5.4 2s2-2 5.4-2C26 2 30 6 30 11c0 7.8-14 16.6-14 16.6z"/>
    </svg>
    <svg class="heart-shape slow w-12 bottom-[10%] right-[16%]" viewBox="0 0 32 29.6" fill="white" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 27.6s-14-8.8-14-16.6C2 6 6 2 10.6 2c3.4 0 5.4 2 5.4 2s2-2 5.4-2C26 2 30 6 30 11c0 7.8-14 16.6-14 16.6z"/>
    </svg>

    <!-- Sidebar Navigation -->
    <aside class="relative z-10 w-full md:w-64 bg-white/70 backdrop-blur-md border-b md:border-b-0 md:border-r border-sky/20 flex flex-col justify-between shrink-0 md:h-screen md:sticky md:top-0">
        <div>
            <!-- App Branding -->
            <div class="h-24 flex items-center px-6 border-b border-sky/20 gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky to-skydeep flex items-center justify-center text-white shadow-lg shadow-sky/30 shrink-0">
                    <i class="fa-solid fa-gem text-base"></i>
                </div>
                <div>
                    <h1 class="font-display font-extrabold text-2xl leading-tight text-skydeep tracking-tight">Laride</h1>
                    <span class="text-xs text-sky font-medium tracking-wide">Task Manager</span>
                </div>
            </div>

            <!-- Navigation Links (circular pills) -->
            <nav class="p-4 space-y-2">
                <a href="#" onclick="filterTasks('all')" id="nav-all" class="flex items-center gap-3 px-5 py-3 rounded-full text-sm font-medium bg-gradient-to-r from-sky to-skydeep text-white shadow-md shadow-sky/30 transition-all">
                    <span class="w-6 h-6 rounded-full bg-white/25 flex items-center justify-center"><i class="fa-solid fa-house text-[11px]"></i></span> All Tasks <span id="count-all" class="ml-auto w-6 h-6 rounded-full bg-white/25 flex items-center justify-center text-xs font-semibold">0</span>
                </a>
                <a href="#" onclick="filterTasks('pending')" id="nav-pending" class="flex items-center gap-3 px-5 py-3 rounded-full text-sm font-medium text-[#9d174d] hover:bg-skytint transition-all">
                    <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center"><i class="fa-solid fa-clock text-[11px]"></i></span> Pending <span id="count-pending" class="ml-auto w-6 h-6 rounded-full bg-skytint flex items-center justify-center text-xs font-semibold text-skydeep">0</span>
                </a>
                <a href="#" onclick="filterTasks('completed')" id="nav-completed" class="flex items-center gap-3 px-5 py-3 rounded-full text-sm font-medium text-[#9d174d] hover:bg-skytint transition-all">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-circle-check text-[11px]"></i></span> Completed <span id="count-completed" class="ml-auto w-6 h-6 rounded-full bg-skytint flex items-center justify-center text-xs font-semibold text-skydeep">0</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer profile -->
        <div class="p-4 border-t border-sky/20">
            <div class="flex items-center gap-3 p-3 rounded-full bg-skytint/60">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-skydeep to-sky text-white flex items-center justify-center font-semibold text-sm shrink-0">
                    LA
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-ink truncate">Laride</p>
                    <p class="text-xs text-[#9d174d] truncate">Task Manager</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 flex flex-col min-w-0">

        <!-- Top Header Bar -->
        <header class="h-24 bg-white/50 backdrop-blur-md border-b border-sky/20 px-6 md:px-10 flex items-center justify-between gap-4 sticky top-0 z-10">
            <div class="flex items-center gap-4 flex-1 max-w-xl">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-sky">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Find a task..." class="w-full border-2 border-skytint bg-white rounded-full pl-11 pr-4 py-3 text-sm placeholder-sky/60 focus:outline-none focus:border-sky transition-colors">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <select id="priorityFilter" onchange="applyFilters()" class="border-2 border-skytint bg-white rounded-full px-4 py-2.5 text-xs text-[#9d174d] focus:outline-none focus:border-sky transition-colors hidden sm:block">
                    <option value="all">All Priorities</option>
                    <option value="High">High Priority</option>
                    <option value="Medium">Medium Priority</option>
                    <option value="Low">Low Priority</option>
                </select>
                <button onclick="openCreateModal()" class="circle-btn w-12 h-12 rounded-full bg-gradient-to-br from-sky to-skydeep text-white flex items-center justify-center shadow-lg shadow-sky/30" title="New Task">
                    <i class="fa-solid fa-plus text-sm"></i>
                </button>
            </div>
        </header>

        <!-- Dynamic Flash Notification Banner -->
        <div id="flashMessage" class="hidden mx-6 md:mx-10 mt-6 p-4 rounded-full border-2 border-skytint bg-white flex items-center gap-3 text-sm shadow-sm transition-all duration-300">
            <i id="flashIcon" class="fa-solid text-base"></i>
            <span id="flashText" class="font-medium"></span>
        </div>

        <!-- Dashboard View Container -->
        <div class="p-6 md:p-10 space-y-8 flex-1 max-w-7xl w-full mx-auto">

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white/80 border-2 border-skytint rounded-3xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-sky to-skydeep text-white flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#9d174d] font-semibold mb-0.5">Total</p>
                        <h3 id="statTotal" class="text-2xl font-bold text-ink">0</h3>
                    </div>
                </div>
                <div class="bg-white/80 border-2 border-skytint rounded-3xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-400 to-pink-500 text-white flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#9d174d] font-semibold mb-0.5">Pending</p>
                        <h3 id="statPending" class="text-2xl font-bold text-ink">0</h3>
                    </div>
                </div>
                <div class="bg-white/80 border-2 border-skytint rounded-3xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#9d174d] font-semibold mb-0.5">Completed</p>
                        <h3 id="statCompleted" class="text-2xl font-bold text-ink">0</h3>
                    </div>
                </div>
            </div>

            <!-- Task Registry -->
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2">
                        Task Registry <span id="currentFilterBadge" class="text-xs px-3 py-1 rounded-full bg-skytint text-skydeep font-medium">All Tasks</span>
                    </h2>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="hidden py-24 text-center border-2 border-dashed border-skytint rounded-3xl bg-white/60">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-skytint flex items-center justify-center text-sky text-xl">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h3 class="font-display font-semibold text-sm text-skydeep">No tasks yet</h3>
                    <p class="text-xs text-[#9d174d] mt-1">Add your first task using the button above.</p>
                </div>

                <!-- Task Grid -->
                <div id="taskGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>

        </div>
    </main>

    <!-- Add/Edit Task Modal Dialog -->
    <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#500724]/30 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white border-2 border-skytint w-full max-w-lg rounded-[2rem] overflow-hidden transform scale-95 transition-transform duration-200 shadow-2xl shadow-sky/20" id="modalCard">
            <div class="flex items-center justify-between px-6 py-5 border-b border-skytint bg-gradient-to-r from-skytint/70 to-cloud">
                <h3 id="modalTitle" class="font-display font-bold text-ink text-base flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-sky"><i class="fa-solid fa-circle-plus text-xs"></i></span> Create New Task
                </h3>
                <button onclick="closeModal()" class="w-9 h-9 rounded-full bg-white text-sky hover:text-skydeep flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <form id="taskForm" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4">
                <input type="hidden" id="taskId">
                <div>
                    <label class="block text-xs font-semibold text-[#9d174d] mb-1.5">Task Title *</label>
                    <input type="text" id="taskTitle" required placeholder="e.g., Plan dream outfit schedule..." class="w-full border-2 border-skytint rounded-full px-5 py-2.5 text-sm placeholder-sky/50 focus:outline-none focus:border-sky transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#9d174d] mb-1.5">Description</label>
                    <textarea id="taskDesc" rows="3" placeholder="Add extra context, checklists, or specifications..." class="w-full border-2 border-skytint rounded-3xl px-5 py-3 text-sm placeholder-sky/50 focus:outline-none focus:border-sky transition-colors resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#9d174d] mb-1.5">Priority Level</label>
                        <select id="taskPriority" class="w-full border-2 border-skytint rounded-full px-5 py-2.5 text-sm focus:outline-none focus:border-sky transition-colors">
                            <option value="Low">Low Priority</option>
                            <option value="Medium" selected>Medium Priority</option>
                            <option value="High">High Priority</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#9d174d] mb-1.5">Due Date *</label>
                        <input type="date" id="taskDueDate" required class="w-full border-2 border-skytint rounded-full px-5 py-2.5 text-sm focus:outline-none focus:border-sky transition-colors">
                    </div>
                </div>
                <div class="pt-4 border-t border-skytint flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-full text-[#9d174d] hover:text-skydeep font-medium text-sm transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-sky to-skydeep text-white font-medium text-sm shadow-md shadow-sky/30 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Application Logic Script -->
    <script>
        let tasks = [];
        let currentFilter = 'all';
        let currentSearchQuery = '';

        window.onload = function() {
            const todayStr = new Date().toISOString().split('T')[0];
            document.getElementById('taskDueDate').min = todayStr;
            renderApp();
        };

        function renderApp() {
            updateStats();
            renderTaskGrid();
            updateSidebarCounts();
        }

        function updateStats() {
            document.getElementById('statTotal').innerText = tasks.length;
            document.getElementById('statPending').innerText = tasks.filter(t => t.status === 'pending').length;
            document.getElementById('statCompleted').innerText = tasks.filter(t => t.status === 'completed').length;
        }

        function updateSidebarCounts() {
            document.getElementById('count-all').innerText = tasks.length;
            document.getElementById('count-pending').innerText = tasks.filter(t => t.status === 'pending').length;
            document.getElementById('count-completed').innerText = tasks.filter(t => t.status === 'completed').length;
        }

        function filterTasks(filter) {
            currentFilter = filter;

            const activeClass = "flex items-center gap-3 px-5 py-3 rounded-full text-sm font-medium bg-gradient-to-r from-sky to-skydeep text-white shadow-md shadow-sky/30 transition-all";
            const inactiveClass = "flex items-center gap-3 px-5 py-3 rounded-full text-sm font-medium text-[#9d174d] hover:bg-skytint transition-all";

            ['all', 'pending', 'completed'].forEach(f => {
                const el = document.getElementById(`nav-${f}`);
                el.className = (f === filter) ? activeClass : inactiveClass;
            });

            const badgeNames = { all: 'All Tasks', pending: 'Pending Tasks', completed: 'Completed Tasks' };
            document.getElementById('currentFilterBadge').innerText = badgeNames[filter];

            renderTaskGrid();
        }

        function handleSearch() {
            currentSearchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
            renderTaskGrid();
        }

        function applyFilters() {
            renderTaskGrid();
        }

        function getFilteredTasks() {
            const priorityVal = document.getElementById('priorityFilter').value;
            return tasks.filter(task => {
                if (currentFilter !== 'all' && task.status !== currentFilter) return false;
                if (priorityVal !== 'all' && task.priority !== priorityVal) return false;
                if (currentSearchQuery && !task.title.toLowerCase().includes(currentSearchQuery) && !task.description.toLowerCase().includes(currentSearchQuery)) return false;
                return true;
            });
        }

        function renderTaskGrid() {
            const filtered = getFilteredTasks();
            const grid = document.getElementById('taskGrid');
            const emptyState = document.getElementById('emptyState');

            grid.innerHTML = '';

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
                grid.classList.add('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
                grid.classList.remove('hidden');
            }

            filtered.forEach(task => {
                let priorityColor = 'text-sky';
                let priorityBg = 'bg-skytint';
                if (task.priority === 'High') { priorityColor = 'text-rose-600'; priorityBg = 'bg-rose-100'; }
                if (task.priority === 'Medium') { priorityColor = 'text-pink-500'; priorityBg = 'bg-pink-100'; }
                if (task.priority === 'Low') { priorityColor = 'text-emerald-500'; priorityBg = 'bg-emerald-100'; }

                const isCompleted = task.status === 'completed';
                const statusBadge = isCompleted
                    ? `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600"><i class="fa-solid fa-check text-[10px]"></i> Completed</span>`
                    : `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-600"><i class="fa-solid fa-clock text-[10px]"></i> Pending</span>`;

                const card = document.createElement('div');
                card.className = "task-card bg-white border-2 border-skytint rounded-[2rem] p-5 flex flex-col justify-between";
                card.innerHTML = `
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span class="w-8 h-8 rounded-full ${priorityBg} ${priorityColor} flex items-center justify-center text-xs"><i class="fa-solid fa-gem"></i></span>
                            ${statusBadge}
                        </div>
                        <h4 class="font-display font-semibold text-base text-ink mb-1.5 ${isCompleted ? 'line-through text-[#f472b6]/60' : ''}">${escapeHtml(task.title)}</h4>
                        <p class="text-xs text-[#9d174d] line-clamp-2">${escapeHtml(task.description || 'No description provided.')}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-sky mb-4 pt-3 border-t border-skytint">
                            <i class="fa-regular fa-calendar"></i> Due: ${task.dueDate}
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="toggleTaskStatus('${task.id}')" title="${isCompleted ? 'Reopen' : 'Complete'}" class="circle-btn w-10 h-10 rounded-full border-2 border-skytint text-skydeep hover:bg-skytint/60 flex items-center justify-center">
                                <i class="fa-solid ${isCompleted ? 'fa-rotate-left' : 'fa-check'} text-xs"></i>
                            </button>
                            <button onclick="openEditModal('${task.id}')" title="Edit" class="circle-btn w-10 h-10 rounded-full border-2 border-skytint text-sky hover:bg-skytint/60 flex items-center justify-center">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button onclick="deleteTask('${task.id}')" title="Delete" class="circle-btn w-10 h-10 rounded-full border-2 border-skytint text-[#f472b6] hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        function openModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function openCreateModal() {
            document.getElementById('taskId').value = '';
            document.getElementById('taskForm').reset();
            document.getElementById('modalTitle').innerHTML = '<span class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-sky"><i class="fa-solid fa-circle-plus text-xs"></i></span> Create New Task';
            openModal();
        }

        function openEditModal(id) {
            const task = tasks.find(t => t.id === id);
            if (!task) return;

            document.getElementById('taskId').value = task.id;
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDesc').value = task.description;
            document.getElementById('taskPriority').value = task.priority;
            document.getElementById('taskDueDate').value = task.dueDate;
            document.getElementById('modalTitle').innerHTML = '<span class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-sky"><i class="fa-solid fa-pen text-xs"></i></span> Edit Task';
            openModal();
        }

        function closeModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            const id = document.getElementById('taskId').value;
            const title = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('taskDesc').value.trim();
            const priority = document.getElementById('taskPriority').value;
            const dueDate = document.getElementById('taskDueDate').value;

            if (!title || !dueDate) return;

            if (id) {
                tasks = tasks.map(t => t.id === id ? { ...t, title, description, priority, dueDate } : t);
                showFlash('Task successfully updated!', 'success');
            } else {
                const newTask = {
                    id: Date.now().toString(),
                    title, description, priority, dueDate,
                    status: 'pending'
                };
                tasks.unshift(newTask);
                showFlash('New task created successfully!', 'success');
            }

            closeModal();
            renderApp();
        }

        function toggleTaskStatus(id) {
            tasks = tasks.map(t => {
                if (t.id === id) {
                    const newStatus = t.status === 'completed' ? 'pending' : 'completed';
                    showFlash(`Task marked as ${newStatus}!`, 'success');
                    return { ...t, status: newStatus };
                }
                return t;
            });
            renderApp();
        }

        function deleteTask(id) {
            if (confirm('Are you sure you want to remove this task?')) {
                tasks = tasks.filter(t => t.id !== id);
                showFlash('Task deleted successfully.', 'error');
                renderApp();
            }
        }

        function showFlash(message, type) {
            const flash = document.getElementById('flashMessage');
            const text = document.getElementById('flashText');
            const icon = document.getElementById('flashIcon');

            text.innerText = message;
            if (type === 'success') {
                flash.className = "mx-6 md:mx-10 mt-6 p-4 rounded-full border-2 border-skytint bg-white text-emerald-600 flex items-center gap-3 text-sm shadow-sm transition-all duration-300";
                icon.className = "fa-solid fa-circle-check text-base text-emerald-500";
            } else {
                flash.className = "mx-6 md:mx-10 mt-6 p-4 rounded-full border-2 border-skytint bg-white text-rose-500 flex items-center gap-3 text-sm shadow-sm transition-all duration-300";
                icon.className = "fa-solid fa-circle-exclamation text-base text-rose-500";
            }

            flash.classList.remove('hidden');
            setTimeout(() => {
                flash.classList.add('hidden');
            }, 3500);
        }

        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>