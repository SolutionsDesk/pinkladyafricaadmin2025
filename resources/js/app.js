import './bootstrap';

import Alpine from 'alpinejs';

// Import Material Tailwind
import { initMaterialTailwind } from '@material-tailwind/html';

window.Alpine = Alpine;

// Initialize both libraries
initMaterialTailwind();
Alpine.start();
