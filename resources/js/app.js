import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'

// Register plugins
Alpine.plugin(intersect)

// Make Alpine available globally
window.Alpine = Alpine

// Start Alpine
Alpine.start()