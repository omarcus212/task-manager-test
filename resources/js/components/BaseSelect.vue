<!--
  <BaseSelect v-model="status" :options="statusOptions" label="Status" />
-->

<template>
    <div>
        <!-- Label -->
        <label
            v-if="label"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            {{ label }}
        </label>

        <!-- Select -->
        <select
            :value="modelValue"
            :disabled="disabled"
            :class="[
                'w-full rounded-lg border bg-white px-3 py-2 transition-all',
                'outline-none focus:border-transparent focus:ring-2 focus:ring-blue-500',
                disabled && 'cursor-not-allowed bg-gray-100',
                error ? 'border-red-500' : 'border-gray-300',
            ]"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <!-- Opção padrão -->
            <option v-if="placeholder" value="">{{ placeholder }}</option>

            <!-- Opções dinâmicas -->
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>

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
    placeholder: {
        type: String,
        default: 'Selecione...',
    },
    options: {
        type: Array,
        required: true,
        // Formato: [{ value: 'active', label: 'Ativo' }]
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
