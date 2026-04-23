<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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
    <style>
        *{font-family:'DM Sans',sans-serif;box-sizing:border-box}
        .serif{font-family:'DM Serif Display',serif}
        @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
        @keyframes slideIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
        @keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
        @keyframes pulse-glow{0%,100%{box-shadow:0 0 8px rgba(250,204,21,.2)}50%{box-shadow:0 0 16px rgba(250,204,21,.4)}}
        .fu{animation:fadeUp .4s ease both}
        .fu1{animation-delay:.04s}.fu2{animation-delay:.08s}.fu3{animation-delay:.12s}
        .fu4{animation-delay:.16s}.fu5{animation-delay:.2s}
        .slide-in{animation:slideIn .3s ease both}
        .modal-in{animation:modalIn .25s ease both}
        .locker-cell{transition:transform .18s cubic-bezier(.4,0,.2,1),box-shadow .18s,border-color .18s}
        .locker-cell:hover{transform:translateY(-3px) scale(1.03);box-shadow:0 8px 24px rgba(0,0,0,.10)!important;z-index:2}
        .locker-cell.dragging{opacity:.35;transform:scale(.88);filter:blur(1px)}
        .dragover{background:rgba(250,204,21,.15)!important;border-color:rgba(250,204,21,.6)!important;box-shadow:inset 0 0 12px rgba(250,204,21,.1)!important}
        .empty-cell{transition:all .18s}
        .stat-pill{transition:all .2s cubic-bezier(.4,0,.2,1);position:relative;overflow:hidden}
        .stat-pill::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent 60%,rgba(255,255,255,.4));pointer-events:none}
        .stat-pill:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.08)}
        .status-btn{transition:all .18s cubic-bezier(.4,0,.2,1)}
        .status-btn:hover{transform:scale(1.04);box-shadow:0 2px 8px rgba(0,0,0,.08)}
        #lockerGrid{display:grid;gap:10px;justify-content:center}
        .detail-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0}
        .detail-row+.detail-row{border-top:1px solid #f4f4f5}
        .hero-card{background:linear-gradient(135deg,#18181b 0%,#27272a 50%,#1e1e22 100%)}
        .hero-card::before{content:'';position:absolute;top:-50%;right:-50%;width:100%;height:100%;background:radial-gradient(circle,rgba(250,204,21,.08) 0%,transparent 70%);pointer-events:none}
        .grid-panel{background:linear-gradient(180deg,#ffffff 0%,#fafafa 100%)}
        .occ-bar-track{background:linear-gradient(90deg,#f4f4f5,#e4e4e7)}
        .occ-bar-fill{background:linear-gradient(90deg,#facc15,#f59e0b);box-shadow:0 0 12px rgba(250,204,21,.3)}
    </style>
</head>
<body class="bg-zinc-100 min-h-screen overflow-y-auto">
<div class="p-3 pl-0 w-full mx-auto flex flex-col h-full gap-3">

    <!-- HEADER -->
    <div class="flex items-center justify-between fu fu1">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-zinc-800 flex items-center justify-center shadow-lg" style="box-shadow:0 4px 16px rgba(0,0,0,.15)">
                <i class="fa-solid fa-grid-2 text-yellow-400 text-lg"></i>
            </div>
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
        <button onclick="openAddModal()" class="flex items-center gap-2 bg-zinc-800 hover:bg-zinc-700 text-yellow-400 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all cursor-pointer shadow-lg hover:shadow-xl" style="box-shadow:0 4px 16px rgba(0,0,0,.15)">
            <i class="fa-solid fa-plus text-xs"></i> Add Locker
        </button>
    </div>

    <!-- STATS ROW -->
    <div class="grid grid-cols-5 gap-3 fu fu2">
        <button id="filter-all" onclick="setFilter('all')" class="stat-pill hero-card relative rounded-2xl p-4 cursor-pointer text-left border border-zinc-700">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">All Lockers</p>
                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center"><i class="fa-solid fa-lock text-yellow-400 text-[10px]"></i></div>
            </div>
            <p class="serif text-3xl text-white" id="count-all">0</p>
        </button>
        <button id="filter-occupied" onclick="setFilter('occupied')" class="stat-pill bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-4 border border-blue-200/80 cursor-pointer text-left">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Occupied</p>
                <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center"><i class="fa-solid fa-user text-blue-500 text-[10px]"></i></div>
            </div>
            <p class="serif text-3xl text-blue-600" id="count-occupied">0</p>
        </button>
        <button id="filter-available" onclick="setFilter('available')" class="stat-pill bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-4 border border-emerald-200/80 cursor-pointer text-left">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Available</p>
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center"><i class="fa-solid fa-lock-open text-emerald-500 text-[10px]"></i></div>
            </div>
            <p class="serif text-3xl text-emerald-600" id="count-available">0</p>
        </button>
        <button id="filter-reserved" onclick="setFilter('reserved')" class="stat-pill bg-gradient-to-br from-violet-50 to-violet-100/50 rounded-2xl p-4 border border-violet-200/80 cursor-pointer text-left">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-violet-400 uppercase tracking-widest">Reserved</p>
                <div class="w-7 h-7 rounded-lg bg-violet-500/10 flex items-center justify-center"><i class="fa-solid fa-bookmark text-violet-500 text-[10px]"></i></div>
            </div>
            <p class="serif text-3xl text-violet-600" id="count-reserved">0</p>
        </button>
        <button id="filter-maintenance" onclick="setFilter('maintenance')" class="stat-pill bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-2xl p-4 border border-orange-200/80 cursor-pointer text-left">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Maintenance</p>
                <div class="w-7 h-7 rounded-lg bg-orange-500/10 flex items-center justify-center"><i class="fa-solid fa-wrench text-orange-500 text-[10px]"></i></div>
            </div>
            <p class="serif text-3xl text-orange-600" id="count-maintenance">0</p>
        </button>
    </div>

    <!-- OCCUPANCY BAR -->
    <div class="bg-white rounded-2xl px-5 py-3 border border-zinc-200 flex items-center gap-4 fu fu3">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-zinc-800 flex items-center justify-center"><i class="fa-solid fa-chart-simple text-yellow-400 text-[9px]"></i></div>
            <span class="text-xs font-semibold text-zinc-500 whitespace-nowrap">Occupancy Rate</span>
        </div>
        <div class="flex-1 h-3 rounded-full occ-bar-track overflow-hidden">
            <div id="occupancyBar" class="h-full rounded-full occ-bar-fill transition-all duration-700 ease-out" style="width:0%"></div>
        </div>
        <span id="occupancyPct" class="text-sm font-bold text-zinc-800 w-10 text-right">0%</span>
    </div>

    <!-- MAIN AREA: Grid + Detail -->
    <div class="flex gap-3 flex-1 min-h-0 fu fu4">

        <!-- GRID PANEL -->
        <div class="flex-1 grid-panel rounded-3xl border border-zinc-200 p-5 flex flex-col min-h-0 shadow-sm">
            <!-- Floor Tabs + Meta -->
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-zinc-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-zinc-800 flex items-center justify-center">
                        <i class="fa-solid fa-building text-yellow-400 text-sm"></i>
                    </div>
                    <div>
                        <p id="floorLabel" class="text-sm font-semibold text-zinc-800">Floor 1 — Layout View</p>
                        <p id="floorMeta" class="text-xs text-zinc-400 mt-0.5"></p>
                    </div>
                </div>
                <div id="floorTabs" class="flex items-center gap-1 bg-zinc-100 rounded-xl p-1"></div>
            </div>
            <!-- Grid -->
            <div class="flex-1 overflow-auto flex items-start justify-center pt-3">
                <div id="lockerGrid"></div>
            </div>
        </div>

        <!-- DETAIL PANEL (hidden by default) -->
        <div id="detailPanel" class="hidden flex-col w-[300px] bg-white rounded-3xl border border-zinc-200 flex-shrink-0 slide-in overflow-y-auto shadow-sm">
            <!-- Detail Header -->
            <div class="bg-zinc-800 rounded-t-3xl p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p id="dp-number" class="serif text-2xl text-white"></p>
                        <p id="dp-floor" class="text-xs text-white/50 mt-0.5"></p>
                    </div>
                    <button onclick="closeDetail()" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors cursor-pointer">
                        <i class="fa-solid fa-xmark text-white/60 text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="p-5">

            <!-- Status Badge -->
            <div id="dp-badge" class="mb-4"></div>

            <!-- Info Rows -->
            <div class="bg-zinc-50 rounded-2xl p-4 mb-4 space-y-0">
                <div class="detail-row border-none!">
                    <span class="text-[11px] text-zinc-400">Position</span>
                    <span id="dp-pos" class="text-xs font-semibold text-zinc-700"></span>
                </div>
                <div class="detail-row">
                    <span class="text-[11px] text-zinc-400">Floor</span>
                    <span id="dp-floorval" class="text-xs font-semibold text-zinc-700"></span>
                </div>
                <div id="dp-emprows"></div>
            </div>

            <!-- Quick Actions: Change Status -->
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Change Status</p>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <button id="sbtn-occupied" onclick="changeStatus('occupied')" class="status-btn flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-50 border border-blue-200 text-xs font-semibold text-blue-600 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>Occupied
                </button>
                <button id="sbtn-available" onclick="changeStatus('available')" class="status-btn flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Available
                </button>
                <button id="sbtn-reserved" onclick="changeStatus('reserved')" class="status-btn flex items-center gap-2 px-3 py-2 rounded-xl bg-violet-50 border border-violet-200 text-xs font-semibold text-violet-600 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>Reserved
                </button>
                <button id="sbtn-maintenance" onclick="changeStatus('maintenance')" class="status-btn flex items-center gap-2 px-3 py-2 rounded-xl bg-orange-50 border border-orange-200 text-xs font-semibold text-orange-600 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>Maintenance
                </button>
            </div>

            <!-- Remove -->
            <button onclick="removeLocker()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-500 hover:bg-red-100 transition-colors cursor-pointer">
                <i class="fa-solid fa-trash-can text-[10px]"></i> Remove Locker
            </button>
            </div><!-- close .p-5 inner wrapper -->
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/40 backdrop-blur-sm" onclick="handleModalBg(event)">
    <div class="bg-white rounded-3xl p-6 w-[400px] shadow-2xl modal-in">
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

<!-- TOAST -->
<div id="toast" class="hidden"></div>

<script>
// ── Data ────────────────────────────────────────
const FLOORS = { 'Floor 1': { rows: 4, cols: 8 }, 'Floor 2': { rows: 3, cols: 6 }, 'Floor 3': { rows: 2, cols: 5 } };
const NAMES = ['Maria Santos','Juan dela Cruz','Ana Reyes','Pedro Garcia','Liza Mendoza','Carlo Cruz','Nina Bautista','Ramon Torres','Elena Villanueva','Miguel Ocampo','Sarah Lim','David Tan','Grace Chua','Kevin Sy','Jasmine Go','Arnel Elizalde','Vince Salenga','Raiza Raguero','Gen Ong','Pamela Flores'];
const DEPTS = ['HR','IT','Admin','Finance','Operations'];
const STATUS_ICON = { occupied:'👤', available:'🔓', reserved:'🔒', maintenance:'🔧' };
const STATUS_LABEL = { occupied:'Occupied', available:'Available', reserved:'Reserved', maintenance:'Maintenance' };
const STATUS_CLR = {
    occupied:   { dot:'#3b82f6', text:'#2563eb', bg:'#eff6ff', border:'#93c5fd', badgeBg:'rgba(219,234,254,0.9)' },
    available:  { dot:'#22c55e', text:'#16a34a', bg:'#f0fdf4', border:'#86efac', badgeBg:'rgba(220,252,231,0.9)' },
    reserved:   { dot:'#8b5cf6', text:'#7c3aed', bg:'#faf5ff', border:'#c4b5fd', badgeBg:'rgba(245,243,255,0.9)' },
    maintenance:{ dot:'#f97316', text:'#ea580c', bg:'#fff7ed', border:'#fdba74', badgeBg:'rgba(255,247,237,0.9)' }
};

function randStatus() { const r = Math.random(); return r < 0.52 ? 'occupied' : r < 0.72 ? 'available' : r < 0.87 ? 'reserved' : 'maintenance'; }

let state = {}, activeFloor = 'Floor 1', activeFilter = 'all', selectedId = null, draggingId = null, toastTimer = null;

function generateFloor(floor) {
    const { rows, cols } = FLOORS[floor]; const lockers = []; let idx = 0;
    for (let r = 0; r < rows; r++) for (let c = 0; c < cols; c++) {
        const status = randStatus(), hasEmp = status === 'occupied' || status === 'reserved';
        lockers.push({ id: `${floor.replace(' ','')}-${String(r).padStart(2,'0')}${String(c).padStart(2,'0')}`, number: `L${String(idx+1).padStart(3,'0')}`, row: r, col: c, floor, status,
            employee: hasEmp ? NAMES[idx % NAMES.length] : null, empId: hasEmp ? `EMP-${1000+idx}` : null,
            dept: status==='occupied' ? DEPTS[idx%DEPTS.length] : null,
            assignedDate: status==='occupied' ? `2025-${String((idx%9)+1).padStart(2,'0')}-${String((idx%28)+1).padStart(2,'0')}` : null });
        idx++;
    }
    return lockers;
}
Object.keys(FLOORS).forEach(f => { state[f] = generateFloor(f); });

// ── Helpers ─────────────────────────────────────
function getLockers() { return state[activeFloor]; }
function getAt(row, col) { return getLockers().find(l => l.row === row && l.col === col); }
function getById(id) { return getLockers().find(l => l.id === id); }

// ── Toast ────────────────────────────────────────
function toast(msg, type='success') {
    const t = document.getElementById('toast');
    const icon = type === 'error' ? '⚠️' : type === 'info' ? 'ℹ️' : '✅';
    t.innerHTML = `${icon} ${msg}`;
    t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-zinc-800 border border-zinc-700 rounded-xl px-5 py-2.5 text-sm text-white z-[200] shadow-2xl flex items-center gap-2 whitespace-nowrap';
    t.style.animation = 'fadeUp .3s ease both';
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.className = 'hidden'; }, 3000);
}

// ── Render Floor Tabs ────────────────────────────
function renderTabs() {
    const wrap = document.getElementById('floorTabs');
    wrap.innerHTML = '';
    Object.keys(FLOORS).forEach(f => {
        const btn = document.createElement('button');
        btn.textContent = f;
        btn.className = `px-4 py-1.5 rounded-lg text-xs font-semibold cursor-pointer border-none transition-all ${f === activeFloor ? 'bg-yellow-400 text-zinc-800 shadow-sm' : 'bg-transparent text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100'}`;
        btn.onclick = () => { activeFloor = f; selectedId = null; closeDetail(); renderAll(); };
        wrap.appendChild(btn);
    });
}

// ── Render Stats ─────────────────────────────────
function renderStats() {
    const lockers = getLockers();
    const counts = { occupied:0, available:0, reserved:0, maintenance:0 };
    lockers.forEach(l => counts[l.status]++);
    document.getElementById('count-all').textContent = lockers.length;
    Object.keys(counts).forEach(s => { document.getElementById(`count-${s}`).textContent = counts[s]; });
    const pct = lockers.length ? Math.round((counts.occupied / lockers.length) * 100) : 0;
    document.getElementById('occupancyPct').textContent = pct + '%';
    document.getElementById('occupancyBar').style.width = pct + '%';
    ['all','occupied','available','reserved','maintenance'].forEach(s => {
        const btn = document.getElementById(`filter-${s}`);
        if (!btn) return;
        btn.style.opacity = activeFilter === s ? '1' : '0.5';
        btn.style.outline = activeFilter === s ? '2px solid rgba(250,204,21,0.6)' : 'none';
        btn.style.outlineOffset = '2px';
        btn.style.transform = activeFilter === s ? 'translateY(-2px)' : '';
        btn.style.boxShadow = activeFilter === s ? '0 8px 24px rgba(0,0,0,.1)' : '';
    });
}

// ── Render Grid ──────────────────────────────────
function renderGrid() {
    const { rows, cols } = FLOORS[activeFloor];
    const grid = document.getElementById('lockerGrid');
    const sz = cols <= 6 ? 88 : 80;
    grid.style.display = 'grid';
    grid.style.gap = '10px';
    grid.style.justifyContent = 'center';
    grid.style.gridTemplateColumns = `repeat(${cols}, ${sz}px)`;
    document.getElementById('floorLabel').textContent = `${activeFloor} — Layout View`;
    document.getElementById('floorMeta').textContent = `${cols} columns × ${rows} rows · ${getLockers().length} lockers`;
    grid.innerHTML = '';

    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            const locker = getAt(r, c);
            const cell = document.createElement('div');
            cell.dataset.row = r; cell.dataset.col = c;
            cell.style.width = sz + 'px'; cell.style.height = sz + 'px';

            if (!locker) {
                cell.className = 'empty-cell rounded-xl border-2 border-dashed border-zinc-200/60 bg-zinc-50/50 hover:border-yellow-300/50 hover:bg-yellow-50/30 transition-all';
                cell.ondragover = e => { e.preventDefault(); cell.classList.add('dragover'); };
                cell.ondragleave = () => cell.classList.remove('dragover');
                cell.ondrop = e => handleDrop(e, r, c);
            } else {
                const s = locker.status, clr = STATUS_CLR[s];
                const dimmed = activeFilter !== 'all' && s !== activeFilter;
                const isSelected = locker.id === selectedId;
                cell.className = `locker-cell relative rounded-xl border-2 cursor-pointer select-none flex flex-col items-center justify-center gap-0.5 px-1 transition-all ${dimmed ? 'opacity-20' : ''}`;
                cell.style.background = clr.bg;
                cell.style.borderColor = isSelected ? clr.dot : clr.border;
                if (isSelected) cell.style.boxShadow = `0 0 0 2px ${clr.dot}40, 0 4px 12px ${clr.dot}20`;
                cell.draggable = true; cell.dataset.id = locker.id;
                cell.ondragstart = e => { draggingId = locker.id; cell.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; };
                cell.ondragend = () => { draggingId = null; cell.classList.remove('dragging'); renderGrid(); };
                cell.ondragover = e => { e.preventDefault(); cell.classList.add('dragover'); };
                cell.ondragleave = () => cell.classList.remove('dragover');
                cell.ondrop = e => handleDrop(e, r, c);
                cell.onclick = () => selectLocker(locker.id);
                cell.style.background = `linear-gradient(145deg, ${clr.bg}, ${clr.bg}dd)`;
                cell.innerHTML = `
                    <div class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style="background:${clr.dot};box-shadow:0 0 8px ${clr.dot}80"></div>
                    <span class="font-mono text-[10px] font-bold" style="color:${clr.text};opacity:.8">${locker.number}</span>
                    <span class="text-lg leading-none mt-0.5">${STATUS_ICON[s]}</span>
                    ${locker.employee ? `<span class="text-[8px] font-semibold text-center leading-tight mt-0.5 px-1 truncate" style="color:${clr.text};max-width:100%;opacity:.75">${locker.employee.split(' ')[0]}</span>` : ''}`;
            }
            grid.appendChild(cell);
        }
    }
}

// ── Drag & Drop ──────────────────────────────────
function handleDrop(e, toRow, toCol) {
    e.preventDefault();
    document.querySelectorAll('.dragover').forEach(el => el.classList.remove('dragover'));
    if (!draggingId) return;
    const src = getById(draggingId); if (!src) return;
    const target = getAt(toRow, toCol);
    state[activeFloor] = state[activeFloor].map(l => {
        if (l.id === src.id) return { ...l, row: toRow, col: toCol };
        if (target && l.id === target.id) return { ...l, row: src.row, col: src.col };
        return l;
    });
    draggingId = null;
    toast(target ? `Swapped ${src.number} ↔ ${target.number}` : `Moved ${src.number} to [${toRow+1}, ${toCol+1}]`);
    renderAll();
}

// ── Select / Detail Panel ────────────────────────
function selectLocker(id) {
    selectedId = id === selectedId ? null : id;
    if (selectedId) renderDetail(getById(selectedId)); else closeDetail();
    renderGrid();
}

function renderDetail(locker) {
    if (!locker) return;
    const panel = document.getElementById('detailPanel');
    panel.classList.remove('hidden'); panel.classList.add('flex');
    const clr = STATUS_CLR[locker.status];
    document.getElementById('dp-number').textContent = locker.number;
    document.getElementById('dp-floor').textContent = locker.floor;
    document.getElementById('dp-pos').textContent = `Row ${locker.row+1}, Col ${locker.col+1}`;
    document.getElementById('dp-floorval').textContent = locker.floor;
    const badgeEl = document.getElementById('dp-badge');
    badgeEl.innerHTML = `<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-[11px] font-semibold"><div class="w-1.5 h-1.5 rounded-full"></div>${STATUS_LABEL[locker.status]}</div>`;
    const b = badgeEl.firstElementChild;
    b.style.cssText = `background:${clr.badgeBg};border-color:${clr.border};color:${clr.text}`;
    b.querySelector('div').style.cssText = `background:${clr.dot};box-shadow:0 0 5px ${clr.dot}`;
    let empHtml = '';
    if (locker.employee) {
        empHtml += `<div class="detail-row"><span class="text-[11px] text-zinc-400">Employee</span><span class="text-xs font-semibold text-zinc-700">${locker.employee}</span></div>`;
        empHtml += `<div class="detail-row"><span class="text-[11px] text-zinc-400">Emp ID</span><span class="text-xs font-semibold font-mono text-zinc-700">${locker.empId}</span></div>`;
        if (locker.dept) empHtml += `<div class="detail-row"><span class="text-[11px] text-zinc-400">Department</span><span class="text-xs font-semibold text-zinc-700">${locker.dept}</span></div>`;
        if (locker.assignedDate) empHtml += `<div class="detail-row"><span class="text-[11px] text-zinc-400">Assigned</span><span class="text-[11px] font-mono text-zinc-600">${locker.assignedDate}</span></div>`;
    } else empHtml = `<div class="detail-row"><span class="text-[11px] text-zinc-400">Employee</span><span class="text-xs text-zinc-300">— Unassigned —</span></div>`;
    document.getElementById('dp-emprows').innerHTML = empHtml;
    document.querySelectorAll('.status-btn').forEach(btn => {
        const s = btn.id.replace('sbtn-','');
        btn.style.opacity = locker.status === s ? '1' : '0.4';
    });
}

function closeDetail() {
    const p = document.getElementById('detailPanel');
    p.classList.add('hidden'); p.classList.remove('flex');
    selectedId = null;
}

// ── Change Status ────────────────────────────────
function changeStatus(newStatus) {
    if (!selectedId) return;
    state[activeFloor] = state[activeFloor].map(l => {
        if (l.id !== selectedId) return l;
        const clear = newStatus === 'available' || newStatus === 'maintenance';
        return { ...l, status: newStatus, employee: clear ? null : l.employee, empId: clear ? null : l.empId, dept: clear ? null : l.dept };
    });
    toast(`${getById(selectedId)?.number ?? ''} → ${STATUS_LABEL[newStatus]}`);
    renderAll(); renderDetail(getById(selectedId));
}

// ── Remove ───────────────────────────────────────
function removeLocker() {
    if (!selectedId) return;
    const locker = getById(selectedId);
    Swal.fire({
        title: 'Remove Locker?',
        text: `Are you sure you want to remove ${locker.number}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            state[activeFloor] = state[activeFloor].filter(l => l.id !== selectedId);
            selectedId = null; closeDetail();
            toast(`Removed ${locker.number}`, 'info'); renderAll();
        }
    });
}

// ── Filter ───────────────────────────────────────
function setFilter(f) { activeFilter = f; renderAll(); }

// ── Add Modal ────────────────────────────────────
function openAddModal() { const m = document.getElementById('addModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeAddModal() { const m = document.getElementById('addModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
function handleModalBg(e) { if (e.target === e.currentTarget) closeAddModal(); }
function toggleEmpFields() {
    const s = document.getElementById('add-status').value;
    const ef = document.getElementById('empFields');
    (s === 'occupied' || s === 'reserved') ? ef.classList.remove('hidden') : ef.classList.add('hidden');
}
function addLocker() {
    const row = parseInt(document.getElementById('add-row').value), col = parseInt(document.getElementById('add-col').value);
    const status = document.getElementById('add-status').value;
    const { rows, cols } = FLOORS[activeFloor];
    if (isNaN(row)||row<0||row>=rows||isNaN(col)||col<0||col>=cols) { toast(`Row 0–${rows-1}, Col 0–${cols-1}`, 'error'); return; }
    if (getAt(row, col)) { toast('Position already occupied!', 'error'); return; }
    const emp = document.getElementById('add-emp')?.value.trim()||null;
    const empId = document.getElementById('add-empid')?.value.trim()||null;
    const dept = document.getElementById('add-dept')?.value||null;
    const num = `L${String(getLockers().length+1).padStart(3,'0')}`;
    state[activeFloor].push({ id:`${activeFloor.replace(' ','')}-add-${Date.now()}`, number:num, row, col, floor:activeFloor, status, employee:emp, empId, dept, assignedDate: status==='occupied' ? new Date().toISOString().split('T')[0] : null });
    closeAddModal(); toast(`Added ${num}`); renderAll();
}

// ── Main render ──────────────────────────────────
function renderAll() { renderTabs(); renderStats(); renderGrid(); }
renderAll();
</script>
</body>
</html>