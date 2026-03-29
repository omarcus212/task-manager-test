<template>
    <div class="w-full space-y-8">
        <!-- Project Loading -->
        <div v-if="isProjectLoading" class="py-20 text-center">
            <i
                class="pi pi-spinner mb-4 animate-spin text-5xl text-indigo-600"
            ></i>
            <p class="text-lg font-medium text-slate-600">
                Loading project information...
            </p>
        </div>

        <!-- Project Error -->
        <div v-else-if="projectError" class="py-20 text-center">
            <i
                class="pi pi-exclamation-triangle mb-4 text-6xl text-red-400"
            ></i>
            <p class="mb-4 text-lg font-medium text-red-600">
                {{ projectError }}
            </p>
            <BaseButton variant="primary" @click="handleGoBack">
                <i class="pi pi-arrow-left mr-2"></i> Back
            </BaseButton>
        </div>

        <!-- Main Content -->
        <div v-else-if="currentProject" class="space-y-8">
            <!-- Project Info -->
            <div class="flex flex-col gap-4 border-b border-slate-200 pb-6">
                <div
                    class="rounded-2xl border-2 border-slate-200 bg-gradient-to-r from-slate-50 to-white p-6"
                >
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex-1">
                            <div class="mb-3 flex items-center gap-3">
                                <span
                                    class="rounded bg-slate-200 px-3 py-1.5 font-mono text-sm font-bold text-slate-600"
                                >
                                    #{{ currentProject.id }}
                                </span>
                                <h1
                                    class="text-3xl font-black tracking-tight text-slate-800"
                                >
                                    {{ currentProject.name }}
                                </h1>
                            </div>
                            <p class="mb-4 text-base text-gray-600">
                                {{
                                    currentProject.description ||
                                    'No description'
                                }}
                            </p>
                            <div class="flex items-center gap-6 text-sm">
                                <div
                                    class="flex items-center gap-2 text-slate-600"
                                >
                                    <i class="pi pi-list text-slate-400"></i>
                                    <span class="font-semibold"
                                        >{{
                                            currentProject.tasks_count || 0
                                        }}
                                        tasks</span
                                    >
                                </div>
                                <div
                                    v-if="
                                        currentProject.overdue_tasks_count > 0
                                    "
                                    class="flex items-center gap-2 font-semibold text-red-600"
                                >
                                    <i class="pi pi-exclamation-triangle"></i>
                                    <span
                                        >{{
                                            currentProject.overdue_tasks_count
                                        }}
                                        overdue</span
                                    >
                                </div>
                                <div
                                    v-else
                                    class="flex items-center gap-2 font-semibold text-green-600"
                                >
                                    <i class="pi pi-check-circle"></i>
                                    <span>All up to date</span>
                                </div>
                            </div>
                        </div>
                        <div class="sm:self-center">
                            <span
                                :class="[
                                    'inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-bold',
                                    currentProject.status === 'active'
                                        ? 'border-2 border-green-300 bg-green-100 text-green-800'
                                        : 'border-2 border-blue-300 bg-blue-950/10 text-blue-900',
                                ]"
                            >
                                <span
                                    class="h-3 w-3 rounded-full"
                                    :class="
                                        currentProject.status === 'active'
                                            ? 'bg-green-600'
                                            : 'bg-blue-900'
                                    "
                                ></span>
                                {{
                                    currentProject.status === 'active'
                                        ? 'Active'
                                        : 'Archived'
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Filters -->
            <div
                class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <div
                    class="flex items-center gap-2 text-sm font-bold tracking-wider text-slate-500 uppercase"
                >
                    <i class="pi pi-filter text-indigo-500"></i>
                    <span>Task Filters</span>
                </div>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="flex flex-1 flex-wrap gap-4">
                        <div class="w-[180px]">
                            <BaseSelect
                                v-model="statusFilter"
                                label="Status"
                                placeholder="All"
                                :options="[
                                    { value: 'todo', label: 'To Do' },
                                    {
                                        value: 'in_progress',
                                        label: 'In Progress',
                                    },
                                    { value: 'done', label: 'Done' },
                                ]"
                                @change="handleFilter"
                            />
                        </div>
                        <div class="w-[180px]">
                            <BaseSelect
                                v-model="priorityFilter"
                                label="Priority"
                                placeholder="All"
                                :options="[
                                    { value: 'low', label: 'Low' },
                                    { value: 'medium', label: 'Medium' },
                                    { value: 'high', label: 'High' },
                                ]"
                                @change="handleFilter"
                            />
                        </div>
                        <div class="flex items-end pb-2">
                            <label
                                class="flex cursor-pointer items-center gap-2"
                            >
                                <input
                                    v-model="onlyOverdue"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    @change="handleFilter"
                                />
                                <span class="text-sm font-medium text-gray-700">
                                    <i
                                        class="pi pi-exclamation-triangle mr-1 text-red-500"
                                    ></i>
                                    Overdue only
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <BaseButton
                            variant="secondary"
                            icon="pi-search"
                            @click="handleFilter"
                            >Search</BaseButton
                        >
                        <BaseButton
                            variant="ghost"
                            icon="pi-filter-slash"
                            @click="clearFilters"
                            >Clear</BaseButton
                        >
                        <BaseButton
                            variant="primary"
                            icon="pi-plus"
                            @click="openCreateModal"
                            >New Task</BaseButton
                        >
                    </div>
                </div>
            </div>

            <!-- Tasks Loading -->
            <div v-if="isTasksLoading" class="py-12 text-center">
                <i
                    class="pi pi-spinner animate-spin text-4xl text-indigo-600"
                ></i>
                <p class="mt-3 font-medium text-slate-500">Loading tasks...</p>
            </div>

            <!-- Tasks Error -->
            <div
                v-else-if="tasksError"
                class="rounded-2xl border border-red-100 bg-red-50 p-6 shadow-sm"
            >
                <div class="flex items-center gap-2 text-red-700">
                    <i class="pi pi-exclamation-circle"></i>
                    <span class="font-semibold">{{ tasksError }}</span>
                </div>
            </div>

            <!-- Tasks Grid -->
            <div v-else-if="tasksList.length > 0" class="space-y-4">
                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <TaskCard
                        v-for="task in tasksList"
                        :key="task.id"
                        :task="task"
                        @status-change="handleStatusUpdate"
                        @delete="handleTaskDeletion"
                    />
                </div>

                <!-- Pagination -->
                <div
                    v-if="hasPagination"
                    class="mt-8 flex items-center justify-center gap-4"
                >
                    <BaseButton
                        variant="secondary"
                        :disabled="!hasPreviousPage"
                        @click="goToPage(metaTasks.current_page[0] - 1)"
                    >
                        <i class="pi pi-chevron-left"></i> Previous
                    </BaseButton>
                    <span class="font-medium text-gray-600"
                        >Page {{ metaTasks.current_page[0] }} of
                        {{ metaTasks.last_page }}</span
                    >
                    <BaseButton
                        variant="secondary"
                        :disabled="!hasNextPage"
                        @click="goToPage(metaTasks.current_page[0] + 1)"
                    >
                        Next <i class="pi pi-chevron-right"></i>
                    </BaseButton>
                </div>
            </div>

            <!-- Empty -->
            <div v-else class="py-12 text-center">
                <i class="pi pi-check-square mb-4 text-6xl text-gray-300"></i>
                <p class="text-lg text-gray-500">No tasks found</p>
                <BaseButton
                    variant="primary"
                    class="mt-4"
                    @click="openCreateModal"
                >
                    <i class="pi pi-plus mr-2"></i>
                    Create first task
                </BaseButton>
            </div>

            <!-- Modal Create Task -->
            <div
                v-if="isModalOpen"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            >
                <div
                    class="mx-4 w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl"
                >
                    <h3 class="mb-4 text-lg font-bold">New Task</h3>
                    <div class="space-y-4">
                        <BaseInput
                            v-model="form.title"
                            label="Title"
                            placeholder="Ex: Create wireframes"
                            required
                        />
                        <div>
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Task description..."
                            ></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <BaseSelect
                                v-model="form.priority"
                                label="Priority *"
                                :options="[
                                    { value: 'low', label: 'Low' },
                                    { value: 'medium', label: 'Medium' },
                                    { value: 'high', label: 'High' },
                                ]"
                            />
                            <BaseInput
                                v-model="form.dueDate"
                                type="date"
                                label="Due Date"
                            />
                        </div>
                        <BaseSelect
                            v-model="form.status"
                            label="Status *"
                            :options="[
                                { value: 'todo', label: 'To Do' },
                                { value: 'in_progress', label: 'In Progress' },
                                { value: 'done', label: 'Done' },
                            ]"
                        />
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <BaseButton variant="ghost" @click="closeModal"
                            >Cancel</BaseButton
                        >
                        <BaseButton
                            variant="primary"
                            :loading="isSaving"
                            @click="handleSaveTask"
                            >Create</BaseButton
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProjects } from '../composables/useProjects';
import { useTasks } from '../composables/useTasks';
import Swal from 'sweetalert2';
import BaseButton from '../components/BaseButton.vue';
import BaseInput from '../components/BaseInput.vue';
import BaseSelect from '../components/BaseSelect.vue';
import TaskCard from '../components/TaskCard.vue';

const route = useRoute();
const router = useRouter();

// Project ID from URL
const projectId = computed(() => route.params.id);

// Composables
const {
    currentProject,
    loading: isProjectLoading,
    error: projectError,
    fetchProject,
} = useProjects();

const {
    tasks: tasksList,
    meta: metaTasks,
    loading: isTasksLoading,
    error: tasksError,
    fetchTasks,
    createTask,
    updateTaskOptimistic,
    deleteTask,
} = useTasks(projectId);

// State
const statusFilter = ref('');
const priorityFilter = ref('');
const onlyOverdue = ref(false);
const currentPage = ref(1);
const isModalOpen = ref(false);
const isSaving = ref(false);

const form = reactive({
    title: '',
    description: '',
    priority: 'medium',
    dueDate: '',
    status: 'todo',
});

//  Monitora erros críticos exibidos na tela para recarregar a página após 10 segundos
watch([projectError, tasksError], ([newProjectErr, newTasksErr]) => {
    if (newProjectErr || newTasksErr) {
        setTimeout(() => {
            // Só recarrega se o erro ainda estiver presente na tela
            if (projectError.value || tasksError.value) {
                window.location.reload();
            }
        }, 10000);
    }
});

// Computed
const hasPagination = computed(
    () => metaTasks.value && metaTasks.value.last_page > 1,
);
const hasPreviousPage = computed(
    () => metaTasks.value && metaTasks.value.current_page[0] > 1,
);
const hasNextPage = computed(
    () =>
        metaTasks.value &&
        metaTasks.value.current_page[0] < metaTasks.value.last_page,
);

// Functions
const loadTasksData = async () => {
    await fetchTasks({
        status: statusFilter.value || undefined,
        priority: priorityFilter.value || undefined,
        overdue: onlyOverdue.value ? true : undefined,
        page: currentPage.value,
    });

    if (tasksList.value.length === 0 && currentPage.value > 1) {
        currentPage.value = 1;
        await loadTasksData();
    }
};

const handleFilter = () => {
    currentPage.value = 1;
    loadTasksData();
};

const clearFilters = () => {
    statusFilter.value = '';
    priorityFilter.value = '';
    onlyOverdue.value = false;
    currentPage.value = 1;
    loadTasksData();
};

const goToPage = (page) => {
    currentPage.value = page;
    loadTasksData();
};

const handleGoBack = () => {
    router.push('/');
};

const openCreateModal = () => {
    form.title = '';
    form.description = '';
    form.priority = 'medium';
    form.dueDate = '';
    form.status = 'todo';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleStatusUpdate = async ({ task, newStatus }) => {
    try {
        await updateTaskOptimistic(task, { status: newStatus });

        Swal.fire({
            icon: 'success',
            title: 'Status updated!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Could not update task',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
    }
};

const handleTaskDeletion = async (taskItem) => {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: `Delete "${taskItem.title}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
    });

    if (result.isConfirmed) {
        try {
            await deleteTask(taskItem);
            await loadTasksData();
            Swal.fire({
                icon: 'success',
                title: 'Task deleted!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Could not delete task',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
            });
        }
    }
};

const dateToday = new Date().toISOString().split('T')[0];

const handleSaveTask = async () => {
    if (form.dueDate && form.dueDate < dateToday) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid date',
            text: 'Cannot select a date before today.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    if (!form.title) {
        Swal.fire({
            icon: 'warning',
            title: 'Attention',
            text: 'Title is required!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    isSaving.value = true;

    try {
        const payload = {
            title: form.title,
            description: form.description,
            priority: form.priority,
            due_date: form.dueDate || null,
            status: form.status,
        };

        await createTask(payload);

        Swal.fire({
            icon: 'success',
            title: 'Task created!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });

        closeModal();
        loadTasksData();
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: err.message || 'Error saving task',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
    } finally {
        isSaving.value = false;
    }
};

// Lifecycle
onMounted(async () => {
    await fetchProject(projectId.value);
    loadTasksData();
});
</script>
