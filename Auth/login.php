<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Admin Locker | Login</title>
</head>
    <body>
        <div class="w-screen h-screen flex items-center justify-center bg-[#111111]">
            <h1 class="text-4xl font-bold text-pink-500 fixed top-5 left-5">LA ROSE NOIRE</h1>
            <div class="w-[450px] h-[500px] rounded-4xl bg-[#222222] shadow-md border border-[#333333] px-10 py-8 flex flex-col items-center justify-around">
                <div class="w-full h-auto flex flex-col items-center justify-center">
                    <div class="w-14 h-14 rounded-full bg-zinc-600 flex items-center justify-center">
                        <i class="fa-solid fa-lock text-xl text-yellow-400"></i>
                    </div>
                </div>
                <div class="w-full h-auto flex flex-col items-center justify-start">
                    <h2 class="text-2xl font-medium text-white">Admin Locker</h2>
                    <p class="text-sm text-gray-400 font-medium">Please login to your account to continue</p>
                </div>
                <form id="login-form" method="POST" class="w-full h-auto flex flex-col items-start justify-start gap-5">
                    <div class="w-full h-auto flex flex-col items-start justify-start gap-1">
                        <label for="username" class="text-sm text-white/70 font-medium">Username</label>
                        <input type="text" id="username" name="username" class="w-full h-12 rounded-xl border-2 border-[#333333] p-2 text-white bg-[#333333] focus:outline-none focus:border-blue-500 transition-all" placeholder="Enter your username">
                    </div>
                    <div class="w-full h-auto flex flex-col items-start justify-start gap-1">
                        <label for="password" class="text-sm text-white/70 font-medium">Password</label>
                        <div class="flex flex-row items-center justify-between w-full h-12 border-2 border-[#333333] p-2 bg-[#333333] rounded-xl focus-within:border-blue-500 transition-all">
                            <input type="password" id="password" name="password" class="w-full h-12 text-white focus:outline-none" placeholder="Enter your password">
                            <button type="button" onclick="togglePassword()" class="flex items-center justify-center h-12 w-12 cursor-pointer group">
                                <i id="toggleIcon" class="fa-regular fa-eye text-white/70 group-hover:scale-105 group-hover:text-white transition-all"></i>
                            </button>
                        </div>
                    </div>
                    <button id="login-btn" type="submit" class="w-full h-12 rounded-xl bg-yellow-600 text-white text-lg font-semibold cursor-pointer hover:bg-yellow-700 hover:scale-105 transition-all">Login</button>
                </form>
                <hr class="w-full h-auto border border-zinc-600 rounded-full" />
                <p class="text-sm text-gray-400 font-medium">Don't have an account? Contact your administrator.</p>
            </div>
        </div>
    </body>
</html>

<script>
    function togglePassword() {

        const input = document.getElementById("password")
        const icon = document.getElementById("toggleIcon")

        if (input.type === "password") {
            input.type = "text"
            icon.classList.replace("fa-eye", "fa-eye-slash")
        } else {
            input.type = 'password'
            icon.classList.replace('fa-eye-slash', 'fa-eye')
        }
    }

    document.getElementById('login-form').addEventListener("submit", async function(e) {
        e.preventDefault()

        const btn = document.getElementById("login-btn")

        try {
            btn.innerHTML = "<i class='fa-solid fa-spinner fa-spin'></i> Logging in..."
            btn.disabled = true

            const res = await fetch("../API/login-api.php", { method: "POST", body: new FormData(this) })
            const data = await res.json()

            if (data.success) {
                Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer
                        toast.onmouseleave = Swal.resumeTimer
                    }
                }).fire({
                    icon: "success",
                    title: data.message
                }).then(() => {
                    window.location.href = "../index.php"
                })
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message,
                    confirmButtonColor: "#3b82f6"
                })

                btn.innerHTML = "Login"
                btn.disabled = false
            }
        } catch (err) {
            console.log(err)
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "An error occurred while logging in.",
                confirmButtonColor: "#3b82f6"
            })

            btn.innerHTML = "Login"
            btn.disabled = false
        }
    })
</script>