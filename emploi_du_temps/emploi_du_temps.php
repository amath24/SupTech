<!DOCTYPE html>

<html class="light" lang="fr">



<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Emploi du temps - ISM Management</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "600"
                        }],
                        "body-md": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "body-sm": ["14px", {
                            "lineHeight": "20px",
                            "fontWeight": "400"
                        }],
                        "display-lg": ["32px", {
                            "lineHeight": "40px",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "sidebar-link": ["15px", {
                            "lineHeight": "24px",
                            "fontWeight": "500"
                        }],
                        "label-caps": ["12px", {
                            "lineHeight": "16px",
                            "letterSpacing": "0.05em",
                            "fontWeight": "600"
                        }],
                        "title-sm": ["18px", {
                            "lineHeight": "24px",
                            "fontWeight": "600"
                        }]
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background text-on-background font-body-md antialiased overflow-hidden">
    <!-- Top Navigation Bar -->
    <header class="bg-surface-container-lowest text-on-surface font-body-md shadow-sm fixed top-0 right-0 w-[calc(100%-theme(spacing.sidebar-width))] h-header-height z-40 border-b border-outline-variant flex justify-between items-center px-gutter hidden md:flex">
        <div class="flex items-center">
            <div class="relative w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">search</span>
                <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-body-sm transition-shadow" placeholder="Rechercher..." type="text" />
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="relative p-2 text-on-surface-variant hover:bg-surface-variant/50 rounded-full transition-colors group">
                <span class="material-symbols-outlined" data-icon="notifications" style="font-variation-settings: 'FILL' 0;">notifications</span>
                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface-container-lowest"></span>
            </button>
            <div class="h-6 w-px bg-outline-variant mx-2"></div>
            <div class="flex items-center gap-3 cursor-pointer group hover:bg-surface-variant/50 p-2 rounded-lg transition-colors">
                <div class="text-right hidden lg:block">
                    <p class="font-title-sm text-title-sm text-on-surface">Aminata Sow</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Profil Utilisateur</p>
                </div>
                <img alt="Avatar de l'administrateur" class="w-10 h-10 rounded-full object-cover border border-outline-variant group-hover:border-primary transition-colors" data-alt="Professional headshot portrait of a confident Senegalese academic administrator, female, wearing business casual attire. Bright, natural lighting, soft depth of field background typical of a modern corporate setting. Colors are neutral with subtle cool tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDMrGeEi17s6AxvyGNK2i-5qtoLCeckzF2LgqcvxxpLTjhgRzLQVsBAG-7BQJMDbEfceFOziDP2tRPx13THv3zS-akgEX2klT5iMUGmVbJBVYpzzYwgml73OtQIEGB4RZLvK0MQRDGlruhqwKbSOxzr1VnIdYZZco0rzuNnDgShD-Fsl1fxMRjrOkoXIL_zMjRY5SDa-q3Ua2HLJsAQ-vNRxkHczijkAY7h2EWiTbo3PMUEFddL3rIWuQ" />
            </div>
        </div>
    </header>
    <!-- Side Navigation Bar -->
    <nav class="bg-primary text-on-primary font-sidebar-link shadow-sm fixed left-0 top-0 h-screen w-sidebar-width border-r border-outline-variant z-50 flex flex-col hidden md:flex">
        <!-- Brand/Header -->
        <div class="p-6 border-b border-on-primary/10">
            <img alt="Logo de l'Institut" class="h-10 w-auto mb-4" src="https://lh3.googleusercontent.com/aida/AP1WRLtzoZ2rcpMxMScovgaHRyc7GYXZ38veD2Icj5ryxozctkkqhQgiVxtyGP3X3oUVUx665NG_e-7F3cQxWJFoxm1DxKC5kz597JAPjIPkOFtdvQDvcXzFYjRi8-_UOKz5efA5xZSUR1zHF0CH5X1b3PY-vwvhMFhMN31PMuQEjmDm4yCbDlIy5zpvq0CLObjS7CwKt7pLVkeKKXzNt4faSKXdsdR-dQRaclSR4QH20G7KZxp76JlG1XnWbi24" />
            <div class="text-on-primary opacity-80 text-body-sm mt-1">Portail Administratif</div>
        </div>
        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="dashboard" style="font-variation-settings: 'FILL' 0;">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="groups" style="font-variation-settings: 'FILL' 0;">groups</span>
                        <span>Étudiants</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="school" style="font-variation-settings: 'FILL' 0;">school</span>
                        <span>Enseignants</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="class" style="font-variation-settings: 'FILL' 0;">class</span>
                        <span>Classes</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="book" style="font-variation-settings: 'FILL' 0;">book</span>
                        <span>Modules</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="person_add" style="font-variation-settings: 'FILL' 0;">person_add</span>
                        <span>Inscriptions</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="payments" style="font-variation-settings: 'FILL' 0;">payments</span>
                        <span>Paiements</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="description" style="font-variation-settings: 'FILL' 0;">description</span>
                        <span>Notes</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 border-l-4 border-secondary-fixed bg-on-primary/10 text-on-primary font-bold opacity-90 transition-all rounded-r-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="calendar_today" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                        <span>Emploi du temps</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/5 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg" href="#">
                        <span class="material-symbols-outlined" data-icon="query_stats" style="font-variation-settings: 'FILL' 0;">query_stats</span>
                        <span>Statistiques</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Footer / Logout -->
        <div class="p-4 border-t border-on-primary/10">
            <a class="flex items-center gap-3 px-4 py-3 text-on-primary/80 hover:bg-on-primary/10 transition-colors duration-200 rounded-lg w-full text-left" href="#">
                <span class="material-symbols-outlined" data-icon="logout" style="font-variation-settings: 'FILL' 0;">logout</span>
                <span>Déconnexion</span>
            </a>
        </div>
    </nav>
    <!-- Main Content Area -->
    <main class="md:ml-[280px] pt-header-height h-screen overflow-y-auto bg-background">
        <div class="p-container-padding max-w-7xl mx-auto space-y-6">
            <!-- Page Header & Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="font-display-lg text-display-lg text-on-background">Emploi du temps</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">Gestion hebdomadaire des cours et ressources</p>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Filters -->
                    <div class="relative hidden sm:block">
                        <select class="appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-4 pr-10 rounded-DEFAULT focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm font-body-sm cursor-pointer">
                            <option>Master 1 - Informatique</option>
                            <option>Licence 3 - Gestion</option>
                            <option>Master 2 - Finance</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                    </div>
                    <div class="relative hidden sm:block">
                        <select class="appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-4 pr-10 rounded-DEFAULT focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm font-body-sm cursor-pointer">
                            <option>Semaine 42 (16 Oct - 21 Oct)</option>
                            <option>Semaine 43 (23 Oct - 28 Oct)</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                    </div>
                    <!-- Add Button -->
                    <button class="flex-1 sm:flex-none bg-primary text-on-primary px-4 py-2.5 rounded-DEFAULT font-title-sm text-title-sm flex items-center justify-center gap-2 hover:bg-primary-container hover:-translate-y-0.5 transition-all shadow-sm">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add</span>
                        Ajouter une séance
                    </button>
                </div>
            </div>
            <!-- Conflict Alert Panel (Glassmorphism inspired, embedded) -->
            <div class="bg-error-container/80 backdrop-blur-md border border-error/20 rounded-lg p-4 flex items-start gap-4 shadow-sm relative overflow-hidden">
                <div class="absolute inset-y-0 left-0 w-1 bg-error"></div>
                <div class="p-2 bg-error/10 rounded-full text-error shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">warning</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-title-sm text-title-sm text-on-error-container">Conflit d'horaire détecté</h4>
                    <p class="font-body-sm text-body-sm text-on-error-container/80 mt-1">
                        Le Professeur <strong>Dr. Sow</strong> est assigné à deux cours simultanés le Mercredi à 10h00. Veuillez résoudre ce conflit pour valider l'emploi du temps.
                    </p>
                </div>
                <button class="px-3 py-1.5 bg-error text-on-error font-body-sm text-body-sm rounded hover:bg-error/90 transition-colors shrink-0">
                    Résoudre
                </button>
            </div>
            <!-- Calendar Bento Grid Container -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col h-[calc(100vh-280px)] min-h-[600px]">
                <!-- Calendar Header (Days) -->
                <div class="grid grid-cols-7 border-b border-outline-variant bg-surface-container-low font-label-caps text-label-caps text-on-surface-variant uppercase text-center">
                    <div class="py-3 border-r border-outline-variant w-16 md:w-20 shrink-0">Heure</div>
                    <div class="py-3 border-r border-outline-variant flex-1"><span class="block md:inline">Lun</span><span class="hidden md:inline">di</span> 16</div>
                    <div class="py-3 border-r border-outline-variant flex-1"><span class="block md:inline">Mar</span><span class="hidden md:inline">di</span> 17</div>
                    <div class="py-3 border-r border-outline-variant flex-1"><span class="block md:inline">Mer</span><span class="hidden md:inline">credi</span> 18</div>
                    <div class="py-3 border-r border-outline-variant flex-1"><span class="block md:inline">Jeu</span><span class="hidden md:inline">di</span> 19</div>
                    <div class="py-3 border-r border-outline-variant flex-1"><span class="block md:inline">Ven</span><span class="hidden md:inline">dredi</span> 20</div>
                    <div class="py-3 flex-1"><span class="block md:inline">Sam</span><span class="hidden md:inline">edi</span> 21</div>
                </div>
                <!-- Calendar Body (Scrollable) -->
                <div class="flex-1 overflow-y-auto overflow-x-hidden relative">
                    <!-- Background Grid -->
                    <div class="absolute inset-0 grid grid-cols-7 pointer-events-none">
                        <div class="border-r border-outline-variant w-16 md:w-20 shrink-0 bg-surface-container-lowest"></div>
                        <div class="border-r border-outline-variant flex-1"></div>
                        <div class="border-r border-outline-variant flex-1"></div>
                        <div class="border-r border-outline-variant flex-1"></div>
                        <div class="border-r border-outline-variant flex-1"></div>
                        <div class="border-r border-outline-variant flex-1"></div>
                        <div class="flex-1"></div>
                    </div>
                    <!-- Time Slots (Rows) -->
                    <div class="relative w-full h-[1000px]"> <!-- Set arbitrary height for demonstration of absolute positioning -->
                        <!-- Horizontal Grid Lines & Time Labels -->
                        <!-- 08:00 -->
                        <div class="absolute top-[0px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">08h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 09:00 -->
                        <div class="absolute top-[100px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">09h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 10:00 -->
                        <div class="absolute top-[200px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">10h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 11:00 -->
                        <div class="absolute top-[300px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">11h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 12:00 -->
                        <div class="absolute top-[400px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">12h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 13:00 (Pause) -->
                        <div class="absolute top-[500px] left-0 w-full flex items-center pointer-events-none bg-surface-container-low h-[100px] border-y border-outline-variant/30">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2 self-start mt-2">13h00</div>
                            <div class="flex-1 flex justify-center items-center">
                                <span class="font-body-md text-on-surface-variant/50 uppercase tracking-widest">Pause Déjeuner</span>
                            </div>
                        </div>
                        <!-- 14:00 -->
                        <div class="absolute top-[600px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">14h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 15:00 -->
                        <div class="absolute top-[700px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">15h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 16:00 -->
                        <div class="absolute top-[800px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">16h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 17:00 -->
                        <div class="absolute top-[900px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2">17h00</div>
                            <div class="flex-1 border-t border-outline-variant/50 group-hover:border-primary/30 transition-colors"></div>
                        </div>
                        <!-- 18:00 (End) -->
                        <div class="absolute top-[1000px] left-0 w-full flex items-center pointer-events-none group">
                            <div class="w-16 md:w-20 text-center font-label-caps text-label-caps text-on-surface-variant py-2 -mt-4">18h00</div>
                            <div class="flex-1 border-t border-outline-variant/80"></div>
                        </div>
                        <!-- EVENT BLOCKS -->
                        <!-- Lundi 08h-11h: PHP -->
                        <div class="absolute left-[calc(14.28%+4rem)] md:left-[calc(14.28%+5rem)] w-[calc(14.28%-4rem-4px)] md:w-[calc(14.28%-5rem-4px)] top-[10px] h-[280px] bg-primary-fixed border-l-4 border-primary rounded shadow-sm hover:-translate-y-1 hover:shadow-md transition-all cursor-pointer p-2 flex flex-col group z-10 mx-1">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-title-sm text-title-sm text-on-primary-fixed leading-tight">PHP Avancé</span>
                                <button class="opacity-0 group-hover:opacity-100 transition-opacity text-on-primary-fixed/70 hover:text-on-primary-fixed p-0.5">
                                    <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                </button>
                            </div>
                            <div class="font-body-sm text-body-sm text-on-primary-fixed-variant flex items-center gap-1 mb-2">
                                <span class="material-symbols-outlined text-[14px]">schedule</span> 08h00 - 11h00
                            </div>
                            <div class="mt-auto space-y-1">
                                <div class="font-body-sm text-body-sm text-on-primary-fixed-variant flex items-center gap-1 bg-surface-container-lowest/50 px-1.5 py-0.5 rounded w-max">
                                    <span class="material-symbols-outlined text-[14px]">person</span> Dr. Fall
                                </div>
                                <div class="font-label-caps text-label-caps text-on-primary-fixed-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">room</span> Salle C.A. Diop
                                </div>
                            </div>
                        </div>
                        <!-- Mardi 14h-17h: Base de données -->
                        <div class="absolute left-[calc(28.56%+0.5rem)] w-[calc(14.28%-1rem)] top-[610px] h-[280px] bg-secondary-container border-l-4 border-secondary rounded shadow-sm hover:-translate-y-1 hover:shadow-md transition-all cursor-pointer p-2 flex flex-col group z-10 mx-1">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-title-sm text-title-sm text-on-secondary-container leading-tight">Bases de données</span>
                            </div>
                            <div class="font-body-sm text-body-sm text-on-secondary-container/80 flex items-center gap-1 mb-2">
                                <span class="material-symbols-outlined text-[14px]">schedule</span> 14h00 - 17h00
                            </div>
                            <div class="mt-auto space-y-1">
                                <div class="font-body-sm text-body-sm text-on-secondary-container flex items-center gap-1 bg-surface-container-lowest/50 px-1.5 py-0.5 rounded w-max">
                                    <span class="material-symbols-outlined text-[14px]">person</span> Pr. Ndiaye
                                </div>
                                <div class="font-label-caps text-label-caps text-on-secondary-container flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">room</span> Amphi B
                                </div>
                            </div>
                        </div>
                        <!-- Mercredi 10h-12h: Conflit Block (Error State) -->
                        <div class="absolute left-[calc(42.84%+0.5rem)] w-[calc(14.28%-1rem)] top-[210px] h-[180px] bg-error-container border-l-4 border-error rounded shadow-sm hover:-translate-y-1 hover:shadow-md transition-all cursor-pointer p-2 flex flex-col group z-10 mx-1 animate-pulse" style="animation-duration: 3s;">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-title-sm text-title-sm text-on-error-container leading-tight">Gestion Projets</span>
                                <span class="material-symbols-outlined text-error text-[16px]">warning</span>
                            </div>
                            <div class="font-body-sm text-body-sm text-on-error-container/80 flex items-center gap-1 mb-2">
                                <span class="material-symbols-outlined text-[14px]">schedule</span> 10h00 - 12h00
                            </div>
                            <div class="mt-auto space-y-1">
                                <div class="font-body-sm text-body-sm text-on-error-container flex items-center gap-1 bg-surface-container-lowest/50 px-1.5 py-0.5 rounded w-max">
                                    <span class="material-symbols-outlined text-[14px]">person</span> Dr. Sow
                                </div>
                                <div class="font-label-caps text-label-caps text-on-error-container flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">room</span> Salle 102
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>