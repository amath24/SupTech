<!DOCTYPE html>

<html lang="fr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Gestion des Classes - SupTech Business School</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#004881",
                        "surface-container": "#ebeef0",
                        "surface-container-high": "#e5e9eb",
                        "inverse-primary": "#a2c9ff",
                        "on-tertiary-fixed-variant": "#693c00",
                        "inverse-on-surface": "#eef1f3",
                        "error-container": "#ffdad6",
                        "tertiary-fixed": "#ffdcbd",
                        "surface-container-highest": "#e0e3e5",
                        "tertiary-fixed-dim": "#ffb86e",
                        "tertiary-container": "#9a5b00",
                        "surface": "#f7fafc",
                        "on-secondary-container": "#5f656c",
                        "on-secondary-fixed": "#161c22",
                        "secondary-fixed": "#dde3eb",
                        "on-surface-variant": "#414750",
                        "surface-tint": "#1960a3",
                        "on-surface": "#181c1e",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed-dim": "#a2c9ff",
                        "tertiary": "#784600",
                        "primary-container": "#2b6cb0",
                        "background": "#f7fafc",
                        "on-secondary": "#ffffff",
                        "on-tertiary-container": "#ffe7d2",
                        "secondary": "#595f66",
                        "outline": "#727782",
                        "on-tertiary": "#ffffff",
                        "on-error": "#ffffff",
                        "surface-variant": "#e0e3e5",
                        "surface-bright": "#f7fafc",
                        "surface-dim": "#d7dadc",
                        "error": "#ba1a1a",
                        "on-secondary-fixed-variant": "#41474e",
                        "secondary-container": "#dde3eb",
                        "on-primary": "#ffffff",
                        "surface-container-low": "#f1f4f6",
                        "primary": "#005394",
                        "inverse-surface": "#2d3133",
                        "outline-variant": "#c1c7d2",
                        "primary-fixed": "#d3e4ff",
                        "on-primary-container": "#e1ecff",
                        "secondary-fixed-dim": "#c1c7cf",
                        "on-primary-fixed": "#001c38",
                        "on-tertiary-fixed": "#2c1600",
                        "on-background": "#181c1e"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "container-max": "1280px",
                        "base": "8px",
                        "margin-mobile": "16px",
                        "margin-desktop": "40px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-sm": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-xl": ["Inter"],
                        "headline-lg": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "600" }],
                        "headline-xl": ["36px", { "lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
<style>
        body { font-family: 'Inter', sans-serif; background-color: #f7fafc; color: #181c1e; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(226, 232, 240, 0.5); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
        .input-focus:focus { border-color: #2b6cb0; box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2); outline: none; }
    </style>
</head>
<body class="bg-background flex h-screen overflow-hidden">
<!-- SideNavBar -->
<nav class="fixed left-0 top-0 h-full w-64 bg-surface dark:bg-inverse-surface border-r border-outline-variant dark:border-outline shadow-sm flex flex-col h-screen py-6 px-4 z-20 hidden md:flex">
<div class="flex items-center gap-3 mb-8 px-4">
<img alt="Logo SupTech Business School" class="w-10 h-10 object-contain" data-alt="Minimalist, modern university logo, blue primary color, high-end vector style graphic suitable for a top-tier business school, set against a pristine white background with subtle ambient lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTsBdXsJG_2DRerOfCDSsg7vPMFFFpycvvFISND7u67V5vS8JSs4qDUVp4a-12BQzA_L913gp4tZW3dKVV7o0FNYue7i8g5iAPozVsVTrO-k9OhAMWso1W74BqBKzaUORmgqkAzay2CnxwzDvmhCXmFJa4a6k_Osyf7FTZvSsL2i6sqsPPkxDjaUQMgUxkLu5qe4AvAyyOnn1Jew4LnTXPAMSKmLZDcvImFriU_mSzHUeKQLnQxw"/>
<div>
<h1 class="text-headline-lg font-headline-lg font-bold text-primary dark:text-inverse-primary">SupTech</h1>
<p class="text-label-sm font-label-sm text-secondary">Business School</p>
</div>
</div>
<ul class="flex-1 space-y-1">
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span class="text-label-md font-label-md">Tableau de bord</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">group</span>
<span class="text-label-md font-label-md">Étudiants</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">person_4</span>
<span class="text-label-md font-label-md">Enseignants</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-primary dark:text-inverse-primary font-bold border-r-4 border-primary opacity-80 bg-surface-container-low" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span>
<span class="text-label-md font-label-md">Classes</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">book</span>
<span class="text-label-md font-label-md">Modules</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">how_to_reg</span>
<span class="text-label-md font-label-md">Inscriptions</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">payments</span>
<span class="text-label-md font-label-md">Paiements</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">grading</span>
<span class="text-label-md font-label-md">Notes</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">calendar_month</span>
<span class="text-label-md font-label-md">Emploi du temps</span>
</a>
</li>
</ul>
<div class="mt-auto border-t border-outline-variant pt-4 space-y-1">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="text-label-md font-label-md">Paramètres</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors duration-200" href="#">
<span class="material-symbols-outlined">help</span>
<span class="text-label-md font-label-md">Aide</span>
</a>
</div>
</nav>
<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col ml-0 md:ml-64 w-full h-full relative">
<!-- TopAppBar -->
<header class="fixed top-0 right-0 w-full md:w-[calc(100%-16rem)] h-16 bg-surface-container-lowest dark:bg-inverse-surface border-b border-outline-variant dark:border-outline shadow-sm z-10 flex justify-between items-center px-gutter">
<div class="flex items-center gap-4">
<!-- Mobile Menu Toggle (Visible only on mobile) -->
<button class="md:hidden text-on-surface p-2">
<span class="material-symbols-outlined">menu</span>
</button>
<h2 class="text-headline-md font-headline-md text-on-surface dark:text-inverse-on-surface hidden sm:block">SupTech Admin</h2>
</div>
<div class="flex items-center gap-6">
<!-- Search bar placeholder -->
<div class="relative hidden lg:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-sm">search</span>
<input class="pl-9 pr-4 py-2 w-64 bg-surface-container-low border border-transparent rounded-full text-body-sm focus:bg-surface-container-lowest focus:border-outline-variant input-focus transition-all" placeholder="Rechercher..." type="text"/>
</div>
<div class="flex items-center gap-4">
<button class="text-on-surface-variant dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary transition-colors">
<span class="material-symbols-outlined">notifications</span>
</button>
<button class="text-on-surface-variant dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-inverse-primary transition-colors">
<span class="material-symbols-outlined">settings</span>
</button>
</div>
<div class="h-8 w-px bg-outline-variant mx-2 hidden sm:block"></div>
<div class="flex items-center gap-3">
<span class="text-label-sm font-label-sm text-secondary hidden sm:block cursor-pointer hover:text-primary transition-colors">Déconnexion</span>
<img alt="Avatar Administrateur" class="w-8 h-8 rounded-full border border-outline-variant object-cover cursor-pointer" data-alt="Professional corporate headshot of a school administrator, middle-aged with glasses, smiling warmly. The photo is well-lit with soft studio lighting against a clean, light grey background, conveying competence and approachability." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDrr4LCiF0ecBpMCwzIhD7tUzD4sKEMn1Bcp9yL9GZ4oWw6l6vfkcmuifJvTASZx6mdiHRaMlmKCLVh0dzI0Lpq-pDv0Z1jpaRgG5VYnj1RztxxIAf6HwJSqxs3a7PF2u57EJycLIjhAgimIIePzVgI8QQ0tOVoQ9_K9XNG5rFiburX9fJQl4XutLkAD0vwgC9BMRFpRnTl-T7p8g__-Ps8jiRDeFiuXCGXI9NzFArqhOUZ4_qd9g"/>
</div>
</div>
</header>
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto mt-16 p-4 sm:p-gutter bg-background">
<div class="max-w-[1280px] mx-auto">
<!-- Page Header & Actions -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
<div>
<h1 class="text-headline-xl font-headline-xl text-on-surface">Gestion des Classes</h1>
<p class="text-body-md font-body-md text-secondary mt-1">Gérez les filières, niveaux et effectifs de l'établissement.</p>
</div>
<div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
<div class="relative w-full sm:w-64">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-lg">search</span>
<input class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest text-body-md input-focus" placeholder="Rechercher une classe..." type="text"/>
</div>
<button class="flex items-center justify-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary transition-colors shadow-sm whitespace-nowrap" onclick="document.getElementById('addModal').classList.remove('hidden')">
<span class="material-symbols-outlined text-sm">add</span>
                            + Ajouter une classe
                        </button>
</div>
</div>
<!-- Data Table (Bento/Card style approach for higher end feel) -->
<div class="glass-card rounded-xl overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
<th class="py-4 px-6 text-label-sm font-label-sm text-secondary uppercase tracking-wider">Nom de la classe</th>
<th class="py-4 px-6 text-label-sm font-label-sm text-secondary uppercase tracking-wider">Filière</th>
<th class="py-4 px-6 text-label-sm font-label-sm text-secondary uppercase tracking-wider">Niveau</th>
<th class="py-4 px-6 text-label-sm font-label-sm text-secondary uppercase tracking-wider">Effectif Max</th>
<th class="py-4 px-6 text-label-sm font-label-sm text-secondary uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary-container font-bold">
                                                B3
                                            </div>
<span class="text-body-md font-body-md font-semibold text-on-surface">Bachelor 3 Marketing</span>
</div>
</td>
<td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">Marketing &amp; Vente</td>
<td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">Bac +3</td>
<td class="py-4 px-6">
<div class="flex items-center gap-2">
<div class="w-full bg-surface-container-high rounded-full h-2 max-w-[100px]">
<div class="bg-primary h-2 rounded-full" style="width: 85%"></div>
</div>
<span class="text-body-sm font-body-sm text-secondary">34/40</span>
</div>
</td>
<td class="py-4 px-6 text-right">
<div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-secondary hover:text-primary transition-colors rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-2 text-secondary hover:text-error transition-colors rounded-full hover:bg-error-container/50">
<span class="material-symbols-outlined text-[20px]">delete</span>
</button>
</div>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-tertiary-container/10 flex items-center justify-center text-tertiary font-bold">
                                                M1
                                            </div>
<span class="text-body-md font-body-md font-semibold text-on-surface">Master 1 Finance</span>
</div>
</td>
<td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">Finance &amp; Audit</td>
<td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">Bac +4</td>
<td class="py-4 px-6">
<div class="flex items-center gap-2">
<div class="w-full bg-surface-container-high rounded-full h-2 max-w-[100px]">
<div class="bg-primary h-2 rounded-full" style="width: 45%"></div>
</div>
<span class="text-body-sm font-body-sm text-secondary">18/40</span>
</div>
</td>
<td class="py-4 px-6 text-right">
<div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-secondary hover:text-primary transition-colors rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-2 text-secondary hover:text-error transition-colors rounded-full hover:bg-error-container/50">
<span class="material-symbols-outlined text-[20px]">delete</span>
</button>
</div>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-secondary-fixed/50 flex items-center justify-center text-on-secondary-fixed font-bold">
                                                B1
                                            </div>
<span class="text-body-md font-body-md font-semibold text-on-surface">Bachelor 1 Tronc Commun</span>
</div>
</td>
<td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">Management Général</td>
<td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">Bac +1</td>
<td class="py-4 px-6">
<div class="flex items-center gap-2">
<div class="w-full bg-surface-container-high rounded-full h-2 max-w-[100px]">
<div class="bg-error h-2 rounded-full" style="width: 98%"></div>
</div>
<span class="text-body-sm font-body-sm text-secondary">59/60</span>
</div>
</td>
<td class="py-4 px-6 text-right">
<div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-secondary hover:text-primary transition-colors rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-2 text-secondary hover:text-error transition-colors rounded-full hover:bg-error-container/50">
<span class="material-symbols-outlined text-[20px]">delete</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</main>
</div>
<!-- Modal Overlay -->
<div class="hidden fixed inset-0 z-50 flex items-center justify-center bg-on-surface/40 backdrop-blur-sm p-4" id="addModal">
<div class="glass-card w-full max-w-lg rounded-xl shadow-lg flex flex-col overflow-hidden animate-fade-in-up">
<!-- Modal Header -->
<div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
<h3 class="text-headline-md font-headline-md text-on-surface">Ajouter une nouvelle classe</h3>
<button class="text-secondary hover:text-on-surface transition-colors p-1" onclick="document.getElementById('addModal').classList.add('hidden')">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<!-- Modal Body -->
<div class="p-6 space-y-5 bg-surface-container-lowest">
<div>
<label class="block text-label-md font-label-md text-on-surface mb-1.5">Nom de la classe</label>
<input class="w-full px-4 py-2.5 rounded border border-outline-variant bg-surface text-body-md input-focus" placeholder="Ex: Bachelor 3 Marketing" type="text"/>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
<div>
<label class="block text-label-md font-label-md text-on-surface mb-1.5">Filière</label>
<select class="w-full px-4 py-2.5 rounded border border-outline-variant bg-surface text-body-md input-focus appearance-none">
<option value="">Sélectionner...</option>
<option value="marketing">Marketing &amp; Vente</option>
<option value="finance">Finance &amp; Audit</option>
<option value="management">Management Général</option>
</select>
</div>
<div>
<label class="block text-label-md font-label-md text-on-surface mb-1.5">Niveau</label>
<select class="w-full px-4 py-2.5 rounded border border-outline-variant bg-surface text-body-md input-focus appearance-none">
<option value="">Sélectionner...</option>
<option value="bac1">Bac +1</option>
<option value="bac2">Bac +2</option>
<option value="bac3">Bac +3</option>
<option value="bac4">Bac +4</option>
<option value="bac5">Bac +5</option>
</select>
</div>
</div>
<div>
<label class="block text-label-md font-label-md text-on-surface mb-1.5">Effectif Max</label>
<input class="w-full px-4 py-2.5 rounded border border-outline-variant bg-surface text-body-md input-focus" placeholder="Ex: 40" type="number"/>
</div>
</div>
<!-- Modal Footer -->
<div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low flex justify-end gap-3">
<button class="px-5 py-2.5 rounded-lg text-secondary font-label-md text-label-md hover:bg-surface-variant transition-colors" onclick="document.getElementById('addModal').classList.add('hidden')">
                    Annuler
                </button>
<button class="bg-primary-container text-white px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary transition-colors shadow-sm">
                    Enregistrer
                </button>
</div>
</div>
</div>
<style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
    </style>
</body></html>