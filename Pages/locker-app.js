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
        btn.style.opacity = activeFilter === s ? '1' : '0.55';
        btn.style.outline = activeFilter === s ? '2px solid rgba(250,204,21,0.5)' : 'none';
        btn.style.outlineOffset = '1px';
    });
}

// ── Render Grid ──────────────────────────────────
function renderGrid() {
    const { rows, cols } = FLOORS[activeFloor];
    const grid = document.getElementById('lockerGrid');
    const sz = cols <= 6 ? 88 : 80;
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
                cell.innerHTML = `
                    <div class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full" style="background:${clr.dot};box-shadow:0 0 6px ${clr.dot}"></div>
                    <span class="font-mono text-[10px] font-medium opacity-70" style="color:${clr.text}">${locker.number}</span>
                    <span class="text-base leading-none">${STATUS_ICON[s]}</span>
                    ${locker.employee ? `<span class="cell-name text-[9px] font-medium text-center leading-tight px-1" style="color:${clr.text};max-width:100%">${locker.employee.split(' ')[0]}</span>` : ''}`;
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
    // Badge
    const badgeEl = document.getElementById('dp-badge');
    badgeEl.innerHTML = `<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-[11px] font-semibold"><div class="w-1.5 h-1.5 rounded-full"></div>${STATUS_LABEL[locker.status]}</div>`;
    const b = badgeEl.firstElementChild;
    b.style.cssText = `background:${clr.badgeBg};border-color:${clr.border};color:${clr.text}`;
    b.querySelector('div').style.cssText = `background:${clr.dot};box-shadow:0 0 5px ${clr.dot}`;
    // Emp rows
    let empHtml = '';
    if (locker.employee) {
        empHtml += `<div class="flex justify-between items-center"><span class="text-[11px] text-zinc-400">Employee</span><span class="text-xs font-semibold text-zinc-700">${locker.employee}</span></div>`;
        empHtml += `<div class="flex justify-between items-center"><span class="text-[11px] text-zinc-400">Emp ID</span><span class="text-xs font-semibold font-mono text-zinc-700">${locker.empId}</span></div>`;
        if (locker.dept) empHtml += `<div class="flex justify-between items-center"><span class="text-[11px] text-zinc-400">Department</span><span class="text-xs font-semibold text-zinc-700">${locker.dept}</span></div>`;
        if (locker.assignedDate) empHtml += `<div class="flex justify-between items-center"><span class="text-[11px] text-zinc-400">Assigned</span><span class="text-[11px] font-mono text-zinc-600">${locker.assignedDate}</span></div>`;
    } else empHtml = `<div class="flex justify-between items-center"><span class="text-[11px] text-zinc-400">Employee</span><span class="text-xs text-zinc-300">— Unassigned —</span></div>`;
    document.getElementById('dp-emprows').innerHTML = empHtml;
    // Active status button highlight
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
    state[activeFloor] = state[activeFloor].filter(l => l.id !== selectedId);
    selectedId = null; closeDetail();
    toast(`Removed ${locker.number}`, 'info'); renderAll();
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
