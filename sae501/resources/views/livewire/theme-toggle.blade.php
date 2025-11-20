<div x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
     x-init="$watch('darkMode', val => {
         localStorage.setItem('theme', val ? 'dark' : 'light');
         if (val) {
             document.documentElement.classList.add('dark');
         } else {
             document.documentElement.classList.remove('dark');
         }
     });
     if (darkMode) {
         document.documentElement.classList.add('dark');
     }"
     class="flex items-center">
    <button
        @click="darkMode = !darkMode"
        class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 dark:bg-dark-card shadow-md hover:bg-primary/80 hover:text-white dark:hover:bg-primary/90 text-secondary dark:text-dark-text transition"
        title="Changer de thème"
    >
        <i :class="darkMode ? 'bi-sun-fill' : 'bi-moon-fill'" class="text-2xl"></i>
    </button>
</div>
