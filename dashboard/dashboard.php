<!DOCTYPE html>

<html class="light" lang="fr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Tableau de bord - ISM Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "tertiary-container": "#6e2d00",
                    "on-tertiary": "#ffffff",
                    "on-primary-fixed-variant": "#264191",
                    "secondary": "#006c49",
                    "secondary-fixed-dim": "#4edea3",
                    "on-tertiary-container": "#ff8f4f",
                    "on-secondary-fixed": "#002113",
                    "error-container": "#ffdad6",
                    "surface-container-low": "#eff4ff",
                    "on-secondary-fixed-variant": "#005236",
                    "surface-tint": "#4059aa",
                    "on-secondary": "#ffffff",
                    "primary-container": "#1e3a8a",
                    "secondary-fixed": "#6ffbbe",
                    "primary-fixed": "#dce1ff",
                    "surface-container-high": "#dce9ff",
                    "on-secondary-container": "#00714d",
                    "tertiary": "#4b1c00",
                    "surface-dim": "#cbdbf5",
                    "on-surface": "#0b1c30",
                    "on-primary-fixed": "#00164e",
                    "on-error-container": "#93000a",
                    "background": "#f8f9ff",
                    "outline-variant": "#c5c5d3",
                    "primary": "#00236f",
                    "on-primary-container": "#90a8ff",
                    "tertiary-fixed-dim": "#ffb690",
                    "surface-variant": "#d3e4fe",
                    "secondary-container": "#6cf8bb",
                    "inverse-surface": "#213145",
                    "on-background": "#0b1c30",
                    "error": "#ba1a1a",
                    "surface-container": "#e5eeff",
                    "inverse-primary": "#b6c4ff",
                    "on-tertiary-fixed-variant": "#783200",
                    "on-primary": "#ffffff",
                    "primary-fixed-dim": "#b6c4ff",
                    "on-surface-variant": "#444651",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-highest": "#d3e4fe",
                    "outline": "#757682",
                    "on-tertiary-fixed": "#341100",
                    "surface-bright": "#f8f9ff",
                    "surface": "#f8f9ff",
                    "inverse-on-surface": "#eaf1ff",
                    "on-error": "#ffffff",
                    "tertiary-fixed": "#ffdbca"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "container-padding": "2rem",
                    "header-height": "72px",
                    "card-gap": "1rem",
                    "sidebar-width": "280px",
                    "gutter": "1.5rem"
            },
            "fontFamily": {
                    "headline-md": ["Inter"],
                    "body-md": ["Inter"],
                    "label-caps": ["Inter"],
                    "title-sm": ["Inter"],
                    "body-sm": ["Inter"],
                    "sidebar-link": ["Inter"],
                    "display-lg": ["Inter"]
            },
            "fontSize": {
                    "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "title-sm": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                    "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "sidebar-link": ["15px", {"lineHeight": "24px", "fontWeight": "500"}],
                    "display-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-pill {
            position: relative;
        }
        .active-pill::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: #6ffbbe;
            border-radius: 0 4px 4px 0;
        }
        body {
            background-color: #f8f9ff;
            color: #0b1c30;
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>
<body class="font-body-md text-body-md overflow-x-hidden">
<!-- Sidebar Navigation -->
<aside class="fixed left-0 top-0 h-screen w-sidebar-width bg-primary dark:bg-primary text-on-primary dark:text-on-primary border-r border-outline-variant shadow-sm z-50 flex flex-col h-full overflow-y-auto">
<div class="px-6 py-8 flex flex-col gap-2">
<img alt="Logo de l'Institut" class="w-full h-auto object-contain" src="https://lh3.googleusercontent.com/aida/AP1WRLv17qjD1Rv9fLz6PQz4Y9_Gfbv3trImNdQwP5-c2Ap-hdEMGTWh4H4gPh8Ue-q4kBi1yvDDX9xMKQhHZSniIUiMp1vAKAvbze6Zvqfes7uXN9TDYHCFPx0iVCdwDPzoG3y5yxJ_xKux5i2hitsbo44AroIQC2g-ESPYrbVeiKak9NB6pfFtG1D8l5PgByJSR1zPqyc-D5RlkNe9OoV29mdagZLxOYGWlM74dyI3fu0ACMzgH7j_Fi2-K2tj"/>
<div class="mt-4">
<p class="text-title-sm font-title-sm text-on-primary">ISM Management</p>
<p class="text-xs opacity-70">Portail Administratif</p>
</div>
</div>
<nav class="flex-grow mt-4 custom-scrollbar">
<ul class="flex flex-col gap-1">
<!-- Dashboard Active -->
<li>
<a class="flex items-center gap-3 px-6 py-3 border-l-4 border-secondary-fixed bg-on-primary/10 text-on-primary font-bold transition-all" href="#">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-sidebar-link text-sidebar-link">Dashboard</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
<span class="font-sidebar-link text-sidebar-link">Étudiants</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="school">school</span>
<span class="font-sidebar-link text-sidebar-link">Enseignants</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="class">class</span>
<span class="font-sidebar-link text-sidebar-link">Classes</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="book">book</span>
<span class="font-sidebar-link text-sidebar-link">Modules</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="person_add">person_add</span>
<span class="font-sidebar-link text-sidebar-link">Inscriptions</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="payments">payments</span>
<span class="font-sidebar-link text-sidebar-link">Paiements</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="description">description</span>
<span class="font-sidebar-link text-sidebar-link">Notes</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
<span class="font-sidebar-link text-sidebar-link">Emploi du temps</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-6 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="query_stats">query_stats</span>
<span class="font-sidebar-link text-sidebar-link">Statistiques</span>
</a>
</li>
</ul>
</nav>
<div class="p-6 border-t border-on-primary/10">
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-sidebar-link text-sidebar-link">Déconnexion</span>
</a>
</div>
</aside>
<!-- Header -->
<header class="fixed top-0 right-0 w-[calc(100%-theme(spacing.sidebar-width))] h-header-height bg-surface-container-lowest dark:bg-surface-container-lowest text-on-surface dark:text-on-surface border-b border-outline-variant shadow-sm z-40 flex justify-between items-center px-gutter h-full w-full">
<div class="flex items-center flex-1 max-w-xl">
<div class="relative w-full">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-variant/30 border-none rounded-full py-2 pl-10 pr-4 focus:ring-2 focus:ring-primary-container text-body-sm font-body-sm outline-none transition-all" placeholder="Rechercher un étudiant, un module ou un cours..." type="text"/>
</div>
</div>
<div class="flex items-center gap-6">
<button class="relative p-2 rounded-full hover:bg-surface-variant/50 transition-colors">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
</button>
<div class="flex items-center gap-3 border-l pl-6 border-outline-variant">
<div class="text-right">
<p class="font-bold text-body-sm">Cheikh Diop</p>
<p class="text-xs text-on-surface-variant">Administrateur</p>
</div>
<div class="w-10 h-10 rounded-full bg-primary-container overflow-hidden ring-2 ring-primary-container/20">
<img class="w-full h-full object-cover" data-alt="A professional studio portrait of a West African male administrator, middle-aged with a warm but authoritative smile, wearing a sharp dark business suit and a clean white shirt. The background is a blurred office environment with soft morning light filtering through windows, creating a polished corporate and academic look with natural skin tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyFFe0d8dNLwVslj-Sy8gLZIsk3wBzmf5D8DhPnCh_UrdurA_7p8PYC8_W8W1U_DCY46r_InOxoLx2MLE1EhOycXDeVtglTY8EcrS6NYh6t7EORnqkncqKXKRKje_E24Bb_3MTi4gs11Vcy1HVc5WDBohX4Ja8NdRMDTp-ffcEv4F6PJ4ahmb-U4iVlEv6d0IwrD1Q1D9_vx-N3PC2R53oWnaaljhSDKBY8uwBOaQN3UORZgfnrdxWqw"/>
</div>
</div>
</div>
</header>
<!-- Main Content -->
<main class="ml-[280px] pt-[72px] min-h-screen p-8 animate-in fade-in duration-700">
<!-- Top Stats Row -->
<div class="flex items-center justify-between mb-8">
<div>
<h1 class="font-headline-md text-headline-md text-primary">Tableau de bord académique</h1>
<p class="text-on-surface-variant mt-1">Bienvenue, Cheikh. Voici l'état actuel de l'institution.</p>
</div>
<button class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2.5 rounded-lg font-title-sm text-sm shadow-md hover:opacity-90 active:scale-95 transition-all">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
                Nouvelle Inscription
            </button>
</div>
<!-- Statistics Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
<!-- Étudiants -->
<div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm hover:-translate-y-1 transition-transform cursor-pointer group">
<div class="flex justify-between items-start">
<div class="p-3 bg-primary-container/10 text-primary rounded-lg">
<span class="material-symbols-outlined">groups</span>
</div>
<span class="text-secondary font-bold text-xs bg-secondary-container/20 px-2 py-1 rounded">+12%</span>
</div>
<div class="mt-4">
<p class="text-on-surface-variant font-label-caps text-label-caps">ÉTUDIANTS</p>
<h3 class="font-display-lg text-display-lg mt-1">342</h3>
</div>
</div>
<!-- Enseignants -->
<div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm hover:-translate-y-1 transition-transform cursor-pointer">
<div class="flex justify-between items-start">
<div class="p-3 bg-secondary-container/10 text-secondary rounded-lg">
<span class="material-symbols-outlined">school</span>
</div>
</div>
<div class="mt-4">
<p class="text-on-surface-variant font-label-caps text-label-caps">ENSEIGNANTS</p>
<h3 class="font-display-lg text-display-lg mt-1">28</h3>
</div>
</div>
<!-- Inscriptions -->
<div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm hover:-translate-y-1 transition-transform cursor-pointer">
<div class="flex justify-between items-start">
<div class="p-3 bg-tertiary-container/10 text-tertiary-container rounded-lg">
<span class="material-symbols-outlined">person_add</span>
</div>
<span class="text-secondary font-bold text-xs bg-secondary-container/20 px-2 py-1 rounded">Actif</span>
</div>
<div class="mt-4">
<p class="text-on-surface-variant font-label-caps text-label-caps">INSCRIPTIONS</p>
<h3 class="font-display-lg text-display-lg mt-1">310</h3>
</div>
</div>
<!-- Taux de réussite -->
<div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm hover:-translate-y-1 transition-transform cursor-pointer">
<div class="flex justify-between items-start">
<div class="p-3 bg-secondary/10 text-secondary rounded-lg">
<span class="material-symbols-outlined">auto_graph</span>
</div>
<div class="w-12 h-1 bg-outline-variant rounded-full mt-4 relative overflow-hidden">
<div class="absolute inset-0 bg-secondary" style="width: 78%"></div>
</div>
</div>
<div class="mt-4">
<p class="text-on-surface-variant font-label-caps text-label-caps">TAUX DE RÉUSSITE</p>
<h3 class="font-display-lg text-display-lg mt-1">78%</h3>
</div>
</div>
</div>
<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
<!-- Monthly Payments (Bar Chart) -->
<div class="lg:col-span-2 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
<div class="flex items-center justify-between mb-8">
<h3 class="font-title-sm text-title-sm">Paiements mensuels (FCFA)</h3>
<select class="bg-surface-variant/20 border-none rounded-lg text-xs font-semibold py-1 pr-8 focus:ring-1 focus:ring-primary">
<option>Année 2024</option>
<option>Année 2023</option>
</select>
</div>
<div class="flex items-end justify-between h-64 gap-4 px-2">
<!-- Simple CSS Chart bars -->
<div class="flex flex-col items-center flex-1 group">
<div class="w-full bg-primary-container/20 rounded-t-md relative group-hover:bg-primary-container transition-all" style="height: 45%">
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-on-surface-bright text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">1.2M</div>
</div>
<p class="text-[10px] mt-2 font-bold text-on-surface-variant">JAN</p>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="w-full bg-primary-container/20 rounded-t-md relative group-hover:bg-primary-container transition-all" style="height: 65%">
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-on-surface-bright text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">2.1M</div>
</div>
<p class="text-[10px] mt-2 font-bold text-on-surface-variant">FEV</p>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="w-full bg-primary-container/40 rounded-t-md relative group-hover:bg-primary-container transition-all" style="height: 85%">
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-on-surface-bright text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">3.5M</div>
</div>
<p class="text-[10px] mt-2 font-bold text-on-surface-variant">MAR</p>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="w-full bg-primary-container/20 rounded-t-md relative group-hover:bg-primary-container transition-all" style="height: 70%">
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-on-surface-bright text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">2.8M</div>
</div>
<p class="text-[10px] mt-2 font-bold text-on-surface-variant">AVR</p>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="w-full bg-primary-container/60 rounded-t-md relative group-hover:bg-primary-container transition-all" style="height: 95%">
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-on-surface-bright text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">4.2M</div>
</div>
<p class="text-[10px] mt-2 font-bold text-on-surface-variant">MAI</p>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="w-full bg-primary-container/40 rounded-t-md relative group-hover:bg-primary-container transition-all" style="height: 75%">
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-on-surface-bright text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">3.1M</div>
</div>
<p class="text-[10px] mt-2 font-bold text-on-surface-variant">JUIN</p>
</div>
</div>
</div>
<!-- Distribution par classe (Donut Chart representation) -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm flex flex-col">
<h3 class="font-title-sm text-title-sm mb-6">Répartition par classe</h3>
<div class="relative flex-grow flex items-center justify-center">
<!-- SVG Donut Chart -->
<svg class="w-40 h-40" viewbox="0 0 36 36">
<path class="text-surface-variant/20" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="transparent" stroke="currentColor" stroke-width="3"></path>
<path class="text-primary" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="transparent" stroke="currentColor" stroke-dasharray="40, 100" stroke-linecap="round" stroke-width="3"></path>
<path class="text-secondary" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="transparent" stroke="currentColor" stroke-dasharray="25, 100" stroke-dashoffset="-40" stroke-linecap="round" stroke-width="3"></path>
<path class="text-tertiary-container" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="transparent" stroke="currentColor" stroke-dasharray="15, 100" stroke-dashoffset="-65" stroke-linecap="round" stroke-width="3"></path>
<path class="text-surface-dim" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="transparent" stroke="currentColor" stroke-dasharray="20, 100" stroke-dashoffset="-80" stroke-linecap="round" stroke-width="3"></path>
</svg>
<div class="absolute inset-0 flex flex-col items-center justify-center">
<span class="text-headline-md font-headline-md">342</span>
<span class="text-[10px] text-on-surface-variant">TOTAL</span>
</div>
</div>
<div class="mt-6 space-y-2">
<div class="flex items-center justify-between text-xs">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-primary"></span>
<span>L3 Dev Web</span>
</div>
<span class="font-bold">40%</span>
</div>
<div class="flex items-center justify-between text-xs">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-secondary"></span>
<span>M1 Génie Logiciel</span>
</div>
<span class="font-bold">25%</span>
</div>
<div class="flex items-center justify-between text-xs">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-tertiary-container"></span>
<span>M2 Cybersecurity</span>
</div>
<span class="font-bold">15%</span>
</div>
</div>
</div>
</div>
<!-- Recent Activity Rows -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
<!-- Derniers inscrits -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
<div class="p-6 border-b border-outline-variant flex justify-between items-center">
<h3 class="font-title-sm text-title-sm">Derniers étudiants inscrits</h3>
<a class="text-primary text-xs font-bold hover:underline" href="#">Voir tout</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-variant/10 font-label-caps text-label-caps text-on-surface-variant">
<tr>
<th class="px-6 py-4">Étudiant</th>
<th class="px-6 py-4">Filière</th>
<th class="px-6 py-4">Date</th>
<th class="px-6 py-4">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<tr class="hover:bg-surface-variant/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-dim">
<img class="w-full h-full object-cover" data-alt="Close-up headshot of a young Senegalese student named Mamadou Diallo, smiling confidently. He is wearing a modern polo shirt in a well-lit academic hallway. The photography is clean, sharp, and high-resolution, maintaining a professional student directory aesthetic with soft bokeh background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAcOeFZmiM3r6yrSG30DqAmhP4RRibKWNFyTVyJq7eKuqGR1KW1dmbwHVXQVnQmUpeRi8hLP_DS53tOAkb6RRN4xeo34OZt4KYynrgocvog8Ra4SIDK3-46JUlyxWEJ0bZjqwYr9OT848oZfeIvlY2N7MvtBNM1LzhjiRRQxgnR3pGBUFTjcB4ffe8NotuG7a2WdtVBQlMMOCk1SswlZfPFguqYKXAIKccicb1AM8zMQaVYGM2jXz6e1g"/>
</div>
<span class="font-medium">Mamadou Diallo</span>
</div>
</td>
<td class="px-6 py-4 text-sm">L3 Dev Web</td>
<td class="px-6 py-4 text-sm text-on-surface-variant">12 Oct 2023</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 p-1.5 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]">visibility</span>
</button>
</td>
</tr>
<tr class="hover:bg-surface-variant/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-dim">
<img class="w-full h-full object-cover" data-alt="Portrait of a young West African female student, Fatou Ndiaye, with a bright smile and braided hair, wearing a professional blouse in a library setting. High-quality corporate educational style photography with natural daylight, soft focus on books in the background, sharp facial features." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAvW98SXCA08hLBkJBRvhV9Pa6Mg7Ht2A0rVxeVsBASt4gYwqwGPkIO3bfZvjFPiYJ_s5TPeCEItgcIJYLzZwhBz943gNrG8bCUBjEOFpdXuHzLiGUNfA1KxgwrALLOxIoFQ9rPN7i0FQXkpy9svBSUxU-9zUcHgkLqX-mjlosZWVoRzbdVD29h2WjYENuPikTxT32vnT12D8OMEaFpJpniKNHU9CDPMtQuzrV52LgxFISJON4Om4xVAw"/>
</div>
<span class="font-medium">Fatou Ndiaye</span>
</div>
</td>
<td class="px-6 py-4 text-sm">M1 Génie Logiciel</td>
<td class="px-6 py-4 text-sm text-on-surface-variant">11 Oct 2023</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 p-1.5 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]">visibility</span>
</button>
</td>
</tr>
<tr class="hover:bg-surface-variant/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-dim">
<img class="w-full h-full object-cover" data-alt="Portrait of Ousmane Sarr, a young Senegalese male student in a casual academic setting. He has a focused expression and is wearing a button-down shirt. The lighting is balanced, showcasing a professional academic atmosphere with a blurred campus garden in the background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDv7rzPg1mqI2sHOgE6mBL1LaTiOajvQSQs07mB8kJkwT6I_y18CuwU-eHDum7acTICsuvepT6cTOdjtdgE-iFBMi2KQWzdRujdJXj9bDAfj0a4iK1oOKzvGlq-HlcxVhCV51ummRrTjVnfEI1ELrqee4DO2rlE-Sp43Z_6FVdSAIokSA9NjveQ1Wl9CvNxajBoHqofs00do23iMCnNl3jZ-GExgTH6wWImJPd6yiBpC2Ui032qo08zqg"/>
</div>
<span class="font-medium">Ousmane Sarr</span>
</div>
</td>
<td class="px-6 py-4 text-sm">M2 Data Science</td>
<td class="px-6 py-4 text-sm text-on-surface-variant">10 Oct 2023</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 p-1.5 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]">visibility</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Dernières notes -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
<div class="p-6 border-b border-outline-variant flex justify-between items-center">
<h3 class="font-title-sm text-title-sm">Dernières notes enregistrées</h3>
<a class="text-primary text-xs font-bold hover:underline" href="#">Saisir une note</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-variant/10 font-label-caps text-label-caps text-on-surface-variant">
<tr>
<th class="px-6 py-4">Module</th>
<th class="px-6 py-4">Étudiant</th>
<th class="px-6 py-4 text-center">Note</th>
<th class="px-6 py-4">Statut</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<tr class="hover:bg-surface-variant/5 transition-colors">
<td class="px-6 py-4">
<div class="text-sm font-medium">Algorithmique</div>
<div class="text-[10px] text-on-surface-variant">Prof. Ba</div>
</td>
<td class="px-6 py-4 text-sm">Mamadou Diallo</td>
<td class="px-6 py-4 text-center">
<span class="font-bold text-primary">16.5</span>
</td>
<td class="px-6 py-4">
<span class="px-2 py-1 bg-secondary/10 text-secondary text-[10px] font-bold rounded-full">Validé</span>
</td>
</tr>
<tr class="hover:bg-surface-variant/5 transition-colors">
<td class="px-6 py-4">
<div class="text-sm font-medium">Base de Données</div>
<div class="text-[10px] text-on-surface-variant">Prof. Faye</div>
</td>
<td class="px-6 py-4 text-sm">Fatou Ndiaye</td>
<td class="px-6 py-4 text-center">
<span class="font-bold text-primary">14.0</span>
</td>
<td class="px-6 py-4">
<span class="px-2 py-1 bg-secondary/10 text-secondary text-[10px] font-bold rounded-full">Validé</span>
</td>
</tr>
<tr class="hover:bg-surface-variant/5 transition-colors">
<td class="px-6 py-4">
<div class="text-sm font-medium">Marketing Digital</div>
<div class="text-[10px] text-on-surface-variant">Prof. Sow</div>
</td>
<td class="px-6 py-4 text-sm">Ousmane Sarr</td>
<td class="px-6 py-4 text-center">
<span class="font-bold text-error">08.0</span>
</td>
<td class="px-6 py-4">
<span class="px-2 py-1 bg-error/10 text-error text-[10px] font-bold rounded-full">Rattrapage</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</main>
<!-- Interactions -->
<script>
        document.addEventListener('DOMContentLoaded', () => {
            // Hover effect on stat cards
            const cards = document.querySelectorAll('.bg-surface-container-lowest');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-4px)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });

            // Smooth entrance animation for tables
            const tables = document.querySelectorAll('tbody tr');
            tables.forEach((tr, index) => {
                tr.style.opacity = '0';
                tr.style.transform = 'translateX(10px)';
                setTimeout(() => {
                    tr.style.transition = 'all 0.4s ease-out';
                    tr.style.opacity = '1';
                    tr.style.transform = 'translateX(0)';
                }, 100 * index);
            });
        });
    </script>
</body></html>