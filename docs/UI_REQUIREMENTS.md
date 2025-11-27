# Spesifikasi UI Berdasarkan Perjalanan Phase (0 → 7)

Tanggal: 27 November 2025
Versi: 1.0

Tujuan dokumen ini adalah merangkum kebutuhan UI frontend secara bertahap mengikuti fase pengembangan yang sudah dan akan kita lakukan. Dokumen fokus pada struktur halaman, elemen UI, state, dan interaksi yang dibutuhkan agar seluruh logika yang telah dibuat dapat digunakan. Styling bukan fokus (bisa mengikuti Tailwind/Vite di fase selanjutnya).

Catatan penting: Jika beberapa fitur dapat digabung dalam satu halaman, semua kebutuhannya ditulis dalam satu section (tidak dipecah). Hanya bila benar-benar halaman berbeda, barulah dipisahkan.

---

## Halaman 1 — Kanban (Phase 4–5)
Route: `/kanban`
Komponen: Livewire `KanbanBoard`

Kebutuhan UI:
- Kolom status (3–4 kolom): `todo`, `doing`, `done`, `hold` (opsional tampilkan `dropped`).
- Kartu task per kolom menampilkan minimal: `title`, `priority_score`, `deadline (jika ada)`, `energy (jika ada)`, status (implicit dari kolom).
- Interaksi pemindahan status:
  - Minimal: dropdown aksi pada kartu → panggil `move(taskId, targetStatus)`.
  - Lanjutan: drag-drop antar kolom (tetap memanggil method `move`).
- Filter bar (di bagian atas halaman):
  - `search` (input text) → filter ke `title/description`.
  - `deadlineFilter` (select): `All`, `Overdue`, `Soon (≤3 hari)`.
  - `energyMin` (number/range 1–10, opsional) → hanya menampilkan task dengan energy ≥ nilai ini.
- Sorting default di setiap kolom: by `priority_score DESC` (sudah di-logika melalui scope `orderByPriority`).
- Empty state per kolom: tampilkan pesan “Tidak ada task” bila kolom kosong.
- Feedback:
  - Notifikasi ringan saat berhasil pindah status (event `task-moved`).
- Error state: tampilkan pesan umum jika aksi gagal, dengan opsi coba lagi.

---

## Halaman 2 — Daftar Prioritas + Smart Views (Phase 2, 5)
Route: `/priority`
Komponen: Livewire `PriorityList` (mode switcher dalam satu halaman)

Kebutuhan UI:
- Mode switcher (tab/toggle) dalam SATU halaman:
  - `Priority` (default): daftar global urut `priority_score DESC`.
  - `Nearest Deadline`: daftar dengan `deadline ASC` (hanya yang memiliki deadline).
  - `Easiest Task`: daftar dengan `difficulty ASC`.
- Tabel/daftar task menampilkan: `title`, `priority_score`, `difficulty`, `obligation`, `desire`, `deadline`, `status`, `energy (opsional)`.
- Quick actions pada setiap baris:
  - Ubah status (dropdown) → panggil `TaskManager::updateStatus()`.
  - Arsipkan (soft delete) → `TaskManager::archive()`.
- Filter bar (atas halaman, berlaku untuk semua mode):
  - `search` (title/description).
  - `deadlineFilter`: `All`, `Overdue`, `Soon`.
  - `energyMin` (opsional).
- Empty state: pesan “Belum ada task sesuai filter”.
- Feedback: notifikasi singkat setelah aksi (update/arsip) sukses.

---

## Halaman 3 — Manajemen Tugas (Phase 3)
Route: `/tasks`
Komponen: Livewire `TaskManager` (form create/edit) + daftar ringkas

Kebutuhan UI:
- Form Create/Edit (bisa modal/side-panel di halaman yang sama):
  - Field: `title` (required), `description`, `difficulty (1–10)`, `desire (1–10)`, `obligation (1–10)`, `deadline (datetime, optional)`, `status` (enum), `energy (1–10, optional)`.
  - Validasi: tampilkan pesan error per field sesuai rules.
  - Aksi tombol: `Simpan`, `Reset`, `Hapus`, `Arsipkan`, `Pulihkan (jika terarsip)`.
- Ringkasan task (opsional list di bawah form):
  - Tabel sederhana menampilkan beberapa kolom (title, status, priority_score, deadline) untuk memudahkan edit cepat (call `edit(id)`).
- Tampilkan `priority_score` secara real-time setelah pengisian field (post-validate) dengan memanggil recalculation sebelum save atau saat update.
- Feedback: notifikasi setelah create/update/delete/archive/restore.

---

## Halaman 4 — Riwayat & Statistik (Phase 6)
Route: `/stats`
Komponen: Service `TaskStats` + tampilan Livewire/Blade sederhana

Kebutuhan UI:
- Statistik dasar:
  - `Tasks selesai (7 hari terakhir)` — angka total.
  - `Rata-rata difficulty (30 hari)` — angka desimal.
  - `Rata-rata waktu penyelesaian (hari, 30 hari)` — angka desimal.
- Riwayat perubahan (opsional tabel):
  - Daftar `task_logs` (kolom: `waktu`, `task`, `field`, `before`, `after`).
  - Filter ringan: berdasarkan `field` atau rentang tanggal.
- Empty/Loading state: indikator saat menghitung statistik.

---

## Opsional — Navigasi & Layout Dasar
- Navbar minimal:
  - Link: `Kanban`, `Prioritas`, `Tugas`, `Statistik`.
- Layout Blade umum dengan slot konten; Vite untuk asset bundling.

---

## Catatan Interaksi Kunci (Hook ke Logika yang Ada)
- Pemindahan status/kalkulasi ulang:
  - Kanban: panggil `KanbanBoard::move(taskId, status)` → service `PriorityEngine` menghitung ulang → simpan → event untuk notifikasi.
  - Priority List: Quick update status panggil `TaskManager::updateStatus`.
- Create/Edit/Delete/Archive/Restore:
  - `TaskManager` menyediakan semua aksi tersebut, termasuk recalculation dan soft delete/restore.
- Filter & Search:
  - Kanban & Priority List menggunakan properti `search`, `deadlineFilter`, `energyMin` untuk mem-filter query sebelum tampil.
- Statistik & Riwayat:
  - `TaskStats` menyediakan angka; `task_logs` menampilkan riwayat granular.

---

## (Rencana) Phase 7 — Ekspansi (UI Ringkas, Opsional)
Tetap konsisten dengan logika yang sudah dirancang sebelumnya; implementasi menyusul sesuai prioritas.
- Multi-User:
  - Halaman Login/Logout (auth default Laravel); tidak perlu custom UI kompleks.
  - UI scoping per user implicit; admin view (opsional) untuk melihat semua user.
- Workspace/Project:
  - Selector workspace di navbar; konten halaman menyaring data berdasarkan workspace aktif.
- API & Mobile (opsional):
  - Tidak perlu UI web khusus; cukup halaman pengaturan token (bila pakai Sanctum).
- Gamifikasi (opsional):
  - Panel poin/streak/badge sederhana di `/stats` atau widget navbar.
- Template Publik (opsional):
  - Halaman galeri template dengan aksi impor/terapkan.

---

## Checklist Implementasi UI (Urutan Disarankan)
1) `/tasks`: Form create/edit + aksi dasar (Phase 3).
2) `/kanban`: Kolom + filter + pindah status (Phase 4–5).
3) `/priority`: Mode switcher (Priority/Nearest/Easiest) + quick actions (Phase 5).
4) `/stats`: Statistik dasar + riwayat perubahan (Phase 6).
5) Navbar & layout dasar.
6) Tambahan opsional Phase 7 (multi-user, workspace, API token, gamifikasi, template) sesuai prioritas.
