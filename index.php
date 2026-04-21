<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ./Auth/login.php");
        exit;
    }

    $routes = [
        [
            "name" => "Dashboard",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" class="group-hover:scale-110 transition-all"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>',
            "route" => "Pages/Dashboard.php"
        ],
        [
            "name" => "Locker",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" class="group-hover:scale-110 transition-all"><path d="M160-120v-640q0-33 23.5-56.5T240-840h480q33 0 56.5 23.5T800-760v640h-80v-80H240v80h-80Zm80-400h200v-240H240v240Zm280-160h200v-80H520v80Zm0 160h200v-80H520v80ZM400-320h160v-80H400v80ZM240-440v160h480v-160H240Zm0 0v160-160Z"/></svg>',
            "route" => "Pages/Locker.php"
        ],
        [
            "name" => "Locker Assignment",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" class="group-hover:scale-110 transition-all"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80Zm221.5-198.5Q510-807 510-820t-8.5-21.5Q493-850 480-850t-21.5 8.5Q450-833 450-820t8.5 21.5Q467-790 480-790t21.5-8.5Zm77 700Q520-157 520-240t58.5-141.5Q637-440 720-440t141.5 58.5Q920-323 920-240T861.5-98.5Q803-40 720-40T578.5-98.5ZM720-100q54 0 93.5-36t45.5-89q-5 2-9.5 3.5T840-220h-40q-17 0-28.5-11.5T760-260v-20h-80v-40q0-17 11.5-28.5T720-360h20q0-5 1-9.5t3-8.5q-6-1-12-1.5t-12-.5q-58 0-99 41t-41 99h80q33 0 56.5 23.5T740-160v20h-60v34q10 3 19.5 4.5T720-100Z"/></svg>',
            "route" => "Pages/Locker-Assignment.php"
        ],
        [
            "name" => "Reassignment",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" class="group-hover:scale-110 transition-all"><path d="M280-160 80-360l200-200 57 56-104 104h607v80H233l104 104-57 56Zm400-240-57-56 104-104H120v-80h607L623-744l57-56 200 200-200 200Z"/></svg>',
            "route" => "Pages/Reassignment.php"
        ],
        [
            "name" => "Reports",
            "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" class="group-hover:scale-110 transition-all"><path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>',
            "route" => "Pages/Reports.php"
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
        <div class="w-full h-screen flex items-center justify-center bg-zinc-100">
            <!-- Sidebar -->
            <div class="w-25 h-full flex flex-col items-center justify-center p-3">
                <div class="w-full h-full flex flex-col items-center justify-between rounded-3xl bg-white border-2 border-black/10 shadow-md px-3 py-5">
                    <div class="flex flex-col items-center justify-start w-full h-auto gap-5">
                        <div class="flex flex-row items-center justify-center w-full h-auto">
                            <i class="fa-solid fa-shield text-4xl text-blue-500"></i>
                        </div>
                        <hr class="w-full border rounded-full border-black/30" />
                        <div class="flex flex-col items-center justify-start w-full h-auto gap-2">
                            <?php foreach ($routes as $route): ?>
                                <?php
                                    $is_active = ($current_route === $route['route']);
                                ?>
                                <button
                                    onclick="navigateTo('<?php echo $route['route'] ?>', this)"
                                    class="relative flex items-center justify-center w-full aspect-square rounded-xl hover:bg-blue-500/10 transition-all cursor-pointer group <?php echo $is_active ? 'bg-blue-500/10' : '' ?>"
                                    data-route="<?php echo $route['route'] ?>"
                                >
                                    <?php echo $route['icon'] ?>
                                    <span class="absolute left-13 bg-black/70 px-2 py-1 rounded-lg text-sm text-white opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all whitespace-nowrap">
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
                <div class="w-full h-full overflow-hidden">
                    <div class="flex flex-row items-start justify-end w-full h-auto">
                        <!-- User Card -->
                        <div class="flex flex-row items-center justify-end w-full h-auto mb-4">
                            <div class="flex items-center gap-3 bg-white border border-black/10 shadow-sm rounded-2xl px-3 py-2 group cursor-default">
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
                                    <p class="text-sm font-semibold text-zinc-800 tracking-tight">
                                        <?php echo $_SESSION['lrn_master_list']['FirstName'] . ' ' . $_SESSION['lrn_master_list']['LastName'] ?>
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        <p class="text-xs text-zinc-400 font-medium"><?php echo $_SESSION['restriction']['role'] ?></p>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="w-px h-7 bg-zinc-100 mx-1"></div>

                                <!-- Logout button -->
                                <button 
                                    onclick="confirmLogout()"
                                    class="flex items-center justify-center w-7 h-7 rounded-lg hover:bg-red-50 text-zinc-400 hover:text-red-500 transition-all cursor-pointer"
                                    title="Logout"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
                                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="main-content" class="w-full h-full">
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
        // Remove active class from all buttons
        document.querySelectorAll('[data-route]').forEach(btn => {
            btn.classList.remove('bg-blue-500/10');
        });

        // Add active class to clicked button
        clickedBtn.classList.add('bg-blue-500/10');

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