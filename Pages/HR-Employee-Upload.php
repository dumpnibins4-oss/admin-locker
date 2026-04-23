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
    <title>Admin Locker | Employee Upload</title>
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .serif { font-family: 'DM Serif Display', serif; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fu  { animation: fadeUp .35s ease both; }
        .fu1 { animation-delay: .04s; }
        .fu2 { animation-delay: .08s; }
        .fu3 { animation-delay: .12s; }
        .fu4 { animation-delay: .16s; }

        /* Search dropdown */
        #search-dropdown {
            max-height: 280px;
            overflow-y: auto;
        }
        #search-dropdown::-webkit-scrollbar { width: 4px; }
        #search-dropdown::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 99px; }

        .search-item {
            transition: background .12s;
            cursor: pointer;
        }
        .search-item:hover { background: #f4f4f5; }
        .search-item.selected-item { background: #fefce8; }

        /* Option pill toggle */
        .opt-pill {
            flex: 1;
            height: 44px;
            border-radius: 14px;
            border: 2px solid #e4e4e7;
            background: #fff;
            font-size: 13px;
            font-weight: 600;
            color: #71717a;
            cursor: pointer;
            transition: all .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .opt-pill:hover { border-color: #a1a1aa; color: #3f3f46; }
        .opt-pill.active-pill { border-color: #1c1c1c; background: #1c1c1c; color: #fff; }
        .opt-pill.active-yellow { border-color: #facc15; background: #fefce8; color: #713f12; }
        .opt-pill.active-blue   { border-color: #93c5fd; background: #eff6ff; color: #1d4ed8; }
        .opt-pill.active-pink   { border-color: #f9a8d4; background: #fdf2f8; color: #9d174d; }
        .opt-pill.active-emerald{ border-color: #6ee7b7; background: #ecfdf5; color: #065f46; }

        /* Batch table */
        .batch-row { transition: background .12s; }
        .batch-row:hover { background: #f9f9f9; }

        /* Drop zone */
        .drop-zone {
            border: 2px dashed #d4d4d8;
            border-radius: 20px;
            padding: 32px 16px;
            text-align: center;
            transition: all .2s;
            cursor: pointer;
        }
        .drop-zone:hover, .drop-zone.drag-over {
            border-color: #facc15;
            background: #fefce8;
        }
        .drop-zone.drag-over {
            transform: scale(1.01);
            box-shadow: 0 0 20px rgba(250,204,21,.15);
        }
        .csv-preview-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .csv-preview-table th { background: #f4f4f5; font-size: 10px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: .05em; padding: 8px 12px; text-align: left; position: sticky; top: 0; }
        .csv-preview-table th:first-child { border-radius: 10px 0 0 10px; }
        .csv-preview-table th:last-child { border-radius: 0 10px 10px 0; }
        .csv-preview-table td { font-size: 12px; padding: 8px 12px; border-bottom: 1px solid #f4f4f5; color: #3f3f46; }
        .csv-preview-table tr:last-child td { border-bottom: none; }
        .csv-err { background: #fef2f2; }
        .csv-err td { color: #b91c1c; }

        /* Step indicator */
        .step-dot {
            width: 28px; height: 28px;
            border-radius: 999px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }
        .step-line { flex: 1; height: 2px; background: #e4e4e7; }
        .step-line.done { background: #1c1c1c; }

        input, select {
            outline: none;
            transition: border-color .15s;
        }
        input:focus, select:focus { border-color: #a1a1aa !important; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #ddd; border-radius: 99px; }
    </style>
</head>
<body class="bg-zinc-100 min-h-screen overflow-y-auto">

<div class="p-3 pl-0 w-full mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6 fu fu1">
        <div>
            <h1 class="serif text-[2rem] leading-tight text-zinc-800">Employee Upload</h1>
            <p class="text-sm text-zinc-400 mt-0.5">Search employees from the system and add locker details before plotting</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Download Template -->
            <button onclick="downloadTemplate()" class="flex items-center gap-2 h-10 px-4 rounded-2xl border border-zinc-200 bg-white text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition-all cursor-pointer">
                <i class="fa-solid fa-download text-zinc-400 text-xs"></i>
                Download Template
            </button>
            <!-- Bulk Upload -->
            <button onclick="openBulkModal()" class="flex items-center gap-2 h-10 px-4 rounded-2xl bg-zinc-800 text-sm font-semibold text-white hover:bg-zinc-700 transition-all cursor-pointer">
                <i class="fa-solid fa-file-csv text-yellow-400 text-xs"></i>
                Bulk Upload CSV
            </button>
        </div>
    </div>

    <!-- STEP INDICATOR -->
    <div class="flex items-center gap-2 mb-6 fu fu2">
        <div class="step-dot bg-zinc-800 text-white" id="step1-dot">1</div>
        <span class="text-xs font-semibold text-zinc-800" id="step1-lbl">Search Employee</span>
        <div class="step-line" id="line1"></div>
        <div class="step-dot bg-zinc-200 text-zinc-400" id="step2-dot">2</div>
        <span class="text-xs font-semibold text-zinc-400" id="step2-lbl">Add Locker Details</span>
        <div class="step-line" id="line2"></div>
        <div class="step-dot bg-zinc-200 text-zinc-400" id="step3-dot">3</div>
        <span class="text-xs font-semibold text-zinc-400" id="step3-lbl">Review & Submit</span>
    </div>

    <div class="grid grid-cols-5 gap-4">

        <!-- LEFT: Search + Form -->
        <div class="col-span-3 flex flex-col gap-4">

            <!-- STEP 1: Search -->
            <div class="bg-white rounded-3xl p-5 border border-zinc-200 fu fu3" id="step1-card">
                <div class="flex items-center gap-2 mb-4">
                    <div class="step-dot bg-zinc-800 text-white text-xs">1</div>
                    <p class="text-sm font-semibold text-zinc-800">Search Employee</p>
                </div>

                <!-- Search box -->
                <div class="relative mb-3">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                    <input
                        id="emp-search"
                        type="text"
                        placeholder="Search by name or Biometrics ID..."
                        oninput="searchEmployee(this.value)"
                        onfocus="showDropdown()"
                        class="w-full h-11 pl-9 pr-10 rounded-xl border border-zinc-200 text-sm text-zinc-800 bg-zinc-50 focus:bg-white"
                    >
                    <!-- Loading spinner -->
                    <div id="empSearchSpinner" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                        <i class="fa-solid fa-spinner fa-spin text-zinc-300 text-sm"></i>
                    </div>
                    <!-- Dropdown -->
                    <div id="search-dropdown"
                        class="absolute top-full left-0 right-0 mt-1.5 bg-white border border-zinc-200 rounded-2xl shadow-lg z-20 hidden">
                        <!-- populated by JS -->
                    </div>
                </div>

                <!-- Selected employee card -->
                <div id="selected-emp-card" class="hidden rounded-2xl border-2 border-zinc-800 bg-zinc-50 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-zinc-200 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <img id="sel-photo" src="" alt=""
                                class="w-full h-full object-cover hidden"
                                onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden')">
                            <i class="fa-solid fa-user text-zinc-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-zinc-800" id="sel-name">—</p>
                            <p class="text-xs text-zinc-400" id="sel-id">—</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span id="sel-type-badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold"></span>
                            <span class="text-[10px] text-zinc-400" id="sel-hired">—</span>
                        </div>
                        <button onclick="clearSelected()" class="w-7 h-7 rounded-xl bg-zinc-200 hover:bg-zinc-300 flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fa-solid fa-xmark text-zinc-500 text-xs"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="bg-white rounded-xl p-2.5">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-0.5">Department</p>
                            <p class="text-xs font-semibold text-zinc-800" id="sel-dept">—</p>
                        </div>
                        <div class="bg-white rounded-xl p-2.5">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-0.5">Employment Type</p>
                            <p class="text-xs font-semibold text-zinc-800" id="sel-emptype">—</p>
                        </div>
                    </div>
                </div>

                <div id="no-selection" class="text-center py-6 text-sm text-zinc-400">
                    <i class="fa-solid fa-magnifying-glass text-2xl text-zinc-300 mb-2 block"></i>
                    Search and select an employee above
                </div>
            </div>

            <!-- STEP 2: Locker Details -->
            <div class="bg-white rounded-3xl p-5 border border-zinc-200 fu fu4" id="step2-card">
                <div class="flex items-center gap-2 mb-5">
                    <div class="step-dot bg-zinc-200 text-zinc-400 text-xs" id="s2-dot">2</div>
                    <p class="text-sm font-semibold text-zinc-500" id="s2-title">Add Locker Details</p>
                    <span class="text-xs text-zinc-400 ml-auto" id="s2-hint">Select an employee first</span>
                </div>

                <div id="locker-form" class="space-y-5 opacity-40 pointer-events-none" >

                    <!-- Phase -->
                    <div>
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-2">
                            Phase <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-2">
                            <?php foreach(['Phase 1','Phase 2','Phase 3','Phase 4'] as $p): ?>
                            <button type="button" onclick="selectOpt('phase','<?= $p ?>',this,'active-pill')"
                                class="opt-pill" data-group="phase" data-val="<?= $p ?>">
                                <?= str_replace('Phase ','P',$p) ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Classification -->
                    <div>
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-2">
                            Classification <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectOpt('classif','Production',this,'active-yellow')"
                                class="opt-pill" data-group="classif" data-val="Production">
                                <i class="fa-solid fa-industry text-xs"></i> Production
                            </button>
                            <button type="button" onclick="selectOpt('classif','Non-Production',this,'active-pill')"
                                class="opt-pill" data-group="classif" data-val="Non-Production">
                                <i class="fa-solid fa-building text-xs"></i> Non-Production
                            </button>
                        </div>
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-2">
                            Employment Type <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectOpt('emptype','Regular',this,'active-emerald')"
                                class="opt-pill" data-group="emptype" data-val="Regular">
                                <i class="fa-solid fa-user-check text-xs"></i> Regular
                            </button>
                            <button type="button" onclick="selectOpt('emptype','Probationary',this,'active-yellow')"
                                class="opt-pill" data-group="emptype" data-val="Probationary">
                                <i class="fa-solid fa-user-clock text-xs"></i> Probationary
                            </button>
                        </div>
                        <p class="text-[10px] text-zinc-400 mt-1.5">Auto-detected from date hired. Override if needed.</p>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-2">
                            Gender <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectOpt('gender','Male',this,'active-blue')"
                                class="opt-pill" data-group="gender" data-val="Male">
                                <i class="fa-solid fa-mars text-xs"></i> Male
                            </button>
                            <button type="button" onclick="selectOpt('gender','Female',this,'active-pink')"
                                class="opt-pill" data-group="gender" data-val="Female">
                                <i class="fa-solid fa-venus text-xs"></i> Female
                            </button>
                        </div>
                    </div>

                    <!-- Notes (optional) -->
                    <div>
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-2">Notes (optional)</label>
                        <textarea id="notes" rows="2" placeholder="Any special instructions..."
                            class="w-full rounded-xl border border-zinc-200 p-3 text-sm text-zinc-800 resize-none bg-zinc-50 focus:bg-white"></textarea>
                    </div>

                    <!-- Add to batch -->
                    <button type="button" onclick="addToBatch()"
                        class="w-full h-11 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-white text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Add to Batch
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Batch queue -->
        <div class="col-span-2 fu fu4">
            <div class="bg-white rounded-3xl p-5 border border-zinc-200 sticky top-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-semibold text-zinc-800">Batch Queue</p>
                        <p class="text-xs text-zinc-400">Employees ready to submit</p>
                    </div>
                    <span id="batch-count" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-100 text-zinc-500">0 added</span>
                </div>

                <!-- Empty state -->
                <div id="batch-empty" class="text-center py-10">
                    <i class="fa-solid fa-layer-group text-3xl text-zinc-200 mb-2 block"></i>
                    <p class="text-sm text-zinc-400">No employees added yet</p>
                    <p class="text-xs text-zinc-300 mt-0.5">Fill the form and click Add to Batch</p>
                </div>

                <!-- Batch list -->
                <div id="batch-list" class="hidden space-y-2 mb-4 max-h-[380px] overflow-y-auto pr-1"></div>

                <!-- Summary -->
                <div id="batch-summary" class="hidden border-t border-zinc-100 pt-4 mb-4 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-zinc-400">Total</span>
                        <span class="font-bold text-zinc-800" id="sum-total">0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-zinc-400"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Male</span>
                        <span class="font-semibold text-zinc-700" id="sum-male">0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-zinc-400"><span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span>Female</span>
                        <span class="font-semibold text-zinc-700" id="sum-female">0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-zinc-400"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Regular</span>
                        <span class="font-semibold text-zinc-700" id="sum-regular">0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-zinc-400"><span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>Probationary</span>
                        <span class="font-semibold text-zinc-700" id="sum-probat">0</span>
                    </div>
                </div>

                <!-- Submit -->
                <button id="submit-btn" onclick="submitBatch()"
                    class="hidden w-full h-11 rounded-xl bg-yellow-400 hover:bg-yellow-300 text-zinc-800 text-sm font-bold transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    Submit for Locker Plotting
                </button>

                <!-- Clear all -->
                <button id="clear-btn" onclick="clearBatch()"
                    class="hidden w-full h-9 rounded-xl bg-zinc-50 hover:bg-zinc-100 text-zinc-400 text-xs font-semibold transition-all mt-2">
                    Clear All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- BULK UPLOAD MODAL -->
<div id="bulkModal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/40 backdrop-blur-sm" onclick="if(event.target===event.currentTarget)closeBulkModal()">
    <div class="bg-white rounded-3xl w-[720px] max-h-[85vh] shadow-2xl flex flex-col" style="animation:fadeUp .25s ease both">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-5 border-b border-zinc-100 flex-shrink-0">
            <div>
                <h2 class="serif text-xl text-zinc-800">Bulk Upload Employees</h2>
                <p class="text-xs text-zinc-400 mt-0.5">Upload a CSV file with employee data and locker details</p>
            </div>
            <button onclick="closeBulkModal()" class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 flex items-center justify-center cursor-pointer transition-colors">
                <i class="fa-solid fa-xmark text-zinc-400 text-sm"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 overflow-y-auto flex-1">
            <!-- Drop zone -->
            <div id="dropZone" class="drop-zone mb-4"
                 ondragover="event.preventDefault();this.classList.add('drag-over')"
                 ondragleave="this.classList.remove('drag-over')"
                 ondrop="handleDrop(event)"
                 onclick="document.getElementById('bulkFileInput').click()">
                <i class="fa-solid fa-cloud-arrow-up text-3xl text-zinc-300 mb-2 block"></i>
                <p class="text-sm font-semibold text-zinc-600">Drop your CSV file here</p>
                <p class="text-xs text-zinc-400 mt-1">or click to browse · <span class="text-yellow-600 font-semibold cursor-pointer" onclick="event.stopPropagation();downloadTemplate()">Download template</span></p>
                <input id="bulkFileInput" type="file" accept=".csv" class="hidden" onchange="parseBulkCSV(this.files[0])">
            </div>

            <!-- Template columns hint -->
            <div id="csvHint" class="bg-zinc-50 rounded-2xl p-4 mb-4">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Expected Columns</p>
                <div class="flex flex-wrap gap-1.5">
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] font-semibold text-zinc-600">Biometric No. <span class="text-red-400">*</span></span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] font-semibold text-zinc-600">Employee Name <span class="text-red-400">*</span></span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] font-semibold text-zinc-600">Area <span class="text-red-400">*</span></span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] font-semibold text-zinc-600">Gender <span class="text-red-400">*</span></span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] font-semibold text-zinc-600">Locker No. <span class="text-red-400">*</span></span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] text-zinc-400">Contact Number</span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] text-zinc-400">Schedule</span>
                    <span class="px-2 py-1 rounded-lg bg-white border border-zinc-200 text-[11px] text-zinc-400">REMARKS</span>
                </div>
                <p class="text-[10px] text-zinc-400 mt-2"><i class="fa-solid fa-circle-info text-zinc-300 mr-1"></i>The <strong>Area</strong> column auto-detects Phase and Classification (e.g. "PHASE 1 MOLDING" → Phase 1, Production)</p>
            </div>

            <!-- Preview area (hidden until file parsed) -->
            <div id="csvPreview" class="hidden">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-file-csv text-emerald-500"></i>
                        <span class="text-sm font-semibold text-zinc-800" id="csvFileName">file.csv</span>
                        <span class="text-xs text-zinc-400" id="csvRowCount"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="csvValidBadge" class="hidden px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600">✓ All valid</span>
                        <span id="csvErrorBadge" class="hidden px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-500"></span>
                        <button onclick="resetBulkModal()" class="text-xs text-zinc-400 hover:text-zinc-600 transition-colors"><i class="fa-solid fa-rotate-right text-[10px]"></i> Re-upload</button>
                    </div>
                </div>
                <div class="rounded-2xl border border-zinc-200 overflow-hidden max-h-[320px] overflow-y-auto">
                    <table class="csv-preview-table" id="csvTable"></table>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-5 border-t border-zinc-100 flex-shrink-0">
            <button onclick="closeBulkModal()" class="px-5 py-2.5 rounded-xl bg-zinc-100 text-sm font-semibold text-zinc-500 hover:bg-zinc-200 transition-colors cursor-pointer">Cancel</button>
            <button id="bulkImportBtn" onclick="importBulkToBatch()" disabled class="px-6 py-2.5 rounded-xl bg-zinc-800 text-sm font-semibold text-white hover:bg-zinc-700 transition-all cursor-pointer disabled:opacity-40 disabled:pointer-events-none flex items-center gap-2">
                <i class="fa-solid fa-file-import text-xs"></i>
                Import to Batch
            </button>
        </div>
    </div>
</div>

<!-- ── DATA ── -->
<script>
let EMPLOYEES = [];

/* ── State ── */
const state = {
    selected: null,
    form: { phase: null, classif: null, emptype: null, gender: null },
    batch: [],
};

/* ── Search ── */
function searchEmployee(q) {
    const dd = document.getElementById('search-dropdown');
    const term = q.toLowerCase().trim();
    if (!term) { dd.classList.add('hidden'); return; }

    if (!EMPLOYEES.length) {
        dd.innerHTML = `<div class="px-4 py-3 text-sm text-zinc-400"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Loading employees...</div>`;
        dd.classList.remove('hidden');
        return;
    }

    const results = EMPLOYEES.filter(e =>
        (e.FirstName + ' ' + e.LastName).toLowerCase().includes(term) ||
        (e.BiometricsID || '').toLowerCase().includes(term) ||
        (e.EmployeeID || '').toLowerCase().includes(term)
    ).slice(0, 20); // limit to 20 results for performance

    if (!results.length) {
        dd.innerHTML = `<div class="px-4 py-3 text-sm text-zinc-400">No results found</div>`;
        dd.classList.remove('hidden');
        return;
    }

    dd.innerHTML = results.map(e => {
        const empStatus = e.EmploymentStatus || '';
        const isInBatch = state.batch.find(b => b.BiometricsID === e.BiometricsID);
        return `
        <div class="search-item flex items-center gap-3 px-4 py-2.5 ${isInBatch ? 'opacity-40 pointer-events-none' : ''}"
             onclick="selectEmployee('${e.BiometricsID}')">
            <div class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user text-zinc-400 text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-zinc-800">${e.FirstName} ${e.LastName}</p>
                <p class="text-xs text-zinc-400">${e.BiometricsID || e.EmployeeID} &middot; ${e.Department || ''}</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${empStatus === 'Regular' ? 'bg-emerald-50 text-emerald-600' : 'bg-yellow-50 text-yellow-700'}">
                ${empStatus || 'N/A'}
            </span>
        </div>
    `}).join('');
    dd.classList.remove('hidden');
}

function showDropdown() {
    const q = document.getElementById('emp-search').value;
    if (q.trim()) searchEmployee(q);
}

document.addEventListener('click', e => {
    if (!e.target.closest('#emp-search') && !e.target.closest('#search-dropdown')) {
        document.getElementById('search-dropdown').classList.add('hidden');
    }
});

function selectEmployee(id) {
    const emp = EMPLOYEES.find(e => e.BiometricsID === id);
    if (!emp) return;
    state.selected = emp;

    const empStatus = emp.EmploymentStatus || '';

    // Fill card
    document.getElementById('sel-name').textContent  = emp.FirstName + ' ' + emp.LastName;
    document.getElementById('sel-id').textContent    = emp.BiometricsID || emp.EmployeeID;
    document.getElementById('sel-dept').textContent  = emp.Department || '—';
    document.getElementById('sel-emptype').textContent = empStatus || '—';
    document.getElementById('sel-hired').textContent = emp.DateHired ? 'Hired ' + emp.DateHired : '—';

    // Try photo
    const photo = document.getElementById('sel-photo');
    photo.src = `http://10.2.0.8/lrnph/emp_photos/${emp.BiometricsID}.jpg`;
    photo.classList.remove('hidden');

    // Type badge
    const badge = document.getElementById('sel-type-badge');
    badge.textContent = empStatus || 'N/A';
    badge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold ' +
        (empStatus === 'Regular' ? 'bg-emerald-50 text-emerald-600' : 'bg-yellow-50 text-yellow-700');

    document.getElementById('selected-emp-card').classList.remove('hidden');
    document.getElementById('no-selection').classList.add('hidden');
    document.getElementById('emp-search').value = '';
    document.getElementById('search-dropdown').classList.add('hidden');

    // Auto-fill employment type & classification from DB
    autoSelectEmpType(empStatus);
    autoSelectClassif(emp.Department || '');
    // Auto-fill gender from DB
    if (emp.Gender) autoSelectGender(emp.Gender);

    // Enable form
    enableForm();
    updateSteps(2);
}

function clearSelected() {
    state.selected = null;
    state.form = { phase: null, classif: null, emptype: null, gender: null };
    document.getElementById('selected-emp-card').classList.add('hidden');
    document.getElementById('no-selection').classList.remove('hidden');
    document.getElementById('emp-search').value = '';
    // Reset pills
    document.querySelectorAll('.opt-pill').forEach(p => {
        p.className = 'opt-pill';
    });
    disableForm();
    updateSteps(1);
}

/* ── Auto-fill helpers ── */
function autoSelectEmpType(type) {
    if (!type) return;
    const activeClass = type === 'Regular' ? 'active-emerald' : 'active-yellow';
    document.querySelectorAll('[data-group="emptype"]').forEach(b => {
        b.className = 'opt-pill';
        if (b.dataset.val === type) { b.classList.add(activeClass); state.form.emptype = type; }
    });
}
function autoSelectClassif(dept) {
    if (!dept) return;
    const deptLower = dept.toLowerCase();
    const isProduction = deptLower.includes('production') && !deptLower.includes('non');
    const val = isProduction ? 'Production' : 'Non-Production';
    const activeClass = val === 'Production' ? 'active-yellow' : 'active-pill';
    document.querySelectorAll('[data-group="classif"]').forEach(b => {
        b.className = 'opt-pill';
        if (b.dataset.val === val) { b.classList.add(activeClass); state.form.classif = val; }
    });
}
function autoSelectGender(gender) {
    if (!gender) return;
    const normalized = gender.trim().charAt(0).toUpperCase() + gender.trim().slice(1).toLowerCase();
    const val = normalized === 'Male' ? 'Male' : normalized === 'Female' ? 'Female' : null;
    if (!val) return;
    const activeClass = val === 'Male' ? 'active-blue' : 'active-pink';
    document.querySelectorAll('[data-group="gender"]').forEach(b => {
        b.className = 'opt-pill';
        if (b.dataset.val === val) { b.classList.add(activeClass); state.form.gender = val; }
    });
}

/* ── Pill select ── */
function selectOpt(group, val, el, activeClass) {
    document.querySelectorAll(`[data-group="${group}"]`).forEach(b => b.className = 'opt-pill');
    el.classList.add(activeClass);
    state.form[group] = val;
}

/* ── Form enable/disable ── */
function enableForm() {
    const f = document.getElementById('locker-form');
    f.classList.remove('opacity-40','pointer-events-none');
    document.getElementById('s2-dot').className = 'step-dot bg-zinc-800 text-white text-xs';
    document.getElementById('s2-title').className = 'text-sm font-semibold text-zinc-800';
    document.getElementById('s2-hint').textContent = '';
}
function disableForm() {
    const f = document.getElementById('locker-form');
    f.classList.add('opacity-40','pointer-events-none');
    document.getElementById('s2-dot').className = 'step-dot bg-zinc-200 text-zinc-400 text-xs';
    document.getElementById('s2-title').className = 'text-sm font-semibold text-zinc-500';
    document.getElementById('s2-hint').textContent = 'Select an employee first';
}

/* ── Step indicator ── */
function updateSteps(active) {
    const dots  = ['step1-dot','step2-dot','step3-dot'];
    const lbls  = ['step1-lbl','step2-lbl','step3-lbl'];
    const lines = ['line1','line2'];
    dots.forEach((id, i) => {
        const el = document.getElementById(id);
        el.className = 'step-dot ' + (i < active ? 'bg-zinc-800 text-white' : 'bg-zinc-200 text-zinc-400');
    });
    lbls.forEach((id, i) => {
        document.getElementById(id).className = 'text-xs font-semibold ' + (i < active ? 'text-zinc-800' : 'text-zinc-400');
    });
    lines.forEach((id, i) => {
        document.getElementById(id).className = 'step-line ' + (i < active - 1 ? 'done' : '');
    });
}

/* ── Add to batch ── */
function addToBatch() {
    if (!state.selected) { alert('Select an employee first.'); return; }
    const { phase, classif, emptype, gender } = state.form;
    if (!phase)   { Swal.fire({ icon:'warning', title:'Missing field', text:'Please select a Phase.',              confirmButtonColor:'#1c1c1c' }); return; }
    if (!classif) { Swal.fire({ icon:'warning', title:'Missing field', text:'Please select a Classification.',     confirmButtonColor:'#1c1c1c' }); return; }
    if (!emptype) { Swal.fire({ icon:'warning', title:'Missing field', text:'Please select an Employment Type.',   confirmButtonColor:'#1c1c1c' }); return; }
    if (!gender)  { Swal.fire({ icon:'warning', title:'Missing field', text:'Please select a Gender.',             confirmButtonColor:'#1c1c1c' }); return; }

    if (state.batch.find(b => b.BiometricsID === state.selected.BiometricsID)) {
        Swal.fire({ icon:'info', title:'Already added', text:'This employee is already in the batch.', confirmButtonColor:'#1c1c1c' });
        return;
    }

    state.batch.push({
        ...state.selected,
        phase, classif, emptype, gender,
        notes: document.getElementById('notes').value.trim(),
    });

    renderBatch();
    clearSelected();
    updateSteps(3);

    // Reset form
    document.getElementById('notes').value = '';
    document.querySelectorAll('.opt-pill').forEach(p => p.className = 'opt-pill');
    state.form = { phase: null, classif: null, emptype: null, gender: null };
}

/* ── Batch render ── */
function renderBatch() {
    const list  = document.getElementById('batch-list');
    const empty = document.getElementById('batch-empty');
    const sumEl = document.getElementById('batch-summary');
    const subBtn= document.getElementById('submit-btn');
    const clrBtn= document.getElementById('clear-btn');
    const cntEl = document.getElementById('batch-count');

    const b = state.batch;
    cntEl.textContent = b.length + ' added';
    cntEl.className   = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold ' +
        (b.length ? 'bg-zinc-800 text-white' : 'bg-zinc-100 text-zinc-500');

    if (!b.length) {
        list.classList.add('hidden'); empty.classList.remove('hidden');
        sumEl.classList.add('hidden'); subBtn.classList.add('hidden');
        clrBtn.classList.add('hidden');
        return;
    }

    empty.classList.add('hidden');
    list.classList.remove('hidden');
    sumEl.classList.remove('hidden');
    subBtn.classList.remove('hidden');
    clrBtn.classList.remove('hidden');

    list.innerHTML = b.map((e, i) => `
        <div class="batch-row flex items-center gap-3 p-3 rounded-2xl bg-zinc-50 border border-zinc-100">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                ${e.gender==='Male'?'bg-blue-50':'bg-pink-50'}">
                <i class="fa-solid ${e.gender==='Male'?'fa-mars text-blue-400':'fa-venus text-pink-400'} text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-zinc-800 truncate">${e.FirstName} ${e.LastName}</p>
                <p class="text-[10px] text-zinc-400">${e.phase} &middot; ${e.classif} &middot; ${e.emptype}</p>
            </div>
            <button onclick="removeBatch(${i})"
                class="w-6 h-6 rounded-lg bg-zinc-200 hover:bg-red-100 hover:text-red-500 flex items-center justify-center text-zinc-400 transition-colors flex-shrink-0">
                <i class="fa-solid fa-xmark text-[10px]"></i>
            </button>
        </div>
    `).join('');

    // Summary
    document.getElementById('sum-total').textContent   = b.length;
    document.getElementById('sum-male').textContent    = b.filter(e=>e.gender==='Male').length;
    document.getElementById('sum-female').textContent  = b.filter(e=>e.gender==='Female').length;
    document.getElementById('sum-regular').textContent = b.filter(e=>e.emptype==='Regular').length;
    document.getElementById('sum-probat').textContent  = b.filter(e=>e.emptype==='Probationary').length;
}

function removeBatch(i) {
    state.batch.splice(i, 1);
    renderBatch();
    if (!state.batch.length) updateSteps(1);
}

function clearBatch() {
    state.batch = [];
    renderBatch();
    updateSteps(1);
}

/* ── Submit ── */
function submitBatch() {
    if (!state.batch.length) return;
    Swal.fire({
        title: 'Submit ' + state.batch.length + ' employee' + (state.batch.length > 1 ? 's' : '') + '?',
        text: 'They will be queued for locker plotting.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1c1c1c',
        cancelButtonColor: '#e4e4e7',
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'Cancel',
    }).then(r => {
        if (!r.isConfirmed) return;
        // POST state.batch to your API here
        Swal.fire({
            icon: 'success',
            title: 'Submitted!',
            text: state.batch.length + ' employee(s) ready for locker plotting.',
            confirmButtonColor: '#1c1c1c',
            timer: 2000,
            showConfirmButton: false,
        }).then(() => {
            state.batch = [];
            renderBatch();
            updateSteps(1);
        });
    });
}

/* ── Download Template ── */
function downloadTemplate() {
    const header = 'No.,Biometric No.,Employee Name,Contact Number,Schedule,Area,Gender,REMARKS,Locker No.';
    const sample = [
        '1,2026-41977,AGUAS KIM RUSSEL CACAP,09468247180,8A-4P,PHASE 1 MOLDING,MALE,Redeploy,MPL-A033-08',
        '2,2026-42067,ALA JERICO BAGUE,09947739638,8A-4P,PHASE 1 MOLDING,MALE,,MPL-A033-21',
        '3,2026-41997,BUNGQUE ASHLEY COSME,09859268259,8A-4P,PHASE 1 MOLDING,FEMALE,,FPL-B006-10',
    ];
    const csv = [header, ...sample].join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'employee_upload_template.csv';
    a.click();
    URL.revokeObjectURL(url);
}

/* ── Bulk Modal ── */
let bulkParsed = []; // [{emp, phase, gender, classif, emptype, notes, error}]

function openBulkModal() {
    const m = document.getElementById('bulkModal');
    m.classList.remove('hidden'); m.classList.add('flex');
    resetBulkModal();
}
function closeBulkModal() {
    const m = document.getElementById('bulkModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
function resetBulkModal() {
    bulkParsed = [];
    document.getElementById('csvPreview').classList.add('hidden');
    document.getElementById('csvHint').classList.remove('hidden');
    document.getElementById('dropZone').classList.remove('hidden');
    document.getElementById('bulkImportBtn').disabled = true;
    document.getElementById('csvValidBadge').classList.add('hidden');
    document.getElementById('csvErrorBadge').classList.add('hidden');
    document.getElementById('bulkFileInput').value = '';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.remove('drag-over');
    const file = e.dataTransfer?.files?.[0];
    if (file && file.name.endsWith('.csv')) parseBulkCSV(file);
    else Swal.fire({ icon:'error', title:'Invalid file', text:'Please drop a .csv file.', confirmButtonColor:'#1c1c1c' });
}

const VALID_PHASES = ['Phase 1','Phase 2','Phase 3','Phase 4'];
const VALID_GENDERS = ['Male','Female'];
const VALID_CLASSIF = ['Production','Non-Production'];
const VALID_EMPTYPE = ['Regular','Probationary'];

function findCol(headers, ...keywords) {
    return headers.findIndex(h => keywords.some(k => h.toLowerCase().replace(/[_\s\-.]/g,'').includes(k.toLowerCase().replace(/[_\s\-.]/g,''))));
}

/* Parse the "Area" column (e.g. "PHASE 1 MOLDING") into phase + classification */
function parseArea(raw) {
    if (!raw) return { phase: '', classif: '' };
    const s = raw.trim().toUpperCase();
    // Extract phase number
    const phaseMatch = s.match(/PHASE\s*(\d)/);
    const phase = phaseMatch ? 'Phase ' + phaseMatch[1] : '';
    // Determine classification from remaining text
    const nonProduction = ['NON-PRODUCTION','NONPRODUCTION','NON PRODUCTION','OFFICE','ADMIN','SUPPORT','QA','QUALITY'];
    const isNonProd = nonProduction.some(kw => s.includes(kw));
    const classif = isNonProd ? 'Non-Production' : 'Production';
    return { phase, classif };
}

/* Normalize gender: MALE→Male, FEMALE→Female */
function normalizeGender(raw) {
    if (!raw) return '';
    const s = raw.trim().toUpperCase();
    if (s === 'MALE' || s === 'M') return 'Male';
    if (s === 'FEMALE' || s === 'F') return 'Female';
    return raw.trim();
}

/* Detect header row: skip metadata rows like "FOR DEPLOYMENT..." or "DATE:..." */
function findHeaderRow(lines) {
    for (let i = 0; i < Math.min(lines.length, 10); i++) {
        const lower = lines[i].toLowerCase();
        if (lower.includes('biometric') || lower.includes('employee name') || lower.includes('employee id')) {
            return i;
        }
    }
    return 0; // fallback to first line
}

function parseBulkCSV(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const text = e.target.result.replace(/\r/g, '');
        const allLines = text.split('\n').filter(l => l.trim());
        if (allLines.length < 2) {
            Swal.fire({ icon:'error', title:'Empty CSV', text:'The file has no data rows.', confirmButtonColor:'#1c1c1c' });
            return;
        }

        const headerIdx = findHeaderRow(allLines);
        const headers = allLines[headerIdx].split(',').map(h => h.trim());
        const dataLines = allLines.slice(headerIdx + 1);

        // Map columns from the real spreadsheet format
        const idIdx      = findCol(headers, 'biometricno', 'biometricsid', 'employeeid', 'empid');
        const nameIdx    = findCol(headers, 'employeename', 'name');
        const contactIdx = findCol(headers, 'contactnumber', 'contact', 'phone');
        const schedIdx   = findCol(headers, 'schedule');
        const areaIdx    = findCol(headers, 'area');
        const genderIdx  = findCol(headers, 'gender', 'sex');
        const remarksIdx = findCol(headers, 'remarks', 'notes');
        const lockerIdx  = findCol(headers, 'lockerno', 'locker');
        // Also support explicit phase/classif/emptype columns (our template fallback)
        const phaseIdx   = findCol(headers, 'phase');
        const classifIdx = findCol(headers, 'classification', 'classif');
        const emptypeIdx = findCol(headers, 'employmenttype', 'emptype');

        if (idIdx < 0) {
            Swal.fire({ icon:'error', title:'Missing Column', text:'CSV must have a Biometric No. or EmployeeID column.', confirmButtonColor:'#1c1c1c' });
            return;
        }

        bulkParsed = [];
        let errorCount = 0;

        dataLines.forEach(line => {
            const cols = line.split(',').map(c => c.trim());
            const id = cols[idIdx] || '';
            if (!id) return; // skip empty rows

            const emp = EMPLOYEES.find(e => e.BiometricsID === id);
            const empName = nameIdx >= 0 ? cols[nameIdx] : (emp ? emp.FirstName + ' ' + emp.LastName : '');

            // Parse Area column if available, otherwise use explicit phase/classif columns
            let phase, classif;
            if (areaIdx >= 0 && cols[areaIdx]) {
                const parsed = parseArea(cols[areaIdx]);
                phase = parsed.phase;
                classif = parsed.classif;
            } else {
                phase = phaseIdx >= 0 ? cols[phaseIdx] : '';
                classif = classifIdx >= 0 ? cols[classifIdx] : '';
            }

            const gender  = normalizeGender(genderIdx >= 0 ? cols[genderIdx] : '');
            const emptype = emptypeIdx >= 0 ? cols[emptypeIdx] : (emp ? emp.EmploymentType : '');
            const notes   = remarksIdx >= 0 ? (cols[remarksIdx] || '') : '';
            const lockerNo = lockerIdx >= 0 ? (cols[lockerIdx] || '') : '';
            const contact  = contactIdx >= 0 ? (cols[contactIdx] || '') : '';
            const schedule = schedIdx >= 0 ? (cols[schedIdx] || '') : '';

            const errors = [];
            if (!emp) errors.push('Employee not found');
            if (state.batch.find(b => b.BiometricsID === id)) errors.push('Already in batch');
            if (phase && !VALID_PHASES.includes(phase)) errors.push('Invalid phase');
            if (gender && !VALID_GENDERS.includes(gender)) errors.push('Invalid gender');
            if (!phase) errors.push('Missing phase/area');
            if (!gender) errors.push('Missing gender');

            const error = errors.length ? errors.join('; ') : null;
            if (error) errorCount++;

            bulkParsed.push({
                id, emp, empName, phase, gender, classif,
                emptype: emptype || (emp ? emp.EmploymentType : ''),
                notes, lockerNo, contact, schedule, error
            });
        });

        renderBulkPreview(file.name, errorCount);
    };
    reader.readAsText(file);
}

function renderBulkPreview(fileName, errorCount) {
    document.getElementById('dropZone').classList.add('hidden');
    document.getElementById('csvHint').classList.add('hidden');
    document.getElementById('csvPreview').classList.remove('hidden');
    document.getElementById('csvFileName').textContent = fileName;
    document.getElementById('csvRowCount').textContent = `${bulkParsed.length} row${bulkParsed.length > 1 ? 's' : ''}`;

    const validCount = bulkParsed.filter(r => !r.error).length;

    if (errorCount > 0) {
        const badge = document.getElementById('csvErrorBadge');
        badge.textContent = `${errorCount} error${errorCount > 1 ? 's' : ''}`;
        badge.classList.remove('hidden');
    }
    if (validCount > 0) {
        document.getElementById('csvValidBadge').textContent = `✓ ${validCount} valid`;
        document.getElementById('csvValidBadge').classList.remove('hidden');
        document.getElementById('bulkImportBtn').disabled = false;
    }

    const table = document.getElementById('csvTable');
    table.innerHTML = `
        <thead><tr>
            <th>#</th><th>Biometric No.</th><th>Employee Name</th><th>Phase</th><th>Classification</th><th>Gender</th><th>Locker No.</th><th>Status</th>
        </tr></thead>
        <tbody>${bulkParsed.map((r, i) => `
            <tr class="${r.error ? 'csv-err' : ''}">
                <td>${i + 1}</td>
                <td class="font-mono">${r.id}</td>
                <td>${r.empName || '<span class="text-red-400">—</span>'}</td>
                <td>${r.phase || '<span class="text-zinc-300">—</span>'}</td>
                <td>${r.classif || '<span class="text-zinc-300">—</span>'}</td>
                <td>${r.gender || '<span class="text-zinc-300">—</span>'}</td>
                <td class="font-mono">${r.lockerNo || '<span class="text-zinc-300">—</span>'}</td>
                <td>${r.error
                    ? '<span class="text-[10px] font-semibold text-red-500">' + r.error + '</span>'
                    : '<span class="text-[10px] font-semibold text-emerald-600">✓ Ready</span>'
                }</td>
            </tr>
        `).join('')}</tbody>
    `;
}

function importBulkToBatch() {
    const valid = bulkParsed.filter(r => !r.error && r.emp);
    if (!valid.length) {
        Swal.fire({ icon:'warning', title:'No valid rows', text:'Fix the errors and try again.', confirmButtonColor:'#1c1c1c' });
        return;
    }
    let imported = 0;
    valid.forEach(r => {
        if (!state.batch.find(b => b.BiometricsID === r.id)) {
            state.batch.push({
                ...r.emp,
                phase: r.phase, classif: r.classif,
                emptype: r.emptype || r.emp.EmploymentType,
                gender: r.gender, notes: r.notes,
                lockerNo: r.lockerNo, contact: r.contact, schedule: r.schedule,
            });
            imported++;
        }
    });
    renderBatch();
    closeBulkModal();
    if (imported) updateSteps(3);
    Swal.fire({
        icon: 'success',
        title: `${imported} employee${imported > 1 ? 's' : ''} imported`,
        text: 'Review the batch queue and submit when ready.',
        confirmButtonColor: '#1c1c1c',
        timer: 2500,
        showConfirmButton: false,
    });
}

/* ── Toast ── */
let toastTimer = null;
function toast(msg, type='success') {
    let t = document.getElementById('upload-toast');
    if (!t) { t = document.createElement('div'); t.id = 'upload-toast'; document.body.appendChild(t); }
    const icon = type === 'error' ? '⚠️' : type === 'info' ? 'ℹ️' : '✅';
    t.innerHTML = `${icon} ${msg}`;
    t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-zinc-800 border border-zinc-700 rounded-xl px-5 py-2.5 text-sm text-white z-[200] shadow-2xl flex items-center gap-2 whitespace-nowrap';
    t.style.animation = 'fadeUp .3s ease both';
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.className = 'hidden'; }, 3000);
}

/* ── Load employees from API ── */
function loadEmployeesFromMasterList() {
    const searchInput = document.getElementById('emp-search');
    const spinner = document.getElementById('empSearchSpinner');
    searchInput.disabled = true;
    searchInput.placeholder = 'Loading employees...';
    spinner.classList.remove('hidden');

    fetch('API/get-master-list.php')
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                EMPLOYEES = data;
                toast(`✓ ${data.length} employees loaded`, 'success');
            } else {
                toast('No employees found', 'error');
            }
        })
        .catch(err => {
            console.error('Failed to load employees:', err);
            toast('Failed to load employee data', 'error');
        })
        .finally(() => {
            searchInput.disabled = false;
            searchInput.placeholder = 'Search by name or Biometrics ID...';
            spinner.classList.add('hidden');
        });
}

/* ── Init ── */
disableForm();
loadEmployeesFromMasterList();
</script>
</body>
</html>