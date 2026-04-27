<?php
    if (session_status() === PHP_SESSION_NONE) session_start();

    $locker_grid = [
        ['title' => 'Male', 'rows' => 8, 'cols' => 8, 'index' => 0, 'gender' => 'Male'],
        ['title' => 'Female', 'rows' => 5, 'cols' => 8, 'index' => 1, 'gender' => 'Female'],
    ];

    $current_floor = 0;

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
            <div class="flex items-center justify-between">
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
                <button type="button" onclick="openAddModal()" class="flex items-center gap-2 bg-white border border-zinc-100 hover:border-zinc-200 hover:scale-105 hover:bg-zinc-100 hover:mr-3 text-black text-sm font-semibold px-5 py-2.5 rounded-xl transition-all cursor-pointer shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-plus text-xs"></i> Add Locker
                </button>
            </div>

            <!-- STATS ROW -->
            <div class="grid grid-cols-5 gap-3">
                <?php foreach ($locker_stats as $stat): ?>
                    <button type="button" id="filter-<?php echo strtolower($stat['label']); ?>" onclick="setFilter('<?php echo strtolower($stat['label']); ?>')" class="stat-pill hero-card relative rounded-2xl p-4 cursor-pointer text-left border <?php echo $stat['border']; ?> <?php echo $stat['color']; ?>">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[10px] font-bold <?php echo $stat['text']; ?> uppercase tracking-widest"><?php echo $stat['label']; ?></p>
                            <div class="w-7 h-7 rounded-lg <?php echo $stat['icon-container']; ?> flex items-center justify-center"><?php echo $stat['icon']; ?></div>
                        </div>
                        <p class="serif text-3xl <?php echo $stat['text']; ?>" id="count-<?php echo strtolower($stat['label']); ?>"><?php echo number_format($stat['value']); ?></p>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- OCCUPANCY BAR -->
            <div class="bg-white rounded-2xl px-5 py-3 border border-zinc-200 flex items-center gap-4">
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
            <div id="gridContainer" class="flex flex-col items-start justify-start w-full overflow-auto rounded-2xl border border-zinc-200 bg-white gap-3">
                <div class="flex flex-row items-center justify-between w-full border-b border-b-zinc-200 shrink-0 px-5 py-3">
                    <div class="flex flex-col items-start justify-center">
                        <h3 class="serif text-xl text-zinc-800 font-bold" id="floorTitle"></h3>
                        <p class="text-xs text-zinc-400 font-medium">Drag lockers to reposition</p>
                    </div>
                    <div class="flex flex-row items-center gap-2">
                        <div class="relative flex flex-row items-center p-1 bg-zinc-800 rounded-full" id="floorToggle">
                            <div id="floorIndicator" class="absolute rounded-full pointer-events-none opacity-0" style="background: linear-gradient(135deg, #f3e6d0, #d2b683, #9c7e49); transition: left 0.3s cubic-bezier(.4,0,.2,1), width 0.3s cubic-bezier(.4,0,.2,1), top 0.3s cubic-bezier(.4,0,.2,1), height 0.3s cubic-bezier(.4,0,.2,1), opacity 0.2s ease;"></div>
                            <?php foreach ($locker_grid as $floor): ?>
                                <button 
                                    type="button"
                                    data-floor-btn
                                    data-floor-index="<?php echo $floor['index']; ?>"
                                    onclick="setFloor(<?php echo $floor['index']; ?>)" 
                                    class="relative z-10 flex items-center justify-center h-9 rounded-full text-sm font-bold cursor-pointer px-8"
                                    style="color: #facc15; transition: color 0.3s ease;">
                                    <?php echo $floor['title']; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div class="w-px h-8 border-r border-zinc-300"></div> <!-- SEPARATOR -->
                        <button type="button" class="flex flex-row items-center justify-center px-4 h-10 rounded-full border border-zinc-200 bg-zinc-800 text-yellow-400 text-sm font-semibold hover:bg-zinc-700 hover:scale-105 transition-all cursor-pointer shadow-sm">
                            <i class="fa-solid fa-screwdriver-wrench text-yellow-400 text-sm mr-2"></i>
                            <p class="text-yellow-400 text-sm font-semibold">Grid Layout</p>
                        </button>
                    </div>
                </div>
                <div class="flex items-start justify-center w-full overflow-auto p-3">
                    <?php foreach ($locker_grid as $index => $floor): ?>
                        <div id="floor-<?php echo $index; ?>" class="floor-grid <?php echo $index !== 0 ? 'hidden' : ''; ?> w-full" data-gender="<?php echo $floor['gender']; ?>">
                            <div class="grid grid-cols-<?php echo $floor['cols']; ?> grid-rows-<?php echo $floor['rows']; ?> gap-1 p-2 border border-zinc-200 rounded-2xl w-fit mx-auto">
                                <?php for ($i = 0; $i < $floor['rows']; $i++): ?>
                                    <?php for ($j = 0; $j < $floor['cols']; $j++): ?>
                                        <div class="locker-cell w-18 h-18 bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50 relative flex items-center justify-center" data-row="<?php echo $i; ?>" data-col="<?php echo $j; ?>" data-gender="<?php echo $floor['gender']; ?>"></div>
                                    <?php endfor; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <!-- ADD MODAL -->
        <div id="addModal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-all duration-200" onclick="handleModalBg(event)">
            <form id="addModalContent" class="bg-white rounded-3xl p-6 w-[400px] shadow-2xl scale-105 opacity-0 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="serif text-xl text-zinc-800">Add New Locker</h2>
                    <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 flex items-center justify-center cursor-pointer transition-colors">
                        <i class="fa-solid fa-xmark text-zinc-400 text-sm"></i>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Locker Number</label>
                        <input name="locker_number" id="add-locker-number" type="text" placeholder="e.g. M-001" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Phase</label>
                            <select name="phase" id="add-phase" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="Phase 1">Phase 1</option>
                                <option value="Phase 2">Phase 2</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Gender</label>
                            <select name="gender" id="add-gender" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Classification</label>
                            <select name="classification" id="add-classification" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="Regular">Regular</option>
                                <option value="Probationary">Probationary</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Employment Type</label>
                            <select name="employment_type" id="add-employment-type" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Row (pos_y)</label>
                            <input name="pos_y" id="add-row" type="number" min="0" placeholder="0" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Column (pos_x)</label>
                            <input name="pos_x" id="add-col" type="number" min="0" placeholder="0" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Face</label>
                            <select name="locker_facing" id="add-face" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="0">Right</option>
                                <option value="1">Down</option>
                                <option value="2">Left</option>
                                <option value="3">Up</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Length</label>
                            <input name="locker_length" id="add-length" type="number" min="1" value="1" placeholder="1" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 w-full gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Status</label>
                            <select name="status" id="add-status" onchange="toggleEmpFields()" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Reserved">Reserved</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Capacity</label>
                            <input type="number" name="capacity" id="add-capacity" min="1" value="1" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                    </div>
                    <div id="empFields" class="hidden space-y-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Employee Name</label>
                            <input name="employee_name" id="add-emp" type="text" placeholder="Full name" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Employee ID</label>
                            <input name="employee_id" id="add-empid" type="text" placeholder="EMP-XXXX" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Department</label>
                            <select name="department" id="add-dept" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-sm text-zinc-800 focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all cursor-pointer">
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
                    <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2.5 rounded-xl bg-zinc-100 text-sm font-semibold text-zinc-500 hover:bg-zinc-200 transition-colors cursor-pointer">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-yellow-400 text-sm font-semibold text-zinc-800 hover:bg-yellow-500 transition-colors cursor-pointer shadow-sm">Add Locker</button>
                </div>
            </form>
        </div>
    </body>
</html>

<script>
    var floorTitles = <?= json_encode(array_column($locker_grid, 'title')) ?>;
    var floorConfigs = <?= json_encode($locker_grid) ?>;
    var currentFilter = 'all lockers';

    // ─── Modal ────────────────────────────────
    var openAddModal = () => {
        document.getElementById('addModal').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('addModal').style.opacity = '1';
            document.getElementById('addModalContent').style.opacity = '1';
            document.getElementById('addModalContent').style.transform = 'scale(1)';
        }, 10);
    }

    var closeAddModal = () => {
        document.getElementById('addModal').style.opacity = '0';
        document.getElementById('addModalContent').style.opacity = '0';
        document.getElementById('addModalContent').style.transform = 'scale(0.95)';
        setTimeout(() => {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('addModalContent').reset();
            // Reset employee fields visibility
            document.getElementById('empFields').classList.add('hidden');
        }, 200);
    }

    var handleModalBg = (event) => {
        if (event.target.id === 'addModal') {
            closeAddModal();
        }
    }

    // ─── Toggle Employee Fields ───────────────
    var toggleEmpFields = () => {
        const status = document.getElementById('add-status').value;
        const empFields = document.getElementById('empFields');
        if (status === 'Occupied') {
            empFields.classList.remove('hidden');
        } else {
            empFields.classList.add('hidden');
        }
    }

    // ─── Stats Filter ─────────────────────────
    var setFilter = (filter) => {
        currentFilter = filter;
        // Update active pill styling
        document.querySelectorAll('.stat-pill').forEach(pill => {
            pill.classList.remove('ring-2', 'ring-offset-2', 'ring-zinc-800');
        });
        const activePill = document.getElementById(`filter-${filter}`);
        if (activePill) {
            activePill.classList.add('ring-2', 'ring-offset-2', 'ring-zinc-800');
        }
        // TODO: filter lockers in grid based on status
    }

    // ─── Floor Switching ──────────────────────
    var setFloor = (floor) => {
        // Switch grid
        document.querySelectorAll('.floor-grid').forEach(el => el.classList.add('hidden'));
        document.getElementById(`floor-${floor}`).classList.remove('hidden');

        document.getElementById('floorTitle').innerText = floorTitles[floor];

        // Move sliding indicator
        const buttons = document.querySelectorAll('[data-floor-btn]');
        const activeBtn = buttons[floor];
        const indicator = document.getElementById('floorIndicator');

        indicator.style.left = `${activeBtn.offsetLeft}px`;
        indicator.style.width = `${activeBtn.offsetWidth}px`;
        indicator.style.top = `${activeBtn.offsetTop}px`;
        indicator.style.height = `${activeBtn.offsetHeight}px`;
        indicator.style.opacity = '1';

        // Update text colors
        buttons.forEach((btn, i) => {
            btn.style.color = i === floor ? '#18181b' : '#facc15';
            btn.style.fontWeight = i === floor ? '800' : '600';
        });
    }

    // Initialize first floor — poll until buttons are fully styled (AJAX + Tailwind timing)
    (function initFloor() {
        const btn = document.querySelector('[data-floor-btn]');
        if (btn && btn.offsetWidth > 50) {
            setFloor(0);
        } else {
            setTimeout(initFloor, 50);
        }
    })();

    // ─── Status Colors ────────────────────────
    var statusStyles = {
        'Available':   { bg: 'bg-emerald-100', border: 'border-emerald-400', text: 'text-emerald-700', icon: 'fa-lock-open' },
        'Occupied':    { bg: 'bg-blue-100',    border: 'border-blue-400',    text: 'text-blue-700',    icon: 'fa-user' },
        'Reserved':    { bg: 'bg-fuchsia-100', border: 'border-fuchsia-400', text: 'text-fuchsia-700', icon: 'fa-bookmark' },
        'Maintenance': { bg: 'bg-orange-100',  border: 'border-orange-400',  text: 'text-orange-700',  icon: 'fa-wrench' },
    };

    // ─── Load Lockers from DB ─────────────────
    var loadLockers = async () => {
        try {
            const response = await fetch('API/get-lockers.php');
            const data = await response.json();

            if (!response.ok) {
                console.error('Failed to load lockers:', data.message);
                return;
            }

            // Rebuild each floor grid dynamically
            floorConfigs.forEach((config, index) => {
                const floorEl = document.getElementById(`floor-${index}`);
                const gridEl = floorEl.querySelector('.grid');
                gridEl.innerHTML = ''; // Clear all cells

                const rows = config.rows;
                const cols = config.cols;
                const gender = config.gender;

                // Track which cells are occupied by a spanning locker
                const occupied = new Set();

                // Filter lockers for this floor's gender
                const floorLockers = data.lockers.filter(l => l.gender === gender);

                // First pass: calculate spans and mark occupied cells
                const lockerPlacements = [];
                floorLockers.forEach(locker => {
                    const row = Math.floor(parseFloat(locker.pos_y));
                    const col = Math.floor(parseFloat(locker.pos_x));
                    const length = parseInt(locker.locker_length) || 1;
                    const facing = parseInt(locker.locker_facing) || 0;
                    const style = statusStyles[locker.status] || statusStyles['Available'];

                    // Calculate anchor position and span based on facing direction
                    let startRow = row, startCol = col, rowSpan = 1, colSpan = 1;

                    switch (facing) {
                        case 0: // Right → span columns to the right
                            colSpan = length;
                            break;
                        case 1: // Down → span rows downward
                            rowSpan = length;
                            break;
                        case 2: // Left → span columns to the left
                            startCol = col - length + 1;
                            colSpan = length;
                            break;
                        case 3: // Up → span rows upward
                            startRow = row - length + 1;
                            rowSpan = length;
                            break;
                    }

                    // Clamp to grid bounds
                    startRow = Math.max(0, startRow);
                    startCol = Math.max(0, startCol);
                    if (startCol + colSpan > cols) colSpan = cols - startCol;
                    if (startRow + rowSpan > rows) rowSpan = rows - startRow;

                    // Mark all cells this locker occupies
                    for (let r = startRow; r < startRow + rowSpan; r++) {
                        for (let c = startCol; c < startCol + colSpan; c++) {
                            occupied.add(`${r}-${c}`);
                        }
                    }

                    lockerPlacements.push({ locker, startRow, startCol, rowSpan, colSpan, style });
                });

                // Second pass: create locker elements with grid placement
                lockerPlacements.forEach(({ locker, startRow, startCol, rowSpan, colSpan, style }) => {
                    const el = document.createElement('div');
                    // CSS grid is 1-indexed
                    el.style.gridRow = `${startRow + 1} / span ${rowSpan}`;
                    el.style.gridColumn = `${startCol + 1} / span ${colSpan}`;
                    el.className = `${style.bg} ${style.border} border-solid rounded-2xl cursor-grab transition-all hover:brightness-95 flex items-center justify-center relative active:cursor-grabbing`;
                    el.style.minHeight = '4.5rem'; // h-18
                    el.setAttribute('data-locker-id', locker.id);
                    el.setAttribute('draggable', 'true');

                    // Drag start — store locker id and source gender
                    el.addEventListener('dragstart', (e) => {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            lockerId: locker.id,
                            sourceGender: gender
                        }));
                        e.dataTransfer.effectAllowed = 'move';
                        el.style.opacity = '0.4';
                        el.classList.add('ring-2', 'ring-yellow-400', 'scale-95');
                    });

                    el.addEventListener('dragend', () => {
                        el.style.opacity = '1';
                        el.classList.remove('ring-2', 'ring-yellow-400', 'scale-95');
                    });

                    el.innerHTML = `
                        <div class="flex flex-col items-center justify-center gap-0.5 pointer-events-none">
                            <i class="fa-solid ${style.icon} text-sm ${style.text}"></i>
                            <span class="text-[10px] font-bold ${style.text} leading-tight text-center">${locker.locker_number}</span>
                            <span class="text-[8px] ${style.text} opacity-60">${locker.status}</span>
                        </div>
                    `;

                    gridEl.appendChild(el);
                });

                // Third pass: fill empty cells (drop targets)
                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        if (!occupied.has(`${r}-${c}`)) {
                            const empty = document.createElement('div');
                            empty.style.gridRow = `${r + 1}`;
                            empty.style.gridColumn = `${c + 1}`;
                            empty.className = 'w-18 h-18 bg-zinc-100 rounded-2xl border border-dashed border-zinc-400 cursor-pointer transition-all hover:border-zinc-800 hover:bg-zinc-50';
                            empty.setAttribute('data-drop-row', r);
                            empty.setAttribute('data-drop-col', c);
                            empty.setAttribute('data-drop-gender', gender);

                            // Drop zone handlers
                            empty.addEventListener('dragover', (e) => {
                                e.preventDefault();
                                e.dataTransfer.dropEffect = 'move';
                                empty.classList.add('bg-yellow-100', 'border-yellow-400', 'border-solid', 'scale-105');
                                empty.classList.remove('bg-zinc-100', 'border-zinc-400', 'border-dashed');
                            });

                            empty.addEventListener('dragleave', () => {
                                empty.classList.remove('bg-yellow-100', 'border-yellow-400', 'border-solid', 'scale-105');
                                empty.classList.add('bg-zinc-100', 'border-zinc-400', 'border-dashed');
                            });

                            empty.addEventListener('drop', async (e) => {
                                e.preventDefault();
                                empty.classList.remove('bg-yellow-100', 'border-yellow-400', 'border-solid', 'scale-105');

                                try {
                                    const payload = JSON.parse(e.dataTransfer.getData('text/plain'));
                                    const dropGender = empty.getAttribute('data-drop-gender');

                                    // Only allow drops within the same gender grid
                                    if (payload.sourceGender !== dropGender) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Invalid Move',
                                            text: 'Cannot move lockers between different gender grids.',
                                            confirmButtonColor: '#facc15',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                        return;
                                    }

                                    const newRow = parseInt(empty.getAttribute('data-drop-row'));
                                    const newCol = parseInt(empty.getAttribute('data-drop-col'));

                                    await updateLockerPosition(payload.lockerId, newCol, newRow);
                                } catch (err) {
                                    console.error('Drop error:', err);
                                }
                            });

                            gridEl.appendChild(empty);
                        }
                    }
                }
            });

            // Update stat counts
            const counts = data.counts;
            const totalEl = document.getElementById('count-all lockers');
            const occEl   = document.getElementById('count-occupied');
            const availEl = document.getElementById('count-available');
            const resEl   = document.getElementById('count-reserved');
            const maintEl = document.getElementById('count-maintenance');

            if (totalEl) totalEl.textContent = Number(counts.total).toLocaleString();
            if (occEl)   occEl.textContent   = Number(counts.occupied).toLocaleString();
            if (availEl) availEl.textContent  = Number(counts.available).toLocaleString();
            if (resEl)   resEl.textContent    = Number(counts.reserved).toLocaleString();
            if (maintEl) maintEl.textContent  = Number(counts.maintenance).toLocaleString();

            // Update occupancy bar
            const total = parseInt(counts.total) || 0;
            const occupied_count = parseInt(counts.occupied) || 0;
            const pct = total > 0 ? Math.round((occupied_count / total) * 100) : 0;
            const bar = document.getElementById('occupancyBar');
            const pctLabel = document.getElementById('occupancyPct');
            if (bar) bar.style.width = pct + '%';
            if (pctLabel) pctLabel.textContent = pct + '%';

        } catch (err) {
            console.error('Error loading lockers:', err);
        }
    };

    // Load on init
    loadLockers();

    // ─── Update Locker Position (Drag & Drop) ──
    var updateLockerPosition = async (lockerId, newCol, newRow) => {
        try {
            const response = await fetch('API/update-locker-position.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: lockerId,
                    pos_x: newCol,
                    pos_y: newRow,
                })
            });
            const result = await response.json();

            if (response.ok) {
                // Silently refresh grid — no popup for quick repositioning
                loadLockers();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Move Failed',
                    text: result.message || 'Could not reposition locker.',
                    confirmButtonColor: '#ef4444',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Could not reach the server.',
                confirmButtonColor: '#ef4444',
                timer: 2000,
                showConfirmButton: false
            });
        }
    };

    // ─── Add Locker (Form Submit) ─────────────
    document.getElementById('addModalContent').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('API/create-locker.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Locker Created',
                    text: result.message || 'Locker has been added successfully.',
                    confirmButtonColor: '#facc15'
                });
                closeAddModal();
                loadLockers(); // Refresh grid
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Failed to create locker.',
                    confirmButtonColor: '#ef4444'
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Could not reach the server. Please try again.',
                confirmButtonColor: '#ef4444'
            });
        }
    });
</script>