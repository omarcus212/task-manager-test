<!--
  <BaseBadge variant="success">Ativo</BaseBadge>
-->

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
            // Variantes
            variant === 'success' && 'bg-green-100 text-green-700',
            variant === 'warning' && 'bg-yellow-100 text-yellow-800',
            variant === 'danger' && 'bg-red-100 text-red-700',
            variant === 'info' && 'bg-blue-100 text-blue-700',
            variant === 'gray' && 'bg-gray-100 text-gray-700',
        ]"
    >
        <!-- Ícone opcional -->
        <i v-if="icon" :class="`pi ${icon}`"></i>

        <!-- Texto -->
        <slot />
    </span>
</template>

<script setup>
// Props
defineProps({
    variant: {
        type: String,
        default: 'gray', // success, warning, danger, info, gray
    },
    icon: {
        type: String,
        default: null, // ex: 'pi-check', 'pi-exclamation'
    },
});
</script>
