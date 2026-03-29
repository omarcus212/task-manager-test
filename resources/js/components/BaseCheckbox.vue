<!--
 <BaseCheckbox v-model="showCompleted" label="Mostrar concluídas" />
-->

<template>
    <label class="flex cursor-pointer items-center gap-2">
        <input
            type="checkbox"
            :checked="modelValue"
            :disabled="disabled"
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            @change="$emit('update:modelValue', $event.target.checked)"
        />
        <span
            v-if="label"
            :class="['text-sm', disabled ? 'text-gray-400' : 'text-gray-700']"
        >
            {{ label }}
        </span>
    </label>
</template>

<script setup>
// Props
defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

// Events
defineEmits(['update:modelValue']);
</script>
