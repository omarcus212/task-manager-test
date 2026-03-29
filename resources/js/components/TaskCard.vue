<!--  
  TaskCard - Card Compacto de Tarefa
-->
<template>
    <div
        class="flex h-full flex-col rounded-xl border-2 border-gray-200 bg-white p-5 transition-all hover:border-blue-400 hover:shadow-lg"
    >
        <div class="mb-3 flex items-center justify-between">
            <!-- Prioridade Badge -->
            <span
                :class="[
                    'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold',
                    task.priority === 'low' && 'bg-yellow-100 text-yellow-800',
                    task.priority === 'medium' &&
                        'bg-orange-100 text-orange-800',
                    task.priority === 'high' && 'bg-red-100 text-red-800',
                ]"
            >
                <i class="pi pi-flag text-[10px]"></i>
                {{ priorityLabels[task.priority] }}
            </span>
            <!-- Status Badge -->
            <span
                :class="[
                    'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold',
                    task.status === 'todo' &&
                        'border border-gray-300 bg-gray-100 text-gray-700',
                    task.status === 'in_progress' &&
                        'bg-orange-100 text-orange-700',
                    task.status === 'done' && 'bg-green-100 text-green-700',
                ]"
            >
                {{ statusLabels[task.status] }}
            </span>
        </div>

        <!-- Título -->
        <h4 class="mb-2 line-clamp-2 text-base font-bold text-gray-900">
            {{ task.title }}
        </h4>

        <!-- Descrição (curta) -->
        <p
            v-if="task.description"
            class="mb-4 line-clamp-2 flex-1 text-sm text-gray-600"
        >
            {{ task.description }}
        </p>

        <!-- Vencimento -->
        <div
            class="mb-4 flex items-center gap-2 text-sm"
            :class="
                task.is_overdue ? 'font-semibold text-red-600' : 'text-gray-500'
            "
        >
            <i
                class="pi text-base"
                :class="
                    task.is_overdue ? 'pi-exclamation-circle' : 'pi-calendar'
                "
            ></i>
            <span>{{ dueDateLabel }}</span>
        </div>

        <!--  Select Status + Botão Excluir  -->
        <div class="flex items-center gap-2 border-t border-gray-100 pt-3">
            <select
                v-model="localStatus"
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-2 py-1.5 text-xs outline-none focus:ring-2 focus:ring-blue-500"
                @change="handleStatusChange"
            >
                <option value="todo">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
            </select>

            <button
                class="flex-shrink-0 cursor-pointer text-xs font-medium text-red-400 transition-colors hover:text-red-500"
                @click.stop="$emit('delete', task)"
                title="Delete task"
            >
                Delete
            </button>
        </div>
    </div>
</template>

<script setup>
/**
 * TaskCard - Card de Tarefa
 *
 * @prop {Object} task
 */
import { ref, computed } from 'vue';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['status-change', 'delete']);

const localStatus = ref(props.task.status);

const statusLabels = {
    todo: 'To Do',
    in_progress: 'In Progress',
    done: 'Done',
};

const priorityLabels = {
    low: 'Low',
    medium: 'Medium',
    high: 'High',
};

const dueDateLabel = computed(() => {
    if (!props.task.due_date) return 'No due date';

    // Formatação (dd-mm-yyyy)
    const [year, month, day] = props.task.due_date.split('-');
    const formatted = `${day}-${month}-${year}`;

    const dueDate = new Date(year, month - 1, day);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (props.task.status === 'done') {
        return `Completed ${formatted}`;
    }
    if (dueDate < today) {
        return `Overdue ${formatted}`;
    }
    return `Due ${formatted}`;
});

const handleStatusChange = (event) => {
    emit('status-change', {
        task: props.task,
        newStatus: event.target.value,
    });
};
</script>
