import purgecss from '@fullhuman/postcss-purgecss';
import autoprefixer from 'autoprefixer';

export default {
    plugins: [
        autoprefixer(),
        purgecss({
            // 1. Lokasi file tempat Anda menulis nama class CSS
            content: [
                './resources/**/*.blade.php',
                './resources/**/*.js',
                './resources/**/*.vue', // Hapus baris ini jika tidak memakai Vue
            ],
            // 2. Safelist: Melindungi class Bootstrap dari penghapusan
            safelist: {
                standard: [
                    'show',
                    'active',
                    'fade',
                    'collapse',
                    'collapsing',
                    'modal-backdrop'
                ],
                deep: [
                    /^bs-/,          // Class bawaan Bootstrap 5
                    /^modal-/,       // Semua variasi class modal
                    /^dropdown-/,    // Semua variasi class dropdown
                    /^offcanvas-/,   // Jika Anda pakai sidebar offcanvas
                    /^carousel-/,    // Jika Anda pakai slider
                    /^toast-/        // Jika Anda pakai notifikasi toast
                ]
            },
            // 3. Extractor: Membantu PurgeCSS membaca karakter khusus
            defaultExtractor: (content) => content.match(/[\w-/:]+(?<!:)/g) || []
        })
    ]
};