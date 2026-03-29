<template>
    <div class="w-full space-y-8">
        <!-- Header -->
        <div
            class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-800">
                    Project Management
                </h1>
            </div>
            <BaseButton
                variant="primary"
                icon="pi-plus"
                @click="openCreateModal"
                >New Project</BaseButton
            >
        </div>

        <!-- Filters -->
        <div
            class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            <div
                class="flex items-center gap-2 text-sm font-bold tracking-wider text-slate-500 uppercase"
            >
                <i class="pi pi-filter text-indigo-500"></i>
                <span>Search Filters</span>
            </div>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="flex flex-1 flex-wrap gap-4">
                    <div class="w-[120px]">
                        <BaseInput
                            v-model="filterId"
                            label="Search by ID"
                            placeholder="Ex: 1"
                            type="number"
                            @input="handleFilter"
                        />
                    </div>

                    <div class="min-w-[300px]">
                        <BaseInput
                            v-model="searchName"
                            label="Search by name"
                            placeholder="Type project name..."
                            @input="handleSearchWithDebounce"
                        />
                    </div>

                    <div class="w-[200px]">
                        <BaseSelect
                            v-model="filterStatus"
                            label="Status"
                            placeholder="All"
                            :options="[
                                { value: 'active', label: 'Active' },
                                { value: 'archived', label: 'Archived' },
                            ]"
                            @change="handleFilter"
                        />
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
                </div>
            </div>
            <p class="text-xs text-slate-400">
                <i class="pi pi-info-circle mr-1"></i>
                Name search is automatic (waits 500ms after typing)
            </p>
        </div>

        <!-- Loading -->
        <div v-if="isLoading" class="py-12 text-center">
            <i class="pi pi-spinner animate-spin text-4xl text-indigo-600"></i>
            <p class="mt-3 font-medium text-slate-500">Syncing data...</p>
        </div>

        <!-- Error -->
        <div
            v-else-if="fetchError"
            class="rounded-2xl border border-red-100 bg-red-50 p-6 shadow-sm"
        >
            <div class="flex items-center gap-2 text-red-700">
                <i class="pi pi-exclamation-circle"></i>
                <span class="font-semibold">{{ fetchError }}</span>
            </div>
        </div>

        <!-- Projects List -->
        <div v-else-if="projectsList.length > 0" class="space-y-4">
            <ProjectCard
                v-for="project in projectsList"
                :key="project.id"
                :project="project"
                @click="viewProject(project.id)"
                @edit="openEditModal(project)"
            />

            <!-- Pagination -->
            <div
                v-if="hasPagination"
                class="mt-6 flex items-center justify-center gap-4"
            >
                <BaseButton
                    variant="secondary"
                    :disabled="!hasPreviousPage"
                    @click="goToPage(meta.current_page[0] - 1)"
                >
                    <i class="pi pi-chevron-left"></i> Previous
                </BaseButton>
                <span class="font-medium text-gray-600"
                    >Page {{ meta.current_page[0] }} of
                    {{ meta.last_page }}</span
                >
                <BaseButton
                    variant="secondary"
                    :disabled="!hasNextPage"
                    @click="goToPage(meta.current_page[0] + 1)"
                >
                    Next <i class="pi pi-chevron-right"></i>
                </BaseButton>
            </div>
        </div>

        <!-- Empty -->
        <div v-else class="py-12 text-center">
            <i class="pi pi-folder-open mb-4 text-6xl text-gray-300"></i>
            <p class="text-gray-500">No projects found</p>
            <BaseButton variant="primary" class="mt-4" @click="openCreateModal"
                >Create first project</BaseButton
            >
        </div>

        <!-- Modal Create/Edit -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div
                class="mx-4 w-full max-w-md rounded-xl bg-white p-6 shadow-2xl"
            >
                <h3 class="mb-4 text-lg font-bold">
                    {{ isEditMode ? 'Edit Project' : 'New Project' }}
                </h3>
                <div class="space-y-4">
                    <BaseInput
                        v-model="form.name"
                        label="Name"
                        placeholder="Ex: Institutional Website"
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
                            placeholder="Project description... (max 600 characters)"
                        ></textarea>
                    </div>
                    <BaseSelect
                        v-model="form.status"
                        label="Status *"
                        :options="[
                            { value: 'active', label: 'Active' },
                            { value: 'archived', label: 'Archived' },
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
                        @click="handleSaveProject"
                        >{{ isEditMode ? 'Update' : 'Create' }}</BaseButton
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useProjects } from '../composables/useProjects';
import Swal from 'sweetalert2';
import BaseButton from '../components/BaseButton.vue';
import BaseInput from '../components/BaseInput.vue';
import BaseSelect from '../components/BaseSelect.vue';
import ProjectCard from '../components/ProjectCard.vue';

const router = useRouter();

// Composable
const {
    projects,
    meta,
    loading: isLoading,
    error: fetchError,
    fetchProjects,
    createProject,
    updateProject,
} = useProjects();

// State
const searchName = ref('');
const filterStatus = ref('');
const filterId = ref('');
const currentPage = ref(1);
const isModalOpen = ref(false);
const isSaving = ref(false);
const isEditMode = ref(false);
const editingProject = ref(null);

const form = reactive({
    name: '',
    description: '',
    status: 'active',
});

// Monitora erros críticos exibidos na tela para recarregar a página após 10 segundos
watch(fetchError, (newErr) => {
    if (newErr) {
        setTimeout(() => {
            // Só recarrega se o erro ainda estiver presente na tela
            if (fetchError.value) {
                window.location.reload();
            }
        }, 10000);
    }
});

// Computed
const projectsList = computed(() => projects.value);

const hasPagination = computed(() => meta.value && meta.value.last_page > 1);

const hasPreviousPage = computed(
    () => meta.value && meta.value.current_page[0] > 1,
);

const hasNextPage = computed(
    () => meta.value && meta.value.current_page[0] < meta.value.last_page,
);

// Functions
const loadProjectsData = async () => {
    await fetchProjects({
        name: searchName.value || undefined,
        status: filterStatus.value || undefined,
        id: filterId.value || undefined,
        page: currentPage.value,
    });

    if (projects.value.length === 0 && currentPage.value > 1) {
        currentPage.value = 1;
        await loadProjectsData();
    }
};

const handleFilter = () => {
    currentPage.value = 1;
    loadProjectsData();
};

let debounceTimer = null;

const handleSearchWithDebounce = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        currentPage.value = 1;
        loadProjectsData();
    }, 500);
};

const clearFilters = () => {
    searchName.value = '';
    filterStatus.value = '';
    filterId.value = '';
    currentPage.value = 1;
    loadProjectsData();
};

const goToPage = (page) => {
    currentPage.value = page;
    loadProjectsData();
};

const viewProject = async (id) => {
    Swal.fire({
        title: 'Loading project...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    await router.push(`/projects/${id}`);
    Swal.close();
};

const openCreateModal = () => {
    isEditMode.value = false;
    editingProject.value = null;
    form.name = '';
    form.description = '';
    form.status = 'active';
    isModalOpen.value = true;
};

const openEditModal = (project) => {
    isEditMode.value = true;
    editingProject.value = project;
    form.name = project.name;
    form.description = project.description || '';
    form.status = project.status;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleSaveProject = async () => {
    if (!form.name) {
        Swal.fire({
            icon: 'warning',
            title: 'Name is required',
            toast: true,
            position: 'top-end',
            timer: 2000,
        });
        return;
    }

    if (form.description.length > 600) {
        Swal.fire({
            icon: 'warning',
            title: 'Attention',
            text: 'Description cannot have more than 600 characters!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    isSaving.value = true;

    try {
        if (isEditMode.value) {
            await updateProject(editingProject.value.id, {
                name: form.name,
                description: form.description,
                status: form.status,
            });

            Swal.fire({
                icon: 'success',
                title: 'Project updated!',
                toast: true,
                position: 'top-end',
                timer: 2000,
            });
        } else {
            await createProject({
                name: form.name,
                description: form.description,
                status: form.status,
            });

            Swal.fire({
                icon: 'success',
                title: 'Project created!',
                toast: true,
                position: 'top-end',
                timer: 2000,
            });
        }

        closeModal();
        loadProjectsData();
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: err.message || 'Error saving project',
            toast: true,
            position: 'top-end',
            timer: 3000,
        });
    } finally {
        isSaving.value = false;
    }
};

onMounted(() => {
    loadProjectsData();
});
</script>
