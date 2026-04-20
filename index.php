<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ./Auth/login.php");
        exit;
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
                <div class="w-full h-full flex flex-col items-center justify-between rounded-3xl bg-white border-2 border-black/10 shadow-md p-5">
                    <div class="flex flex-col items-center justify-start w-full h-auto gap-5">
                        <div class="flex flex-row items-center justify-center w-full h-auto">
                            <i class="fa-solid fa-shield text-4xl text-blue-500"></i>
                        </div>
                        <hr class="w-full border-2 rounded-full border-black/50" />
                        <div class="flex flex-col items-center justify-start w-full h-auto">
                            <button class="flex items-center justify-center w-full aspect-square rounded-xl hover:bg-blue-500/10 transition-all">
                                <i class="fa-solid fa-gauge text-xl text-blue-500"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="flex-1 h-full">

            </div>
        </div>
    </body>
</html>

<script>
    
</script>