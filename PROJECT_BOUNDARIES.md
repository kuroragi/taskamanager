# Batasan & Spesifikasi Proyek: Task Manager (Kanban + Priority)

Tanggal: 27 November 2025
Versi Dokumen: 1.0

## Ringkasan
Aplikasi Task Manager personal dengan alur Kanban dan sistem prioritas terhitung otomatis. Fokus pada stabilitas pondasi data, kalkulasi prioritas yang konsisten, dan interaksi sederhana yang efisien.

## Teknologi & Versi
- Framework Backend: Laravel 12 (PHP ^8.2)
- Frontend Rendering: Blade + Vite
- Komponen Interaktif: Livewire 3 untuk interaksi reaktif ringan (drag-drop status, form dinamis)
- Build Assets: Vite
- Database: MySQL/MariaDB (dev), kompatibel PostgreSQL (opsional)
- Testing: PHPUnit (Feature + Unit)
- Styling: Tailwind CSS (opsional, boleh mulai dengan CSS biasa)

## Scope Wajib (MVP)
- Manajemen task single-user
- Status: `todo`, `doing`, `done`, `hold`/`dropped`
- Field task: `title`, `description`, `difficulty (1–10)`, `desire (1–10)`, `obligation (1–10)`, `deadline (datetime nullable)`, `status`, `priority_score`, `timestamps`
- Prioritas otomatis: `priority_score = (urgency × 3) + (obligation × 2) + desire – difficulty`
- Urgency: fungsi berbasis kedekatan `deadline` (definisi awal linear; detail di bagian Logika)
- Auto-recalculate pada: create, edit, perubahan deadline, perubahan status
- View: Kanban (default) + Priority List
- Sorting default: `ORDER BY priority_score DESC` (global & per kolom Kanban)
- CRUD: create, edit, update status, delete/archive

## Scope Opsional (fase berikutnya)
- Smart Views: Nearest Deadline, Easiest Task, Mode Switcher
- Energy (1–10) sebagai filter, bukan komponen formula
- Tracking: `task_logs`, completion time, statistik personal
- Tags & relasi many-to-many (`tags`, `task_tag`)
- Scheduled recalculation (nightly) via Laravel Scheduler

## Non-Goals (di luar scope MVP)
- Multi-user, workspace/project separation
- Mobile app native
- AI assistant dan gamifikasi
- Public template marketplace

## Batasan Arsitektur
- Monolith Laravel; tanpa microservices
- Livewire 3 digunakan untuk interaksi real-time ringan, tanpa SPA penuh (tidak menggunakan Inertia/React untuk MVP)
- Semua logika prioritas diletakkan di domain layer (Model/Service) dan dipicu via Observer/Listener
- Tidak ada login/registrasi custom (gunakan auth bawaan Laravel jika diperlukan minimal)

## Struktur Direktori (target)
- `app/Models/Task.php`
- `app/Observers/TaskObserver.php` (recalculate on events)
- `app/Services/PriorityEngine.php` (opsional, jika logika dipisah)
- `app/Http/Controllers/TaskController.php`
- `resources/views/tasks/kanban.blade.php`
- `resources/views/tasks/list.blade.php`
- `routes/web.php` (rute CRUD + view)
- `database/migrations/xxxx_xx_xx_create_tasks_table.php`
- `database/migrations/xxxx_xx_xx_create_task_logs_table.php` (opsional)

## Logika Prioritas
- Rentang nilai: `difficulty`, `desire`, `obligation` ∈ [1..10]
- `deadline` nullable; jika null maka `urgency = 0`
- Definisi `urgency` (linear awal):
  - `days_left = max(0, floor((deadline - now) in days))`
  - `urgency = clamp(10 - days_left, 0, 10)`
- Formula prioritas:
  - `priority_score = (urgency × 3) + (obligation × 2) + desire – difficulty`
- Tipe data: `priority_score` disimpan sebagai `decimal(5,2)` atau `float`
- Recalculate Trigger: `creating`, `updating`, `saved`, perubahan `deadline`/`status`

## UX Prinsip
- Kanban 3–4 kolom, grup by status, sorting by `priority_score`
- Perubahan status via dropdown (fase awal); drag-drop via Livewire (fase 4)
- Tampilkan skor prioritas di kartu task
- Filter sederhana: by status, by tag (opsional), by deadline status (overdue/soon)

## Keamanan & Performa
- Validasi server-side untuk rentang nilai (1–10)
- Query efisien: index pada `status`, `deadline`, `priority_score`
- Hindari recalculation berat di request kritikal; gunakan observer + job jika beban meningkat

## Rencana Implementasi Bertahap
1) Fase 0: Finalisasi blueprint (dokumen ini)
2) Fase 1: Migration `tasks` + Model `Task`
3) Fase 2: Priority Engine + Observer + sorting
4) Fase 3: CRUD UI (Blade/Livewire)
5) Fase 4: Kanban interaktif (Livewire drag-drop)
6) Fase 5: Smart Views
7) Fase 6: Tracking & statistik (opsional)

## Kriteria Selesai (MVP)
- Dapat membuat, mengedit, menghapus, dan mengubah status task
- Skor prioritas selalu ter-update sesuai formula
- Kanban dan Priority List berfungsi dengan sorting yang benar
- Pengujian dasar (Feature + Unit) untuk kalkulasi dan flow CRUD

---
Dokumen ini menjadi referensi batasan agar pengembangan fokus, terukur, dan tidak melebar di luar MVP.
