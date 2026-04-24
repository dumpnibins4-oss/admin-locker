<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Locker Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col h-screen overflow-hidden text-slate-800">
    
    <header class="pt-8 pb-4 shrink-0 mx-auto w-full">
        <div class="flex flex-col items-start justify-start gap-1">
            <h1 class="text-3xl font-medium text-gray-800">Employee Upload</h1>
            <p class="text-sm text-gray-500 font-medium">Search employees from the system and add locker details before plotting</p>
        </div>
    </header>

    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto pr-8">
            <div class="w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6">
                        <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Search Employee
                        </h2>
                        
                        <div class="relative mb-6">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" placeholder="Search by name or Biometrics ID...">
                        </div>

                        <div class="border border-green-200 bg-green-50/30 rounded-xl p-4 flex flex-col gap-4 relative group">
                            <button class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">Norkisa Abantas</h3>
                                    <p class="text-sm text-gray-500">ID: 27498</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-2 pt-4 border-t border-gray-200/60">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Department</p>
                                    <p class="text-sm font-medium text-gray-800">Production Department - LRN</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Regular</span>
                                        <span class="text-xs text-gray-500">Hired 2024-02-12</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6">
                        <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Add Locker Details
                        </h2>

                        <form class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phase <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-4 gap-2">
                                    <button type="button" class="py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all bg-white shadow-sm">P1</button>
                                    <button type="button" class="py-2.5 border-2 border-blue-500 rounded-lg text-sm font-bold text-blue-700 bg-blue-50 transition-all shadow-sm">P2</button>
                                    <button type="button" class="py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all bg-white shadow-sm">P3</button>
                                    <button type="button" class="py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all bg-white shadow-sm">P4</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Classification <span class="text-red-500">*</span></label>
                                    <div class="flex bg-gray-100 p-1 rounded-lg">
                                        <button type="button" class="flex-1 py-2 text-sm font-semibold rounded-md shadow-sm bg-white text-gray-800 transition-all">Production</button>
                                        <button type="button" class="flex-1 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all">Non-Production</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Employment Type <span class="text-red-500">*</span></label>
                                    <div class="flex bg-gray-100 p-1 rounded-lg">
                                        <button type="button" class="flex-1 py-2 text-sm font-semibold rounded-md shadow-sm bg-white text-gray-800 transition-all">Regular</button>
                                        <button type="button" class="flex-1 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all">Probationary</button>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1.5">Auto-detected from date hired. Override if needed.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Gender <span class="text-red-500">*</span></label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="gender" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Male</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="gender" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Female</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Notes (Optional)</label>
                                <textarea rows="3" class="block w-full rounded-xl border-gray-200 border bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors" placeholder="Any special instructions..."></textarea>
                            </div>

                            <div class="pt-2">
                                <button type="button" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-slate-800 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add to Batch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-5 xl:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 sticky top-0 flex flex-col h-[600px]">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-base font-semibold text-gray-800">Batch Queue</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Employees ready to submit</p>
                            </div>
                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold leading-none text-slate-800 bg-slate-100 rounded-full">
                                0 Added
                            </span>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center text-center px-4">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 mb-1">No employees added yet</h3>
                            <p class="text-sm text-gray-500 max-w-[200px]">Fill the form and click "Add to Batch" to queue them here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>