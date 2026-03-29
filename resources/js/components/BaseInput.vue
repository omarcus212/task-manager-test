<!--
  <BaseInput v-model="name" label="Nome" placeholder="Digite o nome" />
-->

<template>
    <div>
        <!-- Label -->
        <label
            v-if="label"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <!-- Input -->
        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :class="[
                'w-full rounded-lg border px-3 py-2 transition-all',
                'outline-none focus:border-transparent focus:ring-2 focus:ring-blue-500',
                disabled && 'cursor-not-allowed bg-gray-100',
                error ? 'border-red-500 focus:ring-red-500' : 'border-gray-300',
            ]"
            @input="$emit('update:modelValue', $event.target.value)"
        />

        <!-- Mensagem de Erro -->
        <p v-if="error" class="mt-1 text-sm text-red-500">
            {{ error }}
        </p>
    </div>
</template>

<script setup>
// Props
defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text', // text, email, password, number, date
    },
    placeholder: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
});

// Events
defineEmits(['update:modelValue']);
</script>
