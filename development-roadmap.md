🧭 DEVELOPMENT PHASE – TASK MANAGER (KANBAN + PRIORITY SYSTEM)
🟢 PHASE 0 — DEFINISI SISTEM (Identity & Rule Layer)

Fokus: membentuk otak & jiwa aplikasi

Yang perlu kamu tetapkan:

Domain & tujuan aplikasi

Personal Task Manager

Kanban-based

Priority-driven (bukan manual)

State utama Task

todo

doing

done

hold / dropped

Variabel pembentuk prioritas

Difficulty (1–10)

Desire (1–10)

Obligation (1–10)

Deadline

(Optional) Energy

Logic perhitungan awal

priority_score = (urgency × 3) + (obligation × 2) + desire – difficulty


Jenis view utama

Kanban View (default)

Nearest Deadline

Easiest Task

Priority List

📌 Output fase ini:

Blueprint final aplikasi kamu

🟡 PHASE 1 — STRUKTUR DATA & DOMAIN MODEL

Fokus: pondasi data yang stabil & scalable

Yang perlu dibuat:

Tabel tasks

Field utama:

title

description

difficulty

desire

obligation

deadline

status

priority_score

timestamps

(Optional tapi direkomendasikan sejak awal)

task_logs (for tracking perubahan)

tags

task_tag

📌 Output fase ini:

Data structure yang siap berkembang

🟠 PHASE 2 — CORE LOGIC (Priority Engine & Behavior)

Fokus: bagaimana task “berpikir”

Yang perlu dibuat:

Function calculateUrgency()

Function calculatePriority()

Auto update saat:

Task dibuat

Task di-edit

Deadline berubah

Status berubah

Scheduled recalculation

Sorting default:

ORDER BY priority_score DESC

📌 Output fase ini:

Task kamu punya otak dan nilai

🔵 PHASE 3 — INTERAKSI UTAMA (Task System)

Fokus: membuat & mengelola task

Yang perlu dibuat:

Form Create Task

Form Edit Task

Update status task

Delete / archive task

Tampilkan priority score

📌 Output fase ini:

Task bisa benar-benar hidup & digunakan

🟣 PHASE 4 — KANBAN SYSTEM

Fokus: visual & alur kerja

Yang perlu dibuat:

Layout 3–4 kolom Kanban

Group task by status

Sorting by priority inside each column

Status change (dropdown / drag-drop)

Filter & search ringan

📌 Output fase ini:

“Papan Pikiran” kamu terbentuk

⚫ PHASE 5 — SMART VIEW (Mode A, C, F)

Fokus: membantu kamu mengambil keputusan cepat

Yang perlu dibuat:

Nearest Deadline view

Easiest Task view

Full Kanban default

Mode switcher

Energy filter (optional)

📌 Output fase ini:

Aplikasi mulai terasa “mengerti kamu”

🟤 PHASE 6 — TRACKING & REFLECTION

Fokus: bukan cuma mengerjakan, tapi menyadari pola

Yang perlu dibuat:

Task history

Completion time

Difficulty vs Completion chart

Habit report

Personal statistics

📌 Output fase ini:

Data tentang dirimu mulai terbentuk

🔴 PHASE 7 — MUTATION & EXPANSION (opsional)

Fokus: naik kelas / jadi produk

Yang bisa masuk di sini:

• Multi-user
• Project / Workspace
• AI Assistant
• Mobile App
• Gamifikasi
• Public template

📌 Output fase ini:

Side-project berubah jadi “system”