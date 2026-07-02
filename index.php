<!DOCTYPE html>

<html lang="fr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Connexion - SupTech Business School</title>
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
                        "surface-bright": "#f7fafc",
                        "surface-dim": "#d7dadc",
                        "background": "#f7fafc",
                        "secondary-fixed": "#dde3eb",
                        "secondary": "#595f66",
                        "secondary-container": "#dde3eb",
                        "error": "#ba1a1a",
                        "surface-container": "#ebeef0",
                        "on-background": "#181c1e",
                        "on-error-container": "#93000a",
                        "inverse-primary": "#a2c9ff",
                        "tertiary-fixed": "#ffdcbd",
                        "surface-variant": "#e0e3e5",
                        "on-error": "#ffffff",
                        "on-primary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "outline-variant": "#c1c7d2",
                        "inverse-surface": "#2d3133",
                        "on-tertiary-container": "#ffe7d2",
                        "surface": "#f7fafc",
                        "on-primary-fixed-variant": "#004881",
                        "on-primary-container": "#e1ecff",
                        "on-secondary-container": "#5f656c",
                        "on-surface": "#181c1e",
                        "on-secondary-fixed": "#161c22",
                        "primary": "#005394",
                        "primary-fixed-dim": "#a2c9ff",
                        "on-secondary": "#ffffff",
                        "outline": "#727782",
                        "error-container": "#ffdad6",
                        "on-primary-fixed": "#001c38",
                        "secondary-fixed-dim": "#c1c7cf",
                        "surface-container-low": "#f1f4f6",
                        "tertiary-container": "#9a5b00",
                        "primary-container": "#2b6cb0",
                        "on-secondary-fixed-variant": "#41474e",
                        "surface-container-high": "#e5e9eb",
                        "inverse-on-surface": "#eef1f3",
                        "surface-tint": "#1960a3",
                        "surface-container-highest": "#e0e3e5",
                        "on-surface-variant": "#414750",
                        "on-tertiary-fixed": "#2c1600",
                        "tertiary": "#784600",
                        "on-tertiary": "#ffffff",
                        "primary-fixed": "#d3e4ff",
                        "tertiary-fixed-dim": "#ffb86e",
                        "on-tertiary-fixed-variant": "#693c00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "container-max": "1280px",
                        "base": "8px",
                        "gutter": "24px",
                        "margin-desktop": "40px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-xl": ["Inter"],
                        "body-sm": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-xl": ["36px", {"lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "fontWeight": "600"}],
                        "headline-lg": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Custom minimal shadow matching Level 1 guidelines */
        .shadow-ambient {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        /* Focus ring styling for inputs */
        .input-focus-ring:focus {
            border-color: #2b6cb0;
            box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2);
            outline: none;
        }
    </style>
</head>
<body class="bg-surface-bright min-h-screen flex items-center justify-center font-body-md text-on-surface p-margin-mobile md:p-margin-desktop">
<!-- Login Container -->
<main class="w-full max-w-[440px] mx-auto bg-surface-container-lowest rounded-xl shadow-ambient p-8 md:p-12 relative overflow-hidden">
<!-- Subtle top decorative accent -->
<div class="absolute top-0 left-0 w-full h-1 bg-primary-container"></div>
<!-- Header -->
<div class="text-center mb-10">
<h1 class="font-headline-xl text-headline-xl text-primary mb-2 tracking-tight">
                SupTech Business School
            </h1>
<p class="font-body-md text-body-md text-secondary">
                Portail d'accès étudiant et administration
            </p>
</div>
<!-- Login Form -->
<form action="#" class="space-y-6" method="POST">
<!-- Email / Matricule Input -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface" for="identifier">
                    Email ou Matricule
                </label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
<span class="material-symbols-outlined text-[20px]">person</span>
</div>
<input class="w-full pl-10 pr-4 py-3 bg-surface-bright border border-outline-variant rounded transition-all duration-200 input-focus-ring font-body-md text-body-md text-on-surface" id="identifier" name="identifier" placeholder="Saisissez votre identifiant" required="" type="text"/>
</div>
</div>
<!-- Password Input -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface" for="password">
                    Mot de passe
                </label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
<span class="material-symbols-outlined text-[20px]">lock</span>
</div>
<input class="w-full pl-10 pr-10 py-3 bg-surface-bright border border-outline-variant rounded transition-all duration-200 input-focus-ring font-body-md text-body-md text-on-surface" id="password" name="password" placeholder="••••••••" required="" type="password"/>
<button aria-label="Afficher le mot de passe" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-primary transition-colors focus:outline-none" type="button">
<span class="material-symbols-outlined text-[20px]">visibility_off</span>
</button>
</div>
</div>
<!-- Action Area -->
<div class="pt-4 space-y-6">
<button class="w-full bg-primary-container text-on-primary py-3 px-4 rounded font-label-md text-label-md hover:bg-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 flex items-center justify-center gap-2" type="submit">
                    Se connecter
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</button>
<div class="text-center">
<a class="font-body-sm text-body-sm text-primary hover:text-primary-container transition-colors inline-block underline-offset-4 hover:underline" href="#">
                        Mot de passe oublié ?
                    </a>
</div>
</div>
</form>
</main>
</body></html>