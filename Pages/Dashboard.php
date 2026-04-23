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
    <title>Admin Locker | Dashboard</title>
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
        .donut-arc { transform-origin: center; transform: rotate(-90deg); }
        .trow { transition: background .15s; border-radius: 14px; }
        .trow:hover { background: rgba(250,204,21,.12); }
    </style>
</head>
    <body class="bg-zinc-100 min-h-screen overflow-y-auto">
        <?php
            $mock = [
                'total_lockers'          => 120,
                'occupied_lockers'       => 87,
                'available_lockers'      => 33,
                'pending_reviews'        => 6,
                'active_employees'       => 82,
                'inactive_employees'     => 18,
                'regular_employees'      => 65,
                'probationary_employees' => 35,
                'production'             => 70,
                'non_production'         => 30,
                'pending_submissions'    => [
                    ['employee_name' => 'Maria Santos',    'locker_number' => 'A-12', 'phase' => 'Phase 2', 'date_submitted' => 'Apr 21'],
                    ['employee_name' => 'Juan dela Cruz',  'locker_number' => 'B-07', 'phase' => 'Phase 1', 'date_submitted' => 'Apr 21'],
                    ['employee_name' => 'Ana Reyes',       'locker_number' => 'C-03', 'phase' => 'Phase 3', 'date_submitted' => 'Apr 20'],
                    ['employee_name' => 'Pedro Gomez',     'locker_number' => 'A-19', 'phase' => 'Phase 1', 'date_submitted' => 'Apr 20'],
                    ['employee_name' => 'Liza Mangahas',   'locker_number' => 'D-11', 'phase' => 'Phase 4', 'date_submitted' => 'Apr 19'],
                ],
                'recent_reassignments'   => [
                    ['employee_name' => 'Carlo Bautista',  'from_locker' => 'A-04', 'to_locker' => 'B-14', 'date' => 'Apr 21'],
                    ['employee_name' => 'Jenny Pascual',   'from_locker' => 'C-08', 'to_locker' => 'C-15', 'date' => 'Apr 20'],
                    ['employee_name' => 'Mark Villanueva', 'from_locker' => 'B-02', 'to_locker' => 'D-06', 'date' => 'Apr 19'],
                    ['employee_name' => 'Rhea Tolentino',  'from_locker' => 'A-17', 'to_locker' => 'A-22', 'date' => 'Apr 18'],
                    ['employee_name' => 'Roy Macaraeg',    'from_locker' => 'D-01', 'to_locker' => 'B-09', 'date' => 'Apr 17'],
                ],
            ];

            date_default_timezone_set('Asia/Manila');
            $hour      = (int) date('H');
            $greeting  = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
            $today     = date('l, F j, Y');
            $firstName = $_SESSION['lrn_master_list']['FirstName'];

            $total    = $mock['total_lockers'];
            $occupied = $mock['occupied_lockers'];
            $avail    = $mock['available_lockers'];
            $occPct   = $total > 0 ? round($occupied / $total * 100) : 0;
            $avlPct   = 100 - $occPct;

            $active   = $mock['active_employees'];
            $inactive = $mock['inactive_employees'];
            $empTotal = $active + $inactive;
            $actPct   = $empTotal > 0 ? round($active / $empTotal * 100) : 0;

            $regular  = $mock['regular_employees'];
            $probat   = $mock['probationary_employees'];
            $eMax     = max($regular, $probat, 1);
            $regPct   = round($regular / $eMax * 100);
            $proPct   = round($probat  / $eMax * 100);

            $circ    = round(2 * M_PI * 36, 2);
            $arcFill = round($actPct / 100 * $circ, 2);
            $arcGap  = round($circ - $arcFill, 2);
        ?>

        <div class="p-3 pl-0 w-full mx-auto">

            <!-- HEADER -->
            <div class="flex items-start justify-between mb-6 fu fu1">
                <div>
                    <p class="text-xs text-zinc-400 font-medium tracking-wide mb-1"><?= $today ?></p>
                    <h1 class="serif text-[2.4rem] leading-tight text-zinc-800"><?= $greeting ?>, <?= $firstName ?>.</h1>
                    <p class="text-sm text-zinc-400 mt-1">Here's your locker system overview for today.</p>
                </div>
                <div class="flex items-center gap-7 pt-2">
                    <div class="text-right">
                        <p class="serif text-[2.6rem] leading-none text-zinc-800"><?= $total ?></p>
                        <p class="text-[10px] text-zinc-400 font-semibold tracking-widest mt-0.5">TOTAL</p>
                    </div>
                    <div class="w-px h-10 bg-zinc-300"></div>
                    <div class="text-right">
                        <p class="serif text-[2.6rem] leading-none text-zinc-800"><?= $occupied ?></p>
                        <p class="text-[10px] text-zinc-400 font-semibold tracking-widest mt-0.5">OCCUPIED</p>
                    </div>
                    <div class="w-px h-10 bg-zinc-300"></div>
                    <div class="text-right">
                        <p class="serif text-[2.6rem] leading-none text-zinc-800"><?= $avail ?></p>
                        <p class="text-[10px] text-zinc-400 font-semibold tracking-widest mt-0.5">AVAILABLE</p>
                    </div>
                </div>
            </div>

            <!-- ROW 1: 4 stat cards -->
            <div class="grid grid-cols-4 gap-3.5 mb-3.5">
                <div class="bg-zinc-800 rounded-3xl p-5 flex flex-col justify-between min-h-[140px] card fu fu2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Total Lockers</span>
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center">
                            <i class="fa-solid fa-lock text-white text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="serif text-[3rem] leading-none text-white"><?= $total ?></p>
                        <p class="text-xs text-white/35 mt-1.5">All lockers in system</p>
                    </div>
                </div>

                <div class="bg-yellow-400 rounded-3xl p-5 flex flex-col justify-between min-h-[140px] card fu fu3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-yellow-900/50 uppercase tracking-widest">Occupied</span>
                        <div class="w-8 h-8 rounded-xl bg-black/10 flex items-center justify-center">
                            <i class="fa-solid fa-user text-zinc-800 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="serif text-[3rem] leading-none text-zinc-800"><?= $occupied ?></p>
                        <p class="text-xs text-zinc-800/45 mt-1.5">Active assignments</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 flex flex-col justify-between min-h-[140px] border border-zinc-200 card fu fu4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Available</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="serif text-[3rem] leading-none text-zinc-800"><?= $avail ?></p>
                        <p class="text-xs text-zinc-400 mt-1.5">Ready to assign</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 flex flex-col justify-between min-h-[140px] border border-zinc-200 card fu fu5">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Pending Review</span>
                        <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center">
                            <i class="fa-solid fa-clock text-orange-400 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="serif text-[3rem] leading-none text-zinc-800"><?= $mock['pending_reviews'] ?></p>
                        <p class="text-xs text-zinc-400 mt-1.5">Awaiting your review</p>
                    </div>
                </div>
            </div>

            <!-- ROW 2 -->
            <div class="grid grid-cols-3 gap-3.5 mb-3.5">

                <!-- Occupancy bar + Classification -->
                <div class="bg-white rounded-3xl p-5 border border-zinc-200 card fu fu6">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-zinc-800">Locker Occupancy</p>
                        <span class="text-xs font-bold text-zinc-800"><?= $occPct ?>%</span>
                    </div>
                    <div class="flex h-9 rounded-full overflow-hidden bg-zinc-100 mb-3">
                        <div class="flex items-center justify-center text-xs font-semibold text-zinc-800 bg-yellow-400 rounded-full"
                            style="width:<?= $occPct ?>%">
                            <?= $occPct > 12 ? $occPct.'%' : '' ?>
                        </div>
                        <div class="flex items-center justify-center text-xs font-semibold text-white bg-zinc-800 rounded-full"
                            style="width:<?= $avlPct ?>%">
                            <?= $avlPct > 12 ? $avlPct.'%' : '' ?>
                        </div>
                    </div>
                    <div class="flex gap-4 text-xs text-zinc-400 mb-4">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span>Occupied</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-zinc-800 inline-block"></span>Available</span>
                    </div>
                    <div class="border-t border-zinc-100 my-3"></div>
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3">By Classification</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-700">Production</span>
                            <span class="font-bold text-zinc-800"><?= $mock['production'] ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-700">Non-Production</span>
                            <span class="font-bold text-zinc-800"><?= $mock['non_production'] ?></span>
                        </div>
                    </div>
                </div>

                <!-- Employee Donut -->
                <div class="bg-zinc-800 rounded-3xl p-5 flex flex-col card fu fu7">
                    <p class="text-sm font-semibold text-white mb-0.5">Employees</p>
                    <p class="text-xs text-white/35 mb-4">Active vs Inactive</p>
                    <div class="flex items-center justify-center gap-7 flex-1">
                        <div class="relative w-[120px] h-[120px] flex-shrink-0">
                            <svg viewBox="0 0 100 100" class="w-full h-full">
                                <circle cx="50" cy="50" r="36" fill="none" stroke="#3f3f46" stroke-width="14"/>
                                <circle cx="50" cy="50" r="36" fill="none"
                                    stroke="#facc15" stroke-width="14"
                                    stroke-dasharray="<?= $arcFill ?> <?= $arcGap ?>"
                                    stroke-linecap="round"
                                    class="donut-arc"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xl font-bold text-white leading-none"><?= $actPct ?>%</span>
                                <span class="text-[11px] text-white/35 mt-0.5">active</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[2rem] font-bold text-white leading-none"><?= $active ?></p>
                                <p class="text-xs text-white/35 mt-0.5">Active</p>
                            </div>
                            <div class="h-px w-full bg-white/10"></div>
                            <div>
                                <p class="text-[2rem] font-bold text-white/40 leading-none"><?= $inactive ?></p>
                                <p class="text-xs text-white/25 mt-0.5">Inactive</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment type -->
                <div class="bg-zinc-50 rounded-3xl p-5 border border-zinc-200 card fu fu8">
                    <p class="text-sm font-semibold text-zinc-800 mb-0.5">Employment Type</p>
                    <p class="text-xs text-zinc-400 mb-5">Separated locker pools</p>
                    <div class="space-y-5">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-zinc-700">Regular</span>
                                <span class="font-bold text-zinc-800"><?= $regular ?></span>
                            </div>
                            <div class="h-2.5 rounded-full bg-zinc-200 overflow-hidden">
                                <div class="h-full rounded-full bg-zinc-800" style="width:<?= $regPct ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-zinc-700">Probationary</span>
                                <span class="font-bold text-zinc-800"><?= $probat ?></span>
                            </div>
                            <div class="h-2.5 rounded-full bg-zinc-200 overflow-hidden">
                                <div class="h-full rounded-full bg-yellow-400" style="width:<?= $proPct ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-zinc-200 mt-5 pt-4 flex items-center justify-between">
                        <span class="text-xs text-zinc-400">Total Employees</span>
                        <span class="text-lg font-bold text-zinc-800"><?= $empTotal ?></span>
                    </div>
                </div>
            </div>

            <!-- ROW 3: Tables -->
            <div class="grid grid-cols-2 gap-3.5">

                <!-- Pending Submissions -->
                <div class="bg-white rounded-3xl p-5 border border-zinc-200 fu fu9">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-zinc-800">Pending HR Submissions</p>
                            <p class="text-xs text-zinc-400">Locker plots awaiting your review</p>
                        </div>
                        <button class="text-[10px] font-bold text-yellow-600 hover:text-yellow-700 transition-colors tracking-widest">VIEW ALL →</button>
                    </div>
                    <div class="space-y-1">
                        <?php foreach ($mock['pending_submissions'] as $row): ?>
                        <div class="trow flex items-center gap-3 px-3 py-2.5 cursor-pointer">
                            <div class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-user text-zinc-400 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-800 truncate"><?= $row['employee_name'] ?></p>
                                <p class="text-xs text-zinc-400">Locker <?= $row['locker_number'] ?> &middot; <?= $row['phase'] ?></p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-orange-50 text-orange-500">
                                <?= $row['date_submitted'] ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recent Reassignments -->
                <div class="bg-white rounded-3xl p-5 border border-zinc-200 fu fu9">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-zinc-800">Recent Reassignments</p>
                            <p class="text-xs text-zinc-400">Latest locker transfers</p>
                        </div>
                        <button class="text-[10px] font-bold text-yellow-600 hover:text-yellow-700 transition-colors tracking-widest">VIEW ALL →</button>
                    </div>
                    <div class="space-y-1">
                        <?php foreach ($mock['recent_reassignments'] as $row): ?>
                        <div class="trow flex items-center gap-3 px-3 py-2.5 cursor-pointer">
                            <div class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-right-left text-zinc-400 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-800 truncate"><?= $row['employee_name'] ?></p>
                                <p class="text-xs text-zinc-400">
                                    <span class="line-through"><?= $row['from_locker'] ?></span>
                                    &rarr; <span class="font-semibold text-zinc-700"><?= $row['to_locker'] ?></span>
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-zinc-100 text-zinc-500">
                                <?= $row['date'] ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>