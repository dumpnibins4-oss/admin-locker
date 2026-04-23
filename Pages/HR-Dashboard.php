<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
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
    <title>Admin Locker | HR Dashboard</title>
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .serif { font-family: 'DM Serif Display', serif; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fu  { animation: fadeUp .4s ease both; }
        .fu1 { animation-delay: .04s; }
        .fu2 { animation-delay: .08s; }
        .fu3 { animation-delay: .12s; }
        .fu4 { animation-delay: .16s; }
        .fu5 { animation-delay: .20s; }
        .fu6 { animation-delay: .24s; }
        .fu7 { animation-delay: .28s; }
        .fu8 { animation-delay: .32s; }
        .fu9 { animation-delay: .36s; }
        .card { transition: transform .2s, box-shadow .2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(0,0,0,.08); }
        .trow { transition: background .15s; border-radius: 14px; }
        .trow:hover { background: rgba(250,204,21,.12); }
    </style>
</head>
<body class="bg-zinc-100 min-h-screen overflow-y-auto">
<?php
    /* ── MOCK DATA ── */
    $mock = [
        'total_submissions'   => 24,
        'pending'             => 6,
        'approved'            => 15,
        'rejected'            => 3,

        // For the submission breakdown bar
        'this_month'          => 10,
        'last_month'          => 14,

        'recent_submissions' => [
            ['employee_name' => 'Maria Santos',    'emp_id' => 'EMP-0049', 'locker_number' => 'A-12', 'phase' => 'Phase 2', 'date_submitted' => 'Apr 21', 'status' => 'Pending'],
            ['employee_name' => 'Juan dela Cruz',  'emp_id' => 'EMP-0021', 'locker_number' => 'B-07', 'phase' => 'Phase 1', 'date_submitted' => 'Apr 21', 'status' => 'Approved'],
            ['employee_name' => 'Ana Reyes',       'emp_id' => 'EMP-0077', 'locker_number' => 'C-03', 'phase' => 'Phase 3', 'date_submitted' => 'Apr 20', 'status' => 'Rejected'],
            ['employee_name' => 'Pedro Gomez',     'emp_id' => 'EMP-0033', 'locker_number' => 'A-19', 'phase' => 'Phase 1', 'date_submitted' => 'Apr 20', 'status' => 'Approved'],
            ['employee_name' => 'Liza Mangahas',   'emp_id' => 'EMP-0055', 'locker_number' => 'D-11', 'phase' => 'Phase 4', 'date_submitted' => 'Apr 19', 'status' => 'Pending'],
        ],

        'rejected_submissions' => [
            ['employee_name' => 'Ana Reyes',      'emp_id' => 'EMP-0077', 'locker_number' => 'C-03', 'reason' => 'Locker already occupied',      'date_rejected' => 'Apr 20'],
            ['employee_name' => 'Carlo Bautista', 'emp_id' => 'EMP-0061', 'locker_number' => 'B-14', 'reason' => 'Wrong phase assignment',        'date_rejected' => 'Apr 18'],
            ['employee_name' => 'Donna Flores',   'emp_id' => 'EMP-0082', 'locker_number' => 'A-05', 'reason' => 'Employee type mismatch',        'date_rejected' => 'Apr 15'],
        ],
    ];

    date_default_timezone_set('Asia/Manila');
    $hour     = (int) date('H');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $today    = date('l, F j, Y');
    $firstName = $_SESSION['lrn_master_list']['FirstName'] ?? 'HR';

    $total    = $mock['total_submissions'];
    $pending  = $mock['pending'];
    $approved = $mock['approved'];
    $rejected = $mock['rejected'];

    // Approval rate
    $reviewed   = $approved + $rejected;
    $approvePct = $reviewed > 0 ? round($approved / $reviewed * 100) : 0;
    $rejectPct  = 100 - $approvePct;

    // Monthly bar
    $monthMax    = max($mock['this_month'], $mock['last_month'], 1);
    $thisPct     = round($mock['this_month'] / $monthMax * 100);
    $lastPct     = round($mock['last_month'] / $monthMax * 100);

    // Donut arc (approval rate)
    $circ    = round(2 * M_PI * 36, 2);
    $arcFill = round($approvePct / 100 * $circ, 2);
    $arcGap  = round($circ - $arcFill, 2);

    // Status badge helper
    function statusBadge($status) {
        return match($status) {
            'Approved' => '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-600"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>Approved</span>',
            'Rejected' => '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-500"><span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>Rejected</span>',
            default    => '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-orange-50 text-orange-500"><span class="w-1.5 h-1.5 rounded-full bg-orange-400 inline-block"></span>Pending</span>',
        };
    }
?>

<div class="p-3 pl-0 w-full mx-auto">

    <!-- HEADER -->
    <div class="flex items-start justify-between mb-6 fu fu1">
        <div>
            <p class="text-xs text-zinc-400 font-medium tracking-wide mb-1"><?= $today ?></p>
            <h1 class="serif text-[2.4rem] leading-tight text-zinc-800"><?= $greeting ?>, <?= $firstName ?>.</h1>
            <p class="text-sm text-zinc-400 mt-1">Here's a summary of your locker submissions.</p>
        </div>
        <!-- Quick strip -->
        <div class="flex items-center gap-7 pt-2">
            <div class="text-right">
                <p class="serif text-[2.6rem] leading-none text-zinc-800"><?= $total ?></p>
                <p class="text-[10px] text-zinc-400 font-semibold tracking-widest mt-0.5">TOTAL</p>
            </div>
            <div class="w-px h-10 bg-zinc-300"></div>
            <div class="text-right">
                <p class="serif text-[2.6rem] leading-none text-zinc-800"><?= $pending ?></p>
                <p class="text-[10px] text-zinc-400 font-semibold tracking-widest mt-0.5">PENDING</p>
            </div>
            <div class="w-px h-10 bg-zinc-300"></div>
            <div class="text-right">
                <p class="serif text-[2.6rem] leading-none text-zinc-800"><?= $approved ?></p>
                <p class="text-[10px] text-zinc-400 font-semibold tracking-widest mt-0.5">APPROVED</p>
            </div>
        </div>
    </div>

    <!-- ROW 1: 4 stat cards -->
    <div class="grid grid-cols-4 gap-3.5 mb-3.5">

        <!-- Total Submissions -->
        <div class="bg-zinc-800 rounded-3xl p-5 flex flex-col justify-between min-h-[140px] card fu fu2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">My Submissions</span>
                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center">
                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                </div>
            </div>
            <div>
                <p class="serif text-[3rem] leading-none text-white"><?= $total ?></p>
                <p class="text-xs text-white/35 mt-1.5">All time submissions</p>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-yellow-400 rounded-3xl p-5 flex flex-col justify-between min-h-[140px] card fu fu3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-yellow-900/50 uppercase tracking-widest">Pending</span>
                <div class="w-8 h-8 rounded-xl bg-black/10 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-zinc-800 text-xs"></i>
                </div>
            </div>
            <div>
                <p class="serif text-[3rem] leading-none text-zinc-800"><?= $pending ?></p>
                <p class="text-xs text-zinc-800/45 mt-1.5">Awaiting Admin review</p>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-3xl p-5 flex flex-col justify-between min-h-[140px] border border-zinc-200 card fu fu4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Approved</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                </div>
            </div>
            <div>
                <p class="serif text-[3rem] leading-none text-zinc-800"><?= $approved ?></p>
                <p class="text-xs text-zinc-400 mt-1.5">Confirmed by Admin</p>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-3xl p-5 flex flex-col justify-between min-h-[140px] border border-zinc-200 card fu fu5">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Rejected</span>
                <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-400 text-xs"></i>
                </div>
            </div>
            <div>
                <p class="serif text-[3rem] leading-none text-zinc-800"><?= $rejected ?></p>
                <p class="text-xs text-zinc-400 mt-1.5">Sent back by Admin</p>
            </div>
        </div>
    </div>

    <!-- ROW 2: Approval rate donut | Monthly bar | Quick actions -->
    <div class="grid grid-cols-3 gap-3.5 mb-3.5">

        <!-- Approval rate donut -->
        <div class="bg-zinc-800 rounded-3xl p-5 flex flex-col card fu fu6">
            <p class="text-sm font-semibold text-white mb-0.5">Approval Rate</p>
            <p class="text-xs text-white/35 mb-4">Based on reviewed submissions</p>
            <div class="flex items-center justify-center gap-7 flex-1">
                <div class="relative w-[120px] h-[120px] flex-shrink-0">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                        <circle cx="50" cy="50" r="36" fill="none" stroke="#3f3f46" stroke-width="14"/>
                        <circle cx="50" cy="50" r="36" fill="none"
                            stroke="#4ade80" stroke-width="14"
                            stroke-dasharray="<?= $arcFill ?> <?= $arcGap ?>"
                            stroke-linecap="round"
                            style="transform-origin:center;transform:rotate(-90deg)"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xl font-bold text-white leading-none"><?= $approvePct ?>%</span>
                        <span class="text-[11px] text-white/35 mt-0.5">approved</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-[2rem] font-bold text-emerald-400 leading-none"><?= $approved ?></p>
                        <p class="text-xs text-white/35 mt-0.5">Approved</p>
                    </div>
                    <div class="h-px w-full bg-white/10"></div>
                    <div>
                        <p class="text-[2rem] font-bold text-red-400/70 leading-none"><?= $rejected ?></p>
                        <p class="text-xs text-white/25 mt-0.5">Rejected</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly submission bar -->
        <div class="bg-white rounded-3xl p-5 border border-zinc-200 card fu fu7">
            <p class="text-sm font-semibold text-zinc-800 mb-0.5">Monthly Submissions</p>
            <p class="text-xs text-zinc-400 mb-5">This month vs last month</p>
            <div class="space-y-5">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-zinc-700">This Month</span>
                        <span class="font-bold text-zinc-800"><?= $mock['this_month'] ?></span>
                    </div>
                    <div class="h-2.5 rounded-full bg-zinc-100 overflow-hidden">
                        <div class="h-full rounded-full bg-yellow-400" style="width:<?= $thisPct ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-zinc-700">Last Month</span>
                        <span class="font-bold text-zinc-800"><?= $mock['last_month'] ?></span>
                    </div>
                    <div class="h-2.5 rounded-full bg-zinc-100 overflow-hidden">
                        <div class="h-full rounded-full bg-zinc-300" style="width:<?= $lastPct ?>%"></div>
                    </div>
                </div>
            </div>
            <div class="border-t border-zinc-100 mt-5 pt-4 flex items-center justify-between">
                <span class="text-xs text-zinc-400">Total All Time</span>
                <span class="text-lg font-bold text-zinc-800"><?= $total ?></span>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="bg-zinc-50 rounded-3xl p-5 border border-zinc-200 card fu fu8">
            <p class="text-sm font-semibold text-zinc-800 mb-0.5">Quick Actions</p>
            <p class="text-xs text-zinc-400 mb-4">Jump to common tasks</p>
            <div class="flex flex-col gap-2">
                <button onclick="navigateTo('Pages/HR-Employee-Upload.php', this)"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl bg-zinc-800 hover:bg-zinc-700 transition-all text-left group">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-upload text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">Upload Employee</p>
                        <p class="text-[10px] text-white/40">Add new hire data</p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-white/30 group-hover:text-white/70 text-xs ml-auto transition-colors"></i>
                </button>

                <button onclick="navigateTo('Pages/HR-Locker-Plotting.php', this)"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl bg-yellow-400 hover:bg-yellow-300 transition-all text-left group">
                    <div class="w-8 h-8 rounded-xl bg-black/10 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-lock text-zinc-800 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-800">Plot a Locker</p>
                        <p class="text-[10px] text-zinc-800/50">Assign & submit for review</p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-zinc-800/30 group-hover:text-zinc-800/70 text-xs ml-auto transition-colors"></i>
                </button>

                <button onclick="navigateTo('Pages/HR-Status-Update.php', this)"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl bg-white border border-zinc-200 hover:bg-zinc-50 transition-all text-left group">
                    <div class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user-pen text-zinc-500 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-800">Update Status</p>
                        <p class="text-[10px] text-zinc-400">Active / Inactive employees</p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-zinc-300 group-hover:text-zinc-500 text-xs ml-auto transition-colors"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ROW 3: Recent submissions + Rejected (needs attention) -->
    <div class="grid grid-cols-2 gap-3.5">

        <!-- Recent Submissions -->
        <div class="bg-white rounded-3xl p-5 border border-zinc-200 fu fu9">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold text-zinc-800">My Recent Submissions</p>
                    <p class="text-xs text-zinc-400">Latest locker assignments you submitted</p>
                </div>
                <button class="text-[10px] font-bold text-yellow-600 hover:text-yellow-700 transition-colors tracking-widest">VIEW ALL →</button>
            </div>
            <div class="space-y-1">
                <?php foreach ($mock['recent_submissions'] as $row): ?>
                <div class="trow flex items-center gap-3 px-3 py-2.5 cursor-pointer">
                    <div class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user text-zinc-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-800 truncate"><?= $row['employee_name'] ?></p>
                        <p class="text-xs text-zinc-400">Locker <?= $row['locker_number'] ?> &middot; <?= $row['phase'] ?> &middot; <?= $row['emp_id'] ?></p>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <?= statusBadge($row['status']) ?>
                        <span class="text-[10px] text-zinc-400"><?= $row['date_submitted'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Rejected — Needs Attention -->
        <div class="bg-white rounded-3xl p-5 border border-zinc-200 fu fu9">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold text-zinc-800">Needs Attention</p>
                    <p class="text-xs text-zinc-400">Rejected submissions — please review and resubmit</p>
                </div>
                <?php if (count($mock['rejected_submissions']) > 0): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-500">
                    <?= count($mock['rejected_submissions']) ?> rejected
                </span>
                <?php endif; ?>
            </div>

            <?php if (empty($mock['rejected_submissions'])): ?>
            <div class="text-center py-8">
                <i class="fa-solid fa-circle-check text-3xl text-emerald-400 mb-2 block"></i>
                <p class="text-sm text-zinc-400">No rejected submissions. You're all good!</p>
            </div>
            <?php else: ?>
            <div class="space-y-1">
                <?php foreach ($mock['rejected_submissions'] as $row): ?>
                <div class="trow flex items-center gap-3 px-3 py-2.5 cursor-pointer">
                    <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-red-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-800 truncate"><?= $row['employee_name'] ?> <span class="text-zinc-400 font-normal"><?= $row['emp_id'] ?></span></p>
                        <p class="text-xs text-zinc-400">Locker <?= $row['locker_number'] ?> &middot; <span class="text-red-400"><?= $row['reason'] ?></span></p>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <button class="text-[10px] font-bold text-yellow-600 hover:text-yellow-700 transition-colors whitespace-nowrap">Resubmit →</button>
                        <span class="text-[10px] text-zinc-400"><?= $row['date_rejected'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>