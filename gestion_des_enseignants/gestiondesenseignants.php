<!DOCTYPE html>

<html class="light" lang="fr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Gestion des enseignants - ISM Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-error-container": "#93000a",
                        "secondary": "#006c49",
                        "inverse-primary": "#b6c4ff",
                        "on-tertiary-container": "#ff8f4f",
                        "on-background": "#0b1c30",
                        "on-tertiary-fixed": "#341100",
                        "error-container": "#ffdad6",
                        "on-primary-container": "#90a8ff",
                        "primary": "#00236f",
                        "background": "#f8f9ff",
                        "surface-variant": "#d3e4fe",
                        "on-secondary-container": "#00714d",
                        "surface": "#f8f9ff",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed-dim": "#4edea3",
                        "on-surface": "#0b1c30",
                        "outline": "#757682",
                        "inverse-surface": "#213145",
                        "on-primary-fixed-variant": "#264191",
                        "tertiary-fixed": "#ffdbca",
                        "on-error": "#ffffff",
                        "surface-bright": "#f8f9ff",
                        "secondary-fixed": "#6ffbbe",
                        "primary-fixed-dim": "#b6c4ff",
                        "surface-container": "#e5eeff",
                        "tertiary-container": "#6e2d00",
                        "tertiary": "#4b1c00",
                        "on-primary-fixed": "#00164e",
                        "tertiary-fixed-dim": "#ffb690",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#4059aa",
                        "outline-variant": "#c5c5d3",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-container-low": "#eff4ff",
                        "on-surface-variant": "#444651",
                        "primary-fixed": "#dce1ff",
                        "on-tertiary": "#ffffff",
                        "error": "#ba1a1a",
                        "on-secondary-fixed": "#002113",
                        "secondary-container": "#6cf8bb",
                        "primary-container": "#1e3a8a",
                        "on-primary": "#ffffff",
                        "surface-container-high": "#dce9ff",
                        "on-secondary-fixed-variant": "#005236",
                        "on-tertiary-fixed-variant": "#783200",
                        "surface-dim": "#cbdbf5",
                        "surface-container-highest": "#d3e4fe"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "header-height": "72px",
                        "card-gap": "1rem",
                        "sidebar-width": "280px",
                        "gutter": "1.5rem",
                        "container-padding": "2rem"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "body-md": ["Inter"],
                        "body-sm": ["Inter"],
                        "display-lg": ["Inter"],
                        "sidebar-link": ["Inter"],
                        "label-caps": ["Inter"],
                        "title-sm": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "display-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "sidebar-link": ["15px", { "lineHeight": "24px", "fontWeight": "500" }],
                        "label-caps": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "title-sm": ["18px", { "lineHeight": "24px", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F8FAFC] text-on-surface font-body-md min-h-screen">
<!-- TopNavBar -->
<header class="fixed top-0 right-0 w-full md:w-[calc(100%-280px)] h-header-height bg-surface-container-lowest border-b border-outline-variant shadow-sm z-40 flex justify-between items-center px-gutter">
<div class="flex items-center gap-4">
<!-- Mobile Menu Toggle -->
<button class="md:hidden text-on-surface-variant hover:bg-surface-variant/50 p-2 rounded-full transition-colors" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<div class="relative w-64 md:w-96">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
<input class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-lg bg-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors text-body-sm" placeholder="Rechercher..." type="text"/>
</div>
</div>
<div class="flex items-center gap-6">
<button class="text-on-surface-variant hover:bg-surface-variant/50 p-2 rounded-full transition-colors relative group">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full border border-surface-container-lowest"></span>
</button>
<div class="flex items-center gap-3 cursor-pointer group">
<div class="text-right hidden sm:block">
<p class="font-title-sm text-title-sm text-on-surface">Aminata Fall</p>
<p class="font-body-sm text-body-sm text-on-surface-variant">Administrateur</p>
</div>
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-outline-variant group-hover:border-primary transition-colors">
<img alt="Avatar de l'administrateur" class="w-full h-full object-cover" data-alt="A professional headshot of a Senegalese woman in her 30s. She is smiling subtly, wearing smart business attire. The lighting is soft and corporate. The background is a clean, slightly blurred modern office environment. The overall mood is confident and administrative." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8438X2cQXRKsg7cy8eMNenXHG5Xzg8zgw8Q92LcPQewKAv8BIKYuTaXCGJZm46VCfq9NCxzEd8Fo1uFcRpi-jnu-mp7z5vqMvHWd_SbN6TnJxwhgdToM0lGkX35WyeF3QqPiQ9LwYAJ6g2_hckogC6_Sp5Qrpe0FOIVht3irOLH_b_F2kKgDG_ZtMsGGv0Ii5ruohAba1yw01Vq3s82myaw85cte58lKm22PSJAbEXT0HJODMiPjg6Q"/>
</div>
</div>
</div>
</header>
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-screen w-sidebar-width bg-primary border-r border-outline-variant shadow-sm z-50 flex flex-col transition-transform duration-300 md:translate-x-0 -translate-x-full" id="sidebar">
<!-- Brand / Header -->
<div class="h-header-height flex items-center px-6 border-b border-white/10">
<img alt="Logo de l'Institut" class="h-10 object-contain" src="https://lh3.googleusercontent.com/aida/AP1WRLtzoZ2rcpMxMScovgaHRyc7GYXZ38veD2Icj5ryxozctkkqhQgiVxtyGP3X3oUVUx665NG_e-7F3cQxWJFoxm1DxKC5kz597JAPjIPkOFtdvQDvcXzFYjRi8-_UOKz5efA5xZSUR1zHF0CH5X1b3PY-vwvhMFhMN31PMuQEjmDm4yCbDlIy5zpvq0CLObjS7CwKt7pLVkeKKXzNt4faSKXdsdR-dQRaclSR4QH20G7KZxp76JlG1XnWbi24"/>
</div>
<!-- Navigation Tabs -->
<nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1 px-3">
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-sidebar-link text-sidebar-link">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
<span class="font-sidebar-link text-sidebar-link">Étudiants</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-[#10B981] bg-on-primary/10 text-on-primary font-bold rounded-r-lg" href="#">
<span class="material-symbols-outlined" data-icon="school" data-weight="fill" style="font-variation-settings: 'FILL' 1;">school</span>
<span class="font-sidebar-link text-sidebar-link">Enseignants</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="class">class</span>
<span class="font-sidebar-link text-sidebar-link">Classes</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="book">book</span>
<span class="font-sidebar-link text-sidebar-link">Modules</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="person_add">person_add</span>
<span class="font-sidebar-link text-sidebar-link">Inscriptions</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="payments">payments</span>
<span class="font-sidebar-link text-sidebar-link">Paiements</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="description">description</span>
<span class="font-sidebar-link text-sidebar-link">Notes</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
<span class="font-sidebar-link text-sidebar-link">Emploi du temps</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="query_stats">query_stats</span>
<span class="font-sidebar-link text-sidebar-link">Statistiques</span>
</a>
</nav>
<!-- Footer Tab -->
<div class="p-4 border-t border-white/10 mt-auto">
<a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/10 rounded-lg transition-colors duration-200" href="#">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-sidebar-link text-sidebar-link">Déconnexion</span>
</a>
</div>
</aside>
<!-- Main Content -->
<main class="md:ml-[280px] pt-[72px] min-h-screen p-container-padding">
<!-- Page Header & Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
<div>
<h1 class="font-headline-md text-headline-md text-on-background">Gestion des Enseignants</h1>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Gérez le corps professoral, leurs spécialités et affectations.</p>
</div>
<button class="inline-flex items-center gap-2 bg-[#1E3A8A] text-white px-5 py-2.5 rounded-lg hover:-translate-y-0.5 transition-all shadow-sm font-title-sm text-title-sm">
<span class="material-symbols-outlined text-[20px]" data-icon="add">add</span>
                Ajouter un enseignant
            </button>
</div>
<!-- KPI Cards (Bento style) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-card-gap mb-8">
<div class="bg-surface-container-lowest rounded-xl p-6 border border-[#E2E8F0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-transform">
<div class="flex items-center justify-between">
<div>
<p class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-wider">Total Enseignants</p>
<h2 class="font-display-lg text-display-lg text-on-background mt-2">124</h2>
</div>
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined" data-icon="school">school</span>
</div>
</div>
</div>
<div class="bg-surface-container-lowest rounded-xl p-6 border border-[#E2E8F0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-transform">
<div class="flex items-center justify-between">
<div>
<p class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-wider">Docteurs</p>
<h2 class="font-display-lg text-display-lg text-on-background mt-2">45</h2>
</div>
<div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined" data-icon="workspace_premium">workspace_premium</span>
</div>
</div>
</div>
<div class="bg-surface-container-lowest rounded-xl p-6 border border-[#E2E8F0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-transform">
<div class="flex items-center justify-between">
<div>
<p class="font-body-sm text-body-sm text-on-surface-variant uppercase tracking-wider">En cours d'activité</p>
<h2 class="font-display-lg text-display-lg text-on-background mt-2">112</h2>
</div>
<div class="w-12 h-12 rounded-full bg-tertiary/10 flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined" data-icon="event_available">event_available</span>
</div>
</div>
</div>
</div>
<!-- Data Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-[#E2E8F0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] overflow-hidden">
<!-- Table Header Actions -->
<div class="p-4 border-b border-[#E2E8F0] flex flex-col sm:flex-row justify-between items-center gap-4 bg-[#F8FAFC]">
<div class="relative w-full sm:w-64">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]" data-icon="filter_list">filter_list</span>
<select class="w-full pl-10 pr-4 py-2 border border-[#CBD5E1] rounded-md bg-white focus:outline-none focus:border-[#1E3A8A] focus:ring-1 focus:ring-[#1E3A8A] transition-colors text-body-sm appearance-none">
<option>Toutes les spécialités</option>
<option>Génie Logiciel</option>
<option>Réseaux &amp; Télécoms</option>
<option>Management</option>
</select>
</div>
</div>
<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-[#F1F5F9] border-b border-[#E2E8F0]">
<th class="py-3 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase">Matricule</th>
<th class="py-3 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase">Enseignant</th>
<th class="py-3 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase">Grade</th>
<th class="py-3 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase">Spécialité</th>
<th class="py-3 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase">Contact</th>
<th class="py-3 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-[#E2E8F0]">
<tr class="hover:bg-[#F8FAFC] transition-colors group">
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">ENS-2023-001</td>
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-title-sm text-title-sm">AF</div>
<div>
<p class="font-title-sm text-title-sm text-on-surface">Fall, Aminata</p>
</div>
</div>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Docteur
                                </span>
</td>
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">Génie Logiciel</td>
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">+221 77 123 45 67</td>
<td class="py-4 px-6 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-slate-500 hover:text-[#1E3A8A] hover:bg-[#1E3A8A]/10 rounded transition-colors" title="Voir">
<span class="material-symbols-outlined text-[20px]" data-icon="visibility">visibility</span>
</button>
<button class="p-1.5 text-slate-500 hover:text-[#1E3A8A] hover:bg-[#1E3A8A]/10 rounded transition-colors" title="Modifier">
<span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
</button>
</div>
</td>
</tr>
<tr class="hover:bg-[#F8FAFC] transition-colors group">
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">ENS-2021-042</td>
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-title-sm text-title-sm">IG</div>
<div>
<p class="font-title-sm text-title-sm text-on-surface">Gueye, Ibrahima</p>
</div>
</div>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Master
                                </span>
</td>
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">Réseaux &amp; Sécurité</td>
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">+221 76 987 65 43</td>
<td class="py-4 px-6 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-slate-500 hover:text-[#1E3A8A] hover:bg-[#1E3A8A]/10 rounded transition-colors" title="Voir">
<span class="material-symbols-outlined text-[20px]" data-icon="visibility">visibility</span>
</button>
<button class="p-1.5 text-slate-500 hover:text-[#1E3A8A] hover:bg-[#1E3A8A]/10 rounded transition-colors" title="Modifier">
<span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
</button>
</div>
</td>
</tr>
<tr class="hover:bg-[#F8FAFC] transition-colors group">
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">ENS-2022-015</td>
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-tertiary/10 text-tertiary flex items-center justify-center font-title-sm text-title-sm">KS</div>
<div>
<p class="font-title-sm text-title-sm text-on-surface">Sow, Khadija</p>
</div>
</div>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Docteur
                                </span>
</td>
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">Intelligence Artificielle</td>
<td class="py-4 px-6 font-body-sm text-body-sm text-on-surface-variant">+221 70 456 78 90</td>
<td class="py-4 px-6 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-slate-500 hover:text-[#1E3A8A] hover:bg-[#1E3A8A]/10 rounded transition-colors" title="Voir">
<span class="material-symbols-outlined text-[20px]" data-icon="visibility">visibility</span>
</button>
<button class="p-1.5 text-slate-500 hover:text-[#1E3A8A] hover:bg-[#1E3A8A]/10 rounded transition-colors" title="Modifier">
<span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="p-4 border-t border-[#E2E8F0] flex items-center justify-between text-body-sm text-on-surface-variant">
<span>Affichage de 1 à 3 sur 124 enseignants</span>
<div class="flex items-center gap-1">
<button class="px-3 py-1 border border-[#CBD5E1] rounded hover:bg-[#F1F5F9] transition-colors disabled:opacity-50" disabled="">Précédent</button>
<button class="px-3 py-1 bg-[#1E3A8A] text-white rounded">1</button>
<button class="px-3 py-1 border border-[#CBD5E1] rounded hover:bg-[#F1F5F9] transition-colors">2</button>
<button class="px-3 py-1 border border-[#CBD5E1] rounded hover:bg-[#F1F5F9] transition-colors">3</button>
<button class="px-3 py-1 border border-[#CBD5E1] rounded hover:bg-[#F1F5F9] transition-colors">Suivant</button>
</div>
</div>
</div>
</main>
</body></html>