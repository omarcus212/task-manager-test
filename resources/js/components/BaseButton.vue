<!--
  <BaseButton variant="primary" @click="save">Salvar</BaseButton>
-->

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="[
            'inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl font-bold transition-all active:scale-95',
            'focus:ring-2 focus:ring-blue-500/20 focus:outline-none',
            // Variantes
            variant === 'primary' &&
                'bg-blue-600 text-white shadow-md shadow-blue-200 hover:bg-blue-700',
            variant === 'secondary' &&
                'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50',
            variant === 'danger' &&
                'bg-rose-500 text-white shadow-md shadow-rose-100 hover:bg-rose-600',
            variant === 'ghost' &&
                'text-slate-500 hover:bg-slate-100 hover:text-slate-900',
            // Tamanhos
            size === 'sm' && 'px-3 py-1.5 text-sm',
            size === 'md' && 'px-4 py-2 text-base',
            size === 'lg' && 'px-6 py-3 text-lg',
            // Desabilitado
            (disabled || loading) && 'cursor-not-allowed opacity-50',
        ]"
        @click="$emit('click', $event)"
    >
        <!-- Ícone Loading -->
        <i v-if="loading" class="pi pi-spinner animate-spin"></i>

        <!-- Ícone Normal -->
        <i v-else-if="icon" :class="`pi ${icon}`"></i>

        <!-- Texto -->
        <span><slot /></span>
    </button>
</template>

<script setup>
/**
 * BaseButton - Componente de botão reutilizável
 */
defineProps({
    variant: {
        type: String,
        default: 'primary', // primary, secondary, danger, ghost
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
    type: {
        type: String,
        default: 'button', // button, submit, reset
    },
    icon: {
        type: String,
        default: null, // ex: 'pi-plus', 'pi-pencil'
    },
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

// Events - eventos que o componente emite
defineEmits(['click']);
</script>
