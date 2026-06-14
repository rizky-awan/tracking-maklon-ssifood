<style>
.fi-sidebar-item span{
    color: #ffffff !important;
}
.fi-sidebar-item-active span {
    color: #F98613 !important;
}
.fi-sidebar-item span:hover{
    color: #F98613 !important;
}
.fi-sidebar-group-button span{
    color: #94a3b8 !important;
}

.fi-icon-btn svg{
    color: #94a3b8 !important;
}

.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button {
    background: rgba(255,255,255,0.12) !important;
    border-radius: 12px;
}

.dark .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button {
    background: rgba(255,255,255,0.06) !important;
    border-radius: 12px;
}

.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button svg {
    color: #ffffff !important;
}

.fi-sidebar .fi-sidebar-item-button:hover {
    background: rgba(255,255,255,0.08) !important;
    border-radius: 12px;
}

.dark .fi-sidebar .fi-sidebar-item-button:hover {
    background: rgba(255,255,255,0.04) !important;
    border-radius: 12px;
}

.fi-sidebar .fi-sidebar-item-button svg {
    color: #94a3b8 !important;
}

/* 🔥 SIDEBAR + LOGO AREA (ABU) */
.fi-sidebar {
    background: #224051 !important; /* soft gray */
}

.fi-sidebar-header {
    background: #ffffff !important;
}

/* 🔥 MAIN CONTENT PUTIH FULL */
/* .fi-main {
    background: #f8f8f8 !important;
} */

/* 🔥 TOPBAR PUTIH (BIAR NYATU) */
.fi-topbar {
    background: #ffffff !important;
    border-bottom: 1px solid #e5e7eb !important; /* light gray border */
}

.dark .fi-topbar {
    background: #224051 !important;
    border-bottom: 1px solid #333333 !important; /* darker border for dark mode */
}

/* 🔥 background paling luar */
body {
    background: #f8f8f8 !important;
}

/* filament wrapper */
.fi-body {
    background: #f1f1f1 !important;
}

/* area scroll utama */
.fi-main {
    /* background: #f8f8f8 !important; */
    min-height: 100vh; /* 🔥 ini penting */
}

/* 🌙 DARK MODE OVERRIDE */
.dark .fi-sidebar {
    background: #2b2f38 !important;
}

.dark .fi-sidebar-header {
    background: #18181B !important;
}

/* main content */
.dark .fi-main {
    background: #232327 !important;
}

/* topbar */
.dark .fi-topbar {
    background: #18181B !important;
}

/* body fallback */
.dark body {
    background: #232327 !important;
}


.fi-sidebar-header,
.fi-sidebar-header * {
    border-bottom: none !important;
    box-shadow: none !important;
}

.fi-topbar nav {
    --tw-ring-shadow: 0 0 #0000 !important;
    box-shadow: none !important;
}

/* Card dashboard */
.card-stat {
    width: 100%;
    max-width: 100%;
    min-height: 120px; /* 🔥 tambah tinggi */
    padding-left: 100px;
    padding-right: 100px;
    padding-top: 20px;
    position: relative;

    border-radius: 18px;
    color: white;

    box-shadow: 0 12px 30px rgba(0,0,0,0.10);
    transition: all 0.25s ease
}

.card-stat:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.card-title {
    font-size: 18px;
    opacity: 0.85;
}

.card-value {
    font-size: 48px;
    font-weight: 700;
    margin-top: 8px;
}

/* gradient colors */
.bg-green {
    background: linear-gradient(135deg, #A9CF37, #119652);
}

.bg-blue {
    background: linear-gradient(135deg, #57ACC8, #284695);
}

.bg-orange {
    background: linear-gradient(135deg, #EEC045, #ED743D);
}

.bg-red {
    background: linear-gradient(135deg, #E97C71, #EE4723);
}

.card-stat::after {
    content: '';
    position: absolute;
    top: -20px;
    right: -20px;
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.dark .card-stat::after {
    background: rgba(0, 0, 0, 0.1);
}

/* 🔥 tombol collapse */
.fi-topbar .fi-icon-btn {
    position: absolute;
    left: -16px; /* pindahin ke pinggir sidebar */
    top: 50%;
    transform: translateY(-50%);

    width: 32px;
    height: 32px;
    border-radius: 999px;

    background: #ffffff !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);

    z-index: 50;
}

.fi-sidebar .fi-icon-btn svg {
    width: 16px;
    height: 16px;
    color: #334155 !important;
}

.fi-sidebar .fi-icon-btn:hover {
    background: #f1f5f9 !important;
    transform: scale(1.1);
}

</style>