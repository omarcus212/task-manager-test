<!--
  <BaseCard><h3>Conteúdo</h3></BaseCard>
-->

<template>
    <div
        :class="[
            'rounded-xl border border-gray-200 bg-white transition-shadow',
            hoverable && 'cursor-pointer hover:shadow-lg',
            padding && 'p-5',
        ]"
        @click="$emit('click', $event)"
    >
        <slot />
    </div>
</template>

<script setup>
// Props
defineProps({
    hoverable: {
        type: Boolean,
        default: false,
    },
    padding: {
        type: Boolean,
        default: true,
    },
});

// Events
defineEmits(['click']);
</script>
