import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'

// Register plugins
Alpine.plugin(intersect)
Alpine.plugin(collapse)

// Make Alpine available globally
window.Alpine = Alpine

// Start Alpine
Alpine.start()