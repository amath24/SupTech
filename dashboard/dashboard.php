<!DOCTYPE html>

<html class="light" lang="fr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SupTech Admin - Tableau de bord</title>
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
                      "outline": "#727782",
                      "secondary-container": "#dde3eb",
                      "on-error-container": "#93000a",
                      "error-container": "#ffdad6",
                      "primary-fixed": "#d3e4ff",
                      "surface-dim": "#d7dadc",
                      "on-tertiary-fixed-variant": "#693c00",
                      "surface": "#f7fafc",
                      "on-secondary": "#ffffff",
                      "surface-bright": "#f7fafc",
                      "surface-variant": "#e0e3e5",
                      "on-tertiary": "#ffffff",
                      "primary": "#005394",
                      "on-surface": "#181c1e",
                      "surface-container-high": "#e5e9eb",
                      "error": "#ba1a1a",
                      "background": "#f7fafc",
                      "on-surface-variant": "#414750",
                      "surface-tint": "#1960a3",
                      "on-secondary-fixed": "#161c22",
                      "inverse-primary": "#a2c9ff",
                      "on-primary-container": "#e1ecff",
                      "tertiary-fixed-dim": "#ffb86e",
                      "on-error": "#ffffff",
                      "outline-variant": "#c1c7d2",
                      "surface-container": "#ebeef0",
                      "primary-container": "#2b6cb0",
                      "surface-container-low": "#f1f4f6",
                      "inverse-surface": "#2d3133",
                      "on-tertiary-container": "#ffe7d2",
                      "on-secondary-container": "#5f656c",
                      "on-tertiary-fixed": "#2c1600",
                      "tertiary-fixed": "#ffdcbd",
                      "on-primary": "#ffffff",
                      "secondary": "#595f66",
                      "on-background": "#181c1e",
                      "tertiary-container": "#9a5b00",
                      "surface-container-highest": "#e0e3e5",
                      "secondary-fixed": "#dde3eb",
                      "primary-fixed-dim": "#a2c9ff",
                      "on-secondary-fixed-variant": "#41474e",
                      "on-primary-fixed-variant": "#004881",
                      "secondary-fixed-dim": "#c1c7cf",
                      "inverse-on-surface": "#eef1f3",
                      "tertiary": "#784600",
                      "surface-container-lowest": "#ffffff",
                      "on-primary-fixed": "#001c38"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "margin-desktop": "40px",
                      "gutter": "24px",
                      "margin-mobile": "16px",
                      "container-max": "1280px",
                      "base": "8px"
              },
              "fontFamily": {
                      "headline-md": [
                              "Inter"
                      ],
                      "label-sm": [
                              "Inter"
                      ],
                      "headline-xl": [
                              "Inter"
                      ],
                      "body-md": [
                              "Inter"
                      ],
                      "label-md": [
                              "Inter"
                      ],
                      "headline-lg": [
                              "Inter"
                      ],
                      "body-sm": [
                              "Inter"
                      ],
                      "body-lg": [
                              "Inter"
                      ]
              },
              "fontSize": {
                      "headline-md": [
                              "20px",
                              {
                                      "lineHeight": "28px",
                                      "fontWeight": "600"
                              }
                      ],
                      "label-sm": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "letterSpacing": "0.05em",
                                      "fontWeight": "500"
                              }
                      ],
                      "headline-xl": [
                              "36px",
                              {
                                      "lineHeight": "44px",
                                      "letterSpacing": "-0.02em",
                                      "fontWeight": "700"
                              }
                      ],
                      "body-md": [
                              "16px",
                              {
                                      "lineHeight": "24px",
                                      "fontWeight": "400"
                              }
                      ],
                      "label-md": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "600"
                              }
                      ],
                      "headline-lg": [
                              "24px",
                              {
                                      "lineHeight": "32px",
                                      "letterSpacing": "-0.01em",
                                      "fontWeight": "600"
                              }
                      ],
                      "body-sm": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "400"
                              }
                      ],
                      "body-lg": [
                              "18px",
                              {
                                      "lineHeight": "28px",
                                      "fontWeight": "400"
                              }
                      ]
              }
            }
          }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .icon-filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased flex h-screen overflow-hidden">
<!-- SideNavBar -->
<nav class="fixed left-0 top-0 h-full w-64 bg-surface border-r border-outline-variant shadow-sm flex flex-col py-6 px-4 z-20">
<!-- Brand Header -->
<div class="flex items-center gap-3 mb-10 px-2">
<div class="w-10 h-10 rounded bg-primary-container flex items-center justify-center text-on-primary font-bold text-lg flex-shrink-0">
                S
            </div>
<div class="flex flex-col">
<span class="text-headline-md font-headline-md text-primary tracking-tight leading-tight">SupTech</span>
<span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Business School</span>
</div>
</div>
<!-- Main Navigation -->
<div class="flex-1 overflow-y-auto">
<ul class="space-y-1">
<!-- Active Tab -->
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-primary font-bold border-l-4 border-primary bg-surface-container-low opacity-80" href="#">
<span class="material-symbols-outlined icon-filled">dashboard</span>
<span class="text-label-md font-label-md">Tableau de bord</span>
</a>
</li>
<!-- Inactive Tabs -->
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">group</span>
<span class="text-label-md font-label-md">Étudiants</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">person_4</span>
<span class="text-label-md font-label-md">Enseignants</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">school</span>
<span class="text-label-md font-label-md">Classes</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">book</span>
<span class="text-label-md font-label-md">Modules</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">how_to_reg</span>
<span class="text-label-md font-label-md">Inscriptions</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">payments</span>
<span class="text-label-md font-label-md">Paiements</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">grading</span>
<span class="text-label-md font-label-md">Notes</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">calendar_month</span>
<span class="text-label-md font-label-md">Emploi du temps</span>
</a>
</li>
</ul>
</div>
<!-- Footer Navigation -->
<div class="pt-6 mt-6 border-t border-outline-variant">
<ul class="space-y-1">
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="text-label-md font-label-md">Paramètres</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-secondary hover:text-primary hover:bg-surface-container-low transition-colors duration-200 border-l-4 border-transparent" href="#">
<span class="material-symbols-outlined">help</span>
<span class="text-label-md font-label-md">Aide</span>
</a>
</li>
</ul>
</div>
</nav>
<!-- Main Wrapper -->
<div class="flex-1 flex flex-col ml-64 h-full relative">
<!-- TopAppBar -->
<header class="h-16 w-full bg-surface-container-lowest border-b border-outline-variant shadow-sm flex justify-between items-center px-gutter z-10 shrink-0">
<!-- Search Bar (on_left) -->
<div class="flex-1 max-w-md">
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-transparent rounded-lg text-body-sm focus:bg-surface-container-lowest focus:border-primary focus:ring-2 focus:ring-primary-fixed focus:outline-none transition-all duration-200 placeholder-on-surface-variant" placeholder="Rechercher..." type="text"/>
</div>
</div>
<!-- Brand / Product Name (Center-ish, optional given instructions but adding as requested by JSON) -->
<div class="hidden lg:flex flex-1 justify-center">
<span class="text-headline-md font-headline-md text-on-surface">SupTech Admin</span>
</div>
<!-- Trailing Actions -->
<div class="flex items-center gap-4 flex-1 justify-end">
<button aria-label="Notifications" class="p-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full border border-surface-container-lowest"></span>
</button>
<button aria-label="Settings" class="p-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded-full transition-colors">
<span class="material-symbols-outlined">settings</span>
</button>
<div class="h-6 w-px bg-outline-variant mx-2"></div>
<div class="flex items-center gap-3">
<div class="text-right hidden md:block">
<p class="text-label-sm font-label-sm text-on-surface">Admin Principal</p>
<button class="text-label-sm font-label-sm text-secondary hover:text-error transition-colors">Déconnexion</button>
</div>
<img alt="Avatar Administrateur" class="w-8 h-8 rounded-full object-cover border border-outline-variant" data-alt="A highly detailed close-up portrait of a professional academic administrator. They are wearing business casual attire, set against a clean, bright, and minimalist office background. The lighting is soft and even, typical of a modern light-mode application UI, projecting a sense of competence and calm authority." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCRArXGhQsgLuWgg1fNpEOFaacm4tsdQys9how6lMO-uCIR64_MUfUOy4gEStqktNn2jLQ1KL3rG0fFUl-fdBkk7QQlePOnYVrjW2mL3sw4wvUQpG8ngHd8nBVjb3R93aDAW6al3kSCLTl5QhCIBVt0HSzHb4HNyqi_6de7Y4eV_0NTdATANBO09_IMRjpT7vf6Dt_D_NEcjLuPpuFMfhPjZVpEPnUhfwu3NxTkyiEDgN9LnKGmXA"/>
</div>
</div>
</header>
<!-- Main Content Area -->
<main class="flex-1 overflow-y-auto p-gutter bg-background">
<div class="max-w-container-max mx-auto space-y-8">
<!-- Page Header -->
<div>
<h1 class="text-headline-xl font-headline-xl text-on-surface mb-2">Vue d'ensemble</h1>
<p class="text-body-md font-body-md text-secondary">Rapport récapitulatif pour l'année académique en cours.</p>
</div>
<!-- Summary Row (6 Cards) -->
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-6">
<!-- Card 1 -->
<div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col border border-transparent hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-primary-fixed rounded-lg text-primary">
<span class="material-symbols-outlined">group</span>
</div>
</div>
<p class="text-label-md font-label-md text-secondary mb-1">Total Étudiants</p>
<p class="text-headline-lg font-headline-lg text-on-surface">1,240</p>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col border border-transparent hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-secondary-container rounded-lg text-on-secondary-container">
<span class="material-symbols-outlined">person_4</span>
</div>
</div>
<p class="text-label-md font-label-md text-secondary mb-1">Total Enseignants</p>
<p class="text-headline-lg font-headline-lg text-on-surface">85</p>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col border border-transparent hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-primary-fixed rounded-lg text-primary">
<span class="material-symbols-outlined">school</span>
</div>
</div>
<p class="text-label-md font-label-md text-secondary mb-1">Total Classes</p>
<p class="text-headline-lg font-headline-lg text-on-surface">42</p>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col border border-transparent hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-secondary-container rounded-lg text-on-secondary-container">
<span class="material-symbols-outlined">book</span>
</div>
</div>
<p class="text-label-md font-label-md text-secondary mb-1">Total Modules</p>
<p class="text-headline-lg font-headline-lg text-on-surface">120</p>
</div>
<!-- Card 5 -->
<div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col border border-transparent hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-primary-fixed rounded-lg text-primary">
<span class="material-symbols-outlined">how_to_reg</span>
</div>
</div>
<p class="text-label-md font-label-md text-secondary mb-1">Total Inscriptions</p>
<p class="text-headline-lg font-headline-lg text-on-surface">310</p>
</div>
<!-- Card 6 -->
<div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm flex flex-col border border-transparent hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-tertiary-fixed rounded-lg text-on-tertiary-fixed">
<span class="material-symbols-outlined">analytics</span>
</div>
</div>
<p class="text-label-md font-label-md text-secondary mb-1">Taux de réussite</p>
<p class="text-headline-lg font-headline-lg text-on-surface">88%</p>
</div>
</div>
<!-- Data Section (Tables) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
<!-- Table 1: Derniers inscrits -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col">
<div class="p-6 border-b border-surface-container-high flex justify-between items-center">
<h2 class="text-headline-md font-headline-md text-on-surface">Derniers inscrits</h2>
<button class="text-primary hover:text-primary-container text-label-sm font-label-sm transition-colors">Voir tout</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low text-label-sm font-label-sm text-secondary uppercase tracking-wider">
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Date</th>
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Nom de l'étudiant</th>
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Filière</th>
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Statut</th>
</tr>
</thead>
<tbody class="text-body-sm font-body-sm text-on-surface divide-y divide-surface-container-high">
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-secondary">12 Oct 2023</td>
<td class="px-6 py-4 whitespace-nowrap font-medium">Sophie Martin</td>
<td class="px-6 py-4 whitespace-nowrap">Finance</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-fixed text-on-primary-fixed-variant">
                                                Confirmé
                                            </span>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-secondary">11 Oct 2023</td>
<td class="px-6 py-4 whitespace-nowrap font-medium">Lucas Dubois</td>
<td class="px-6 py-4 whitespace-nowrap">Marketing</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-container text-on-secondary-container">
                                                En attente
                                            </span>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-secondary">10 Oct 2023</td>
<td class="px-6 py-4 whitespace-nowrap font-medium">Emma Bernard</td>
<td class="px-6 py-4 whitespace-nowrap">Management</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-fixed text-on-primary-fixed-variant">
                                                Confirmé
                                            </span>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-secondary">09 Oct 2023</td>
<td class="px-6 py-4 whitespace-nowrap font-medium">Hugo Petit</td>
<td class="px-6 py-4 whitespace-nowrap">IT Business</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-fixed text-on-primary-fixed-variant">
                                                Confirmé
                                            </span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Table 2: Dernières notes -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col">
<div class="p-6 border-b border-surface-container-high flex justify-between items-center">
<h2 class="text-headline-md font-headline-md text-on-surface">Dernières notes</h2>
<button class="text-primary hover:text-primary-container text-label-sm font-label-sm transition-colors">Voir tout</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low text-label-sm font-label-sm text-secondary uppercase tracking-wider">
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Étudiant</th>
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Module</th>
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Note</th>
<th class="px-6 py-4 font-medium border-b border-surface-container-high">Date</th>
</tr>
</thead>
<tbody class="text-body-sm font-body-sm text-on-surface divide-y divide-surface-container-high">
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap font-medium">Antoine Leroy</td>
<td class="px-6 py-4 whitespace-nowrap">Macroéconomie</td>
<td class="px-6 py-4 whitespace-nowrap font-bold text-primary">16/20</td>
<td class="px-6 py-4 whitespace-nowrap text-secondary">Aujourd'hui</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap font-medium">Camille Moreau</td>
<td class="px-6 py-4 whitespace-nowrap">Droit des affaires</td>
<td class="px-6 py-4 whitespace-nowrap font-bold text-primary">14.5/20</td>
<td class="px-6 py-4 whitespace-nowrap text-secondary">Hier</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap font-medium">Thomas Simon</td>
<td class="px-6 py-4 whitespace-nowrap">Stratégie Marketing</td>
<td class="px-6 py-4 whitespace-nowrap font-bold text-on-surface-variant">11/20</td>
<td class="px-6 py-4 whitespace-nowrap text-secondary">10 Oct 2023</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-6 py-4 whitespace-nowrap font-medium">Léa Laurent</td>
<td class="px-6 py-4 whitespace-nowrap">Comptabilité</td>
<td class="px-6 py-4 whitespace-nowrap font-bold text-primary">18/20</td>
<td class="px-6 py-4 whitespace-nowrap text-secondary">08 Oct 2023</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>