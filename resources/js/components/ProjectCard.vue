<!--
  ProjectCard - Card de Projeto (Layout Reorganizado)
-->

<template>
    <div
        class="group w-full cursor-pointer rounded-xl border-2 border-gray-300 bg-white p-6 transition-all hover:border-blue-400 hover:shadow-lg"
        @click="$emit('click', project)"
    >
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between gap-4">
                <!-- Esquerda: ID + Nome -->
                <div class="flex items-center gap-3">
                    <!-- ID -->
                    <span
                        class="rounded bg-gray-100 px-2.5 py-1 font-mono text-sm font-semibold text-gray-500"
                    >
                        #{{ project.id }}
                    </span>

                    <!-- Nome (Título Grande) -->
                    <h3
                        class="text-xl font-bold text-gray-900 transition-colors group-hover:text-blue-600"
                    >
                        {{ project.name }}
                    </h3>
                </div>

                <div>
                    <span
                        :class="[
                            'inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-bold',
                            project.status === 'active'
                                ? 'border border-green-300 bg-green-100 text-green-800'
                                : 'border border-blue-300 bg-blue-950/10 text-blue-900',
                        ]"
                    >
                        <span
                            class="h-2.5 w-2.5 rounded-full"
                            :class="
                                project.status === 'active'
                                    ? 'bg-green-600'
                                    : 'bg-blue-900'
                            "
                        ></span>
                        {{
                            project.status === 'active' ? 'Active' : 'Archived'
                        }}
                    </span>
                </div>
            </div>

            <p
                class="line-clamp-2 text-base leading-relaxed text-gray-500"
                :title="project.description"
            >
                {{ project.description || 'No description' }}
            </p>

            <div
                class="flex items-center justify-between gap-4 border-t border-gray-100 pt-2"
            >
                <!-- Esquerda: Stats de Tarefas -->
                <div class="flex items-center gap-6 text-base">
                    <div class="flex items-center gap-2.5 text-gray-600">
                        <i class="pi pi-list text-lg text-gray-400"></i>
                        <span class="font-semibold"
                            >{{ project.tasks_count || 0 }} tasks</span
                        >
                    </div>

                    <div
                        v-if="project.overdue_tasks_count > 0"
                        class="flex items-center gap-2.5 font-semibold text-red-600"
                    >
                        <i class="pi pi-exclamation-triangle text-lg"></i>
                        <span>{{ project.overdue_tasks_count }} overdue</span>
                    </div>

                    <div
                        v-else
                        class="flex items-center gap-2.5 font-semibold text-green-600"
                    >
                        <i class="pi pi-check-circle text-lg"></i>
                        <span>All up to date</span>
                    </div>
                </div>

                <!-- Direita: Botão Editar -->
                <button
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-blue-600 transition-all hover:bg-blue-50 hover:text-blue-800"
                    @click.stop="$emit('edit', project)"
                >
                    <i class="pi pi-pencil"></i>
                    Edit project
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * ProjectCard - Card de Projeto
 *
 * @prop {Object} project
 */

defineProps({
    project: {
        type: Object,
        required: true,
    },
});

defineEmits(['click', 'edit']);
</script>
