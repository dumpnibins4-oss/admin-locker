<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: ./Auth/login.php");
        exit;
    }

    $routes = [
        [
            "name" => "Dashboard",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="group-hover:scale-110 transition-all"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>',
            "route" => $_SESSION['lrn_master_list']['Department'] === 'Human Resources Department - LRN' ? "Pages/HR-Dashboard.php" : "Pages/Dashboard.php"
        ],
        [
            "name" => $_SESSION['lrn_master_list']['Department'] === 'Human Resources Department - LRN' ? "Employee Upload" : "Locker",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="group-hover:scale-110 transition-all"><path d="M160-120v-640q0-33 23.5-56.5T240-840h480q33 0 56.5 23.5T800-760v640h-80v-80H240v80h-80Zm80-400h200v-240H240v240Zm280-160h200v-80H520v80Zm0 160h200v-80H520v80ZM400-320h160v-80H400v80ZM240-440v160h480v-160H240Zm0 0v160-160Z"/></svg>',
            "route" => $_SESSION['lrn_master_list']['Department'] === 'Human Resources Department - LRN' ? "Pages/HR-Employee-Upload.php" : "Pages/Locker.php"
        ],
        [
            "name" => "Locker Assignment",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="group-hover:scale-110 transition-all"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80Zm221.5-198.5Q510-807 510-820t-8.5-21.5Q493-850 480-850t-21.5 8.5Q450-833 450-820t8.5 21.5Q467-790 480-790t21.5-8.5Zm77 700Q520-157 520-240t58.5-141.5Q637-440 720-440t141.5 58.5Q920-323 920-240T861.5-98.5Q803-40 720-40T578.5-98.5ZM720-100q54 0 93.5-36t45.5-89q-5 2-9.5 3.5T840-220h-40q-17 0-28.5-11.5T760-260v-20h-80v-40q0-17 11.5-28.5T720-360h20q0-5 1-9.5t3-8.5q-6-1-12-1.5t-12-.5q-58 0-99 41t-41 99h80q33 0 56.5 23.5T740-160v20h-60v34q10 3 19.5 4.5T720-100Z"/></svg>',
            "route" => "Pages/Locker-Assignment.php"
        ],
        [
            "name" => "Reassignment",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="group-hover:scale-110 transition-all"><path d="M280-160 80-360l200-200 57 56-104 104h607v80H233l104 104-57 56Zm400-240-57-56 104-104H120v-80h607L623-744l57-56 200 200-200 200Z"/></svg>',
            "route" => "Pages/Reassignment.php"
        ],
        [
            "name" => "Reports",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="group-hover:scale-110 transition-all"><path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
            "route" => "Pages/Reports.php"
        ],
    ];

    $common_routes = [
        [
            "name" => "Settings",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>',
            "route" => "Pages/Settings.php"
        ],
        [
            "name" => "Help",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M513.5-254.5Q528-269 528-290t-14.5-35.5Q499-340 478-340t-35.5 14.5Q428-311 428-290t14.5 35.5Q457-240 478-240t35.5-14.5ZM442-394h74q0-33 7.5-52t42.5-52q26-26 41-49.5t15-56.5q0-56-41-86t-97-30q-57 0-92.5 30T342-618l66 26q5-18 22.5-39t53.5-21q32 0 48 17.5t16 38.5q0 20-12 37.5T506-526q-44 39-54 59t-10 73Zm38 314q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>',
            "route" => "Pages/Help.php"
        ],
    ];

    $valid_routes = array_column($routes, 'route');
    $current_route = $_GET['page'] ?? 'Pages/Dashboard.php';

    if (!in_array($current_route, $valid_routes)) {
        $current_route = 'Pages/Dashboard.php';
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Admin Locker</title>
</head>
    <body>
        <div class="w-full h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white/50 to-yellow-50">
            <!-- Sidebar -->
            <div class="w-25 h-full flex flex-col items-center justify-center p-3">
                <div class="w-full h-full flex flex-col items-center justify-between rounded-3xl bg-zinc-900 backdrop-blur-md border border-black/10 shadow-md px-3 py-5">
                    <!-- Top Nav -->
                    <div class="flex flex-col items-center justify-start w-full h-auto gap-5">
                        <div class="flex flex-row items-center justify-center w-full h-auto">
                            <i class="fa-solid fa-shield text-4xl text-blue-400"></i>
                        </div>
                        <hr class="w-full border rounded-full border-zinc-200" />
                        <div class="flex flex-col items-center justify-start w-full h-auto gap-2">
                            <?php foreach ($routes as $route): ?>
                                <?php
                                    $is_active = ($current_route === $route['route']);
                                    $route_color = ($route['name'] == 'Dashboard') ? 'text-red-500' : 'text-white';
                                ?>
                                <button
                                    onclick="navigateTo('<?php echo $route['route'] ?>', this)"
                                    class="relative flex items-center justify-center w-full aspect-square rounded-xl transition-all cursor-pointer group <?php echo $is_active ? 'bg-yellow-400 text-zinc-800' : 'text-white' ?>"
                                    data-route="<?php echo $route['route'] ?>"
                                >
                                    <!-- Hover Effects Container -->
                                    <div class="hover-fx absolute inset-0 rounded-xl overflow-hidden pointer-events-none <?php echo $is_active ? 'hidden' : '' ?>">
                                        <div class="absolute inset-0 bg-yellow-400/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="absolute top-0 -left-[150%] w-[150%] h-full bg-gradient-to-r from-transparent via-white/40 to-transparent -skew-x-12 group-hover:left-[150%] transition-all duration-700 ease-in-out"></div>
                                    </div>
                                    
                                    <div class="relative z-10 flex items-center justify-center pointer-events-none">
                                        <?php echo $route['icon'] ?>
                                    </div>
                                    
                                    <span class="absolute left-13 bg-black/70 px-2 py-1 rounded-lg text-sm text-white opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all whitespace-nowrap z-20 pointer-events-none">
                                        <?php echo $route['name'] ?>
                                    </span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Bottom Nav -->
                    <div class="flex flex-col items-center justify-end w-full h-auto gap-5">
                        <hr class="w-full border rounded-full border-zinc-200" />
                        <div class="flex flex-col items-center justify-start w-full h-auto gap-2">
                            <?php foreach ($common_routes as $route): ?>
                                <?php
                                    $is_active = ($current_route === $route['route']);
                                    $route_color = ($route['name'] == 'Dashboard') ? 'text-red-500' : 'text-white';
                                ?>
                                <button
                                    onclick="navigateTo('<?php echo $route['route'] ?>', this)"
                                    class="relative flex items-center justify-center w-full aspect-square rounded-xl transition-all cursor-pointer group <?php echo $is_active ? 'bg-yellow-400 text-zinc-800' : $route_color ?>"
                                    data-route="<?php echo $route['route'] ?>"
                                >
                                    <!-- Hover Effects Container -->
                                    <div class="hover-fx absolute inset-0 rounded-xl overflow-hidden pointer-events-none <?php echo $is_active ? 'hidden' : '' ?>">
                                        <div class="absolute inset-0 bg-yellow-400/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="absolute top-0 -left-[150%] w-[150%] h-full bg-gradient-to-r from-transparent via-white/40 to-transparent -skew-x-12 group-hover:left-[150%] transition-all duration-700 ease-in-out"></div>
                                    </div>
                                    
                                    <div class="relative z-10 flex items-center justify-center pointer-events-none">
                                        <?php echo $route['icon'] ?>
                                    </div>
                                    
                                    <span class="absolute left-13 bg-black/70 px-2 py-1 rounded-lg text-sm text-white opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all whitespace-nowrap z-20 pointer-events-none">
                                        <?php echo $route['name'] ?>
                                    </span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="flex-1 h-full p-3">
                <div class="w-full h-full flex flex-col">
                    <div class="flex flex-row items-start justify-end w-full h-auto flex-shrink-0">
                        <!-- Notifications -->
                        <!-- User Card -->
                        <div class="flex flex-row items-center justify-end w-full h-auto mb-4 gap-2">
                            <button class="flex items-center justify-center w-13 aspect-square bg-zinc-800 hover:bg-zinc-700 hover:scale-110 hover:shadow-md shadow-black/20 rounded-full transition-all text-white cursor-pointer">
                                <i class="fa-regular fa-bell text-lg"></i>
                            </button>
                            <div class="flex items-center gap-3 bg-zinc-800 border border-black/10 shadow-sm rounded-2xl px-3 py-2 group cursor-default">
                                <!-- Avatar with status dot -->
                                <div class="relative">
                                    <div class="w-9 h-9 rounded-xl overflow-hidden ring-2 ring-blue-500/20">
                                        <img 
                                            src="http://10.2.0.8/lrnph/emp_photos/<?php echo $_SESSION['lrn_master_list']['EmployeeID'] ?>.jpg" 
                                            alt="avatar"
                                            class="w-full h-full object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <div class="w-full h-full bg-blue-500/10 items-center justify-center text-blue-500 font-bold text-sm hidden">
                                            <?php echo strtoupper(substr($_SESSION['lrn_master_list']['FirstName'], 0, 1) . substr($_SESSION['lrn_master_list']['LastName'], 0, 1)) ?>
                                        </div>
                                    </div>
                                    <!-- Online dot -->
                                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 border-2 border-white rounded-full"></span>
                                </div>

                                <!-- Name & Role -->
                                <div class="flex flex-col leading-tight">
                                    <p class="text-sm font-semibold text-white tracking-tight">
                                        <?php echo $_SESSION['lrn_master_list']['FirstName'] . ' ' . $_SESSION['lrn_master_list']['LastName'] ?>
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        <p class="text-xs text-white/70 font-medium"><?php echo $_SESSION['restriction']['role'] ?></p>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="w-px h-7 bg-zinc-100 mx-1"></div>

                                <!-- Logout button -->
                                <button 
                                    onclick="confirmLogout()"
                                    class="flex items-center justify-center w-7 h-7 rounded-lg hover:bg-red-500 text-white/70 hover:text-white transition-all cursor-pointer"
                                    title="Logout"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
                                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="main-content" class="w-full flex-1 overflow-y-auto custom-scrollbar">
                        <!-- Content loads here -->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'Auth/logout.php';
            }
        })
    }

    async function navigateTo(route, clickedBtn) {
        // Remove active class from all buttons and restore default colors
        document.querySelectorAll('[data-route]').forEach(btn => {
            btn.classList.remove('bg-yellow-400', 'text-zinc-800');
            const fx = btn.querySelector('.hover-fx');
            if (fx) fx.classList.remove('hidden');
            
            const name = btn.querySelector('span').textContent.trim();
            if (name === 'Dashboard') {
                btn.classList.add('text-blue-500');
            } else {
                btn.classList.add('text-white');
            }
        });

        // Add active class to clicked button
        clickedBtn.classList.remove('text-white', 'text-red-500');
        clickedBtn.classList.add('bg-yellow-400', 'text-zinc-800');
        const clickedFx = clickedBtn.querySelector('.hover-fx');
        if (clickedFx) clickedFx.classList.add('hidden');

        // Show loading state
        document.getElementById('main-content').innerHTML = `
            <div class="w-full h-full flex items-center justify-center">
                <div class="flex flex-col items-center gap-3">
                    <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500"></i>
                    <p class="text-sm text-zinc-400">Loading...</p>
                </div>
            </div>
        `;

        // Fetch page content
        try {
            const response = await fetch(route);
            const html = await response.text();

            // Parse and extract <body> content only
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const bodyContent = doc.body.innerHTML;

            document.getElementById('main-content').innerHTML = bodyContent;

            // Re-run any inline scripts from the loaded page
            document.getElementById('main-content').querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                newScript.textContent = oldScript.textContent;
                document.body.appendChild(newScript);
                oldScript.remove();
            });

        } catch (error) {
            document.getElementById('main-content').innerHTML = `
                <div class="w-full h-full flex items-center justify-center">
                    <div class="flex flex-col items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-3xl text-red-500"></i>
                        <p class="text-sm text-zinc-400">Failed to load page.</p>
                    </div>
                </div>
            `;
        }
    }

    // Load default page on start
    document.addEventListener('DOMContentLoaded', () => {
        const defaultBtn = document.querySelector('[data-route]');
        if (defaultBtn) navigateTo(defaultBtn.dataset.route, defaultBtn);
    });
</script>