<?php
    if (session_status() === PHP_SESSION_NONE) session_start();


    $locker_stats = [
        [
            'label' => 'All Lockers',
            'value' => 4353,
            'icon' => '<i class="fa-solid fa-lock text-xs text-yellow-500"></i>',
            'color' => 'bg-zinc-800',
            'text' => 'text-white',
            'border' => 'border-zinc-500',
            'icon-container' => 'bg-zinc-700',
        ],
        [
            'label' => 'Occupied',
            'value' => 123,
            'icon' => '<i class="fa-solid fa-user text-xs text-blue-500"></i>',
            'color' => 'bg-gradient-to-br from-purple-50 to-blue-400',
            'text' => 'text-blue-500',
            'border' => 'border-blue-200',
            'icon-container' => 'bg-blue-500/20',
        ],
        [
            'label' => 'Available',
            'value' => 42,
            'icon' => '<i class="fa-solid fa-lock-open text-xs text-emerald-500"></i>',
            'color' => 'bg-gradient-to-br from-emerald-50 to-emerald-100/50',
            'text' => 'text-emerald-500',
            'border' => 'border-emerald-200/80',
            'icon-container' => 'bg-emerald-500/20',
        ],
        [
            'label' => 'Reserved',
            'value' => 123,
            'icon' => '<i class="fa-solid fa-bookmark text-xs text-fuchsia-500"></i>',
            'color' => 'bg-gradient-to-br from-fuchsia-50 to-fuchsia-100/50',
            'text' => 'text-fuchsia-500',
            'border' => 'border-fuchsia-200/80',
            'icon-container' => 'bg-fuchsia-500/20',
        ],
        [
            'label' => 'Maintenance',
            'value' => 2,
            'icon' => '<i class="fa-solid fa-wrench text-xs text-orange-500"></i>',
            'color' => 'bg-gradient-to-br from-orange-50 to-orange-100/50',
            'text' => 'text-orange-500',
            'border' => 'border-orange-200/80',
            'icon-container' => 'bg-orange-500/20',
        ]
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <title>Admin Locker | Lockers</title>
</head>
    <body class="bg-zinc-100 min-h-screen overflow-y-auto">
        <div class="pt-3 w-full mx-auto flex flex-col h-full gap-3">

            <!-- HEADER -->
            <div class="flex items-center justify-between fu fu1">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="serif text-[2rem] leading-tight text-zinc-800">Locker Management</h1>
                        <p class="text-xs text-zinc-400 mt-0.5 flex items-center gap-2">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-grip text-[10px] text-zinc-300"></i>Grid layout</span>
                            <span class="text-zinc-300">·</span>
                            <span class="flex items-center gap-1"><i class="fa-solid fa-arrows-up-down-left-right text-[10px] text-zinc-300"></i>Drag to reposition</span>
                            <span class="text-zinc-300">·</span>
                            <span class="flex items-center gap-1"><i class="fa-solid fa-hand-pointer text-[10px] text-zinc-300"></i>Click for details</span>
                        </p>
                    </div>
                </div>
                <button onclick="openAddModal()" class="flex items-center gap-2 bg-zinc-800 hover:scale-110 hover:bg-zinc-700 hover:mr-3 text-yellow-400 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all cursor-pointer shadow-lg hover:shadow-xl" style="box-shadow:0 4px 16px rgba(0,0,0,.15)">
                    <i class="fa-solid fa-plus text-xs"></i> Add Locker
                </button>
            </div>

            <!-- STATS ROW -->
            <div class="grid grid-cols-5 gap-3 fu fu2">
                <?php foreach ($locker_stats as $stat): ?>
                <button id="filter-<?php echo strtolower($stat['label']); ?>" onclick="setFilter('<?php echo strtolower($stat['label']); ?>')" class="stat-pill hero-card relative rounded-2xl p-4 cursor-pointer text-left border <?php echo $stat['border']; ?> <?php echo $stat['color']; ?>">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] font-bold <?php echo $stat['text']; ?> uppercase tracking-widest"><?php echo $stat['label']; ?></p>
                        <div class="w-7 h-7 rounded-lg <?php echo $stat['icon-container']; ?> flex items-center justify-center"><?php echo $stat['icon']; ?></div>
                    </div>
                    <p class="serif text-3xl <?php echo $stat['text']; ?>" id="count-<?php echo strtolower($stat['label']); ?>"><?php echo number_format($stat['value']); ?></p>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- OCCUPANCY BAR -->
            <div class="bg-white rounded-2xl px-5 py-3 border border-zinc-200 flex items-center gap-4 fu fu3">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-zinc-800 flex items-center justify-center"><i class="fa-solid fa-chart-simple text-yellow-400 text-[9px]"></i></div>
                    <span class="text-xs font-semibold text-zinc-500 whitespace-nowrap">Occupancy Rate</span>
                </div>
                <div class="flex-1 h-3 rounded-full occ-bar-track overflow-hidden">
                    <div id="occupancyBar" class="h-full w-[30%] bg-zinc-800 rounded-full transition-all duration-700 ease-out"></div>
                </div>
                <span id="occupancyPct" class="text-sm font-bold text-zinc-800 w-10 text-right">30%</span>
            </div>

            <!-- GRID CONTAINER -->
            <div id="gridContainer" class="flex flex-col items-start justify-start w-full flex-1 overflow-hidden rounded-2xl border border-zinc-200 bg-white gap-3">
                <div class="flex flex-row items-center justify-between h-15 w-full border-b-1 border-b-zinc-200">
                    <div class="flex flex-col items-start justify-between w-auto h-full px-5 py-3">
                        <h3 class="serif text-md text-zinc-800 font-bold">Floor 1</h3>
                        <p class="text-xs text-zinc-400 font-medium">Drag lockers to reposition</p>
                    </div>
                    <div class="flex flex-row items-center justify-end w-auto h-full px-5 py-3 gap-2">
                        <div class="flex flex-row items-center justify-end w-auto h-full gap-1">
                            <button class="flex items-center justify-center w-auto h-8 rounded-xl bg-yellow-400 text-zinc-800 text-sm font-bold border border-zinc-200 hover:bg-yellow-300 hover:text-zinc-700 hover:scale-105 transition-all cursor-pointer px-4 shadow-sm">
                                Floor 1
                            </button>
                            <button class="flex items-center justify-center w-auto h-8 rounded-xl bg-zinc-800 text-yellow-400 text-sm font-bold border border-zinc-200 hover:bg-zinc-600 hover:text-yellow-400 hover:scale-105 transition-all cursor-pointer px-4 shadow-sm">
                                Floor 2
                            </button>
                            <button class="flex items-center justify-center w-auto h-8 rounded-xl bg-zinc-800 text-yellow-500 text-sm font-bold border border-zinc-200 hover:bg-zinc-600 hover:text-yellow-400 hover:scale-105 transition-all cursor-pointer px-4 shadow-sm">
                                Floor 3
                            </button>
                        </div>
                        <div class="flex h-8 border border-zinc-400 rounded-full"></div> <!-- SEPARATOR -->
                        <button class="flex flex-row items-center justify-center px-4 h-8 w-auto rounded-xl border-1 border-zinc-200 bg-zinc-800 text-yellow-400 text-sm font-semibold hover:bg-zinc-700 hover:text-yellow-400 hover:scale-105 transition-all cursor-pointer shadow-sm">
                            <i class="fa-solid fa-screwdriver-wrench text-yellow-400 text-sm mr-2"></i>
                            <p class="text-yellow-400 text-sm font-semibold">Grid Layout</p>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-center w-full flex-1 overflow-auto p-3">
                    <div class="h-full aspect-square grid grid-cols-8 grid-rows-8 gap-1">
                        <div class="h-full w-full bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50"></div>
                        <div class="h-full w-full bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50"></div>
                        <div class="h-full w-full bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50"></div>
                        <div class="h-full w-full bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50"></div>
                        <div class="h-full w-full bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50"></div>
                    </div>
                </div>
            </div>
        <!-- ADD MODAL -->
        <div id="addModal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-all duration-200" onclick="handleModalBg(event)">
            <div id="addModalContent" class="bg-white rounded-3xl p-6 w-[400px] shadow-2xl scale-105 opacity-0 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="serif text-xl text-zinc-800">Add New Locker</h2>
                    <button onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 flex items-center justify-center cursor-pointer transition-colors">
                        <i class="fa-solid fa-xmark text-zinc-400 text-sm"></i>
                    </button>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Row</label>
                            <input id="add-row" type="number" min="0" placeholder="0" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Column</label>
                            <input id="add-col" type="number" min="0" placeholder="0" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Status</label>
                        <select id="add-status" onchange="toggleEmpFields()" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="reserved">Reserved</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div id="empFields" class="hidden space-y-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Employee Name</label>
                            <input id="add-emp" type="text" placeholder="Full name" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Employee ID</label>
                            <input id="add-empid" type="text" placeholder="EMP-XXXX" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Department</label>
                            <select id="add-dept" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="HR">HR</option>
                                <option value="IT">IT</option>
                                <option value="Admin">Admin</option>
                                <option value="Finance">Finance</option>
                                <option value="Operations">Operations</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 mt-5">
                    <button onclick="closeAddModal()" class="flex-1 px-4 py-2.5 rounded-xl bg-zinc-100 text-sm font-semibold text-zinc-500 hover:bg-zinc-200 transition-colors cursor-pointer">Cancel</button>
                    <button onclick="addLocker()" class="flex-1 px-4 py-2.5 rounded-xl bg-yellow-400 text-sm font-semibold text-zinc-800 hover:bg-yellow-500 transition-colors cursor-pointer shadow-sm">Add Locker</button>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    const openAddModal = () => {
        document.getElementById('addModal').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('addModal').style.opacity = '1';
            document.getElementById('addModalContent').style.opacity = '1';
            document.getElementById('addModalContent').style.transform = 'scale(1)';
        }, 10);
    }
    const closeAddModal = () => {
        document.getElementById('addModal').style.opacity = '0';
        document.getElementById('addModalContent').style.opacity = '0';
        document.getElementById('addModalContent').style.transform = 'scale(0.95)';
        setTimeout(() => {
            document.getElementById('addModal').style.display = 'none';
        }, 200);
    }
    const handleModalBg = (event) => {
        if (event.target.id === 'addModal') {
            closeAddModal();
        }
    }
</script>