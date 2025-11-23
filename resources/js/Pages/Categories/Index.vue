<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash-es';
import { router, usePage, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2';


// ----------------------------------------------------------------------------
// 1) Obtenemos los props que Inertia envía desde el backend (Laravel)
// ----------------------------------------------------------------------------
const page = usePage();

// ----------------------------------------------------------------------------
// 2) Hacemos reactivo el paginado y los datos que vienen desde Laravel
//    Como 'categories' cambia cada vez que Inertia hace una nueva petición,
//    usamos computed() para mantenerlo sincronizado automáticamente.
// ----------------------------------------------------------------------------
const categories = computed(() => page.props.categories);

// ----------------------------------------------------------------------------
// 3) Filtros locales (en este caso solo 'search')
//    Se inicializa con el valor que viene del backend para mantener
//    el campo de búsqueda sincronizado cuando se navega entre páginas.
// ----------------------------------------------------------------------------
const filters = ref({
  search: page.props.filters?.search || '',
});

// ----------------------------------------------------------------------------
// 4) Debounce del filtro:
//    Cada vez que el usuario escribe, esperamos 300 ms antes de hacer
//    la petición, evitando consultas por cada tecla presionada.
// ----------------------------------------------------------------------------
const debouncedFilter = debounce(() => {
  router.get(route('categories.index'), {
    search: filters.value.search,
    page: 1,
  }, {
    preserveState: false,
    replace: true,
  });
}, 300);

// Ejecutar búsqueda al cambiar el input
watch(() => filters.value.search, () => {
  debouncedFilter();
});

//Función deleteCategory
function deleteCategory(id) {
  Swal.fire({
    title: '¿Eliminar categoría?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (result.isConfirmed) {
      router.delete(route('categories.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire('Eliminada', 'La categoría fue eliminada correctamente.', 'success');
        },
        onError: () => {
          Swal.fire('Error', 'No se pudo eliminar la categoría.', 'error');
        },
      });
    }
  });
}

//Función para actualizar categoría con modal
const showEditModal = ref(false);
const isSubmitting = ref(false);
const formErrors = ref({});

const editForm = ref({
  id: null,
  categories_name: '',
  categories_description: '',
  categories_status: true,
});

function openEditModal(category) {
  editForm.value = {
    id: category.id,
    categories_name: category.categories_name,
    categories_description: category.categories_description ?? '',
    categories_status: category.categories_status ? 1 : 0
  };
  formErrors.value = {};
  showEditModal.value = true;
}

function updateCategory() {
  isSubmitting.value = true;

  router.put(
    route("categories.update", editForm.value.id),
    {
      categories_name: editForm.value.categories_name,
      categories_description: editForm.value.categories_description,
      categories_status: editForm.value.categories_status,
    },
    {
      preserveScroll: true,
      preserveState: false,
      onSuccess() {
        isSubmitting.value = false;
        showEditModal.value = false;
        Swal.fire({
          title: "Categoría actualizada",
          text: "Los cambios fueron guardados correctamente.",
          icon: "success",
          timer: 1500,
          showConfirmButton: false,
        });
      },
    }
  );
}

</script>

<template>
  <AppLayout title="Categorías">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Listado de Categorías
      </h2>
    </template>
>
    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-xl sm:rounded-lg p-6">

          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Categorías</h1>

            <Link :href="route('categories.create')"
              class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
            Nueva Categoría
            </Link>
          </div>

          <input v-model="filters.search" type="search" placeholder="Buscar por nombre..."
            class="w-full max-w-md p-2 rounded border border-gray-300 mb-4 focus:ring-green-500 focus:border-green-500" />

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow border border-gray-200 rounded-lg">
              <thead class="bg-gray-50 border-b">
                <tr>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">ID</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Nombre</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Descripción</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Estado</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Fecha Creación</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Computadoras</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Acciones</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="category in categories.data" :key="category.id"
                  class="border-b hover:bg-gray-100 transition">
                  <td class="py-2 px-4">{{ category.id }}</td>
                  <td class="py-2 px-4">{{ category.categories_name }}</td>
                  <td class="py-2 px-4">{{ category.categories_description }}</td>
                  <td class="py-2 px-4">
                    <span :class="category.categories_status ? 'text-green-600' : 'text-red-600'">
                      {{ category.categories_status ? 'Activo' : 'Inactivo' }}
                    </span>
                  </td>
                  <td class="py-2 px-4">{{ category.categories_created_date }}</td>
                  <td class="py-2 px-4">{{ category.computers_count || 0 }}</td>
                  <td class="py-2 px-4 flex space-x-3">
                    <Link :href="route('categories.show', category.id)"
                      class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                    Ver
                    </Link>
                    <button @click="openEditModal(category)"
                      class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                      Editar
                    </button>
                    <button @click="deleteCategory(category.id)"
                      class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                      Eliminar
                    </button>
                  </td>
                </tr>

                <tr v-if="categories.data.length === 0">
                  <td colspan="7" class="text-center py-4 text-gray-500">
                    No se encontraron categorías.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-4 flex justify-center space-x-4">
            <button :disabled="!categories.prev_page_url" @click.prevent="
              router.get(route('categories.index'), {
                page: categories.current_page - 1,
                search: filters.search,
              }, {
                preserveState: false,
              })
              " class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 disabled:opacity-50">
              Anterior
            </button>

            <span class="px-3 py-1 font-semibold text-gray-700">
              {{ categories.current_page }} / {{ categories.last_page }}
            </span>

            <button :disabled="!categories.next_page_url" @click.prevent="
              router.get(route('categories.index'), {
                page: categories.current_page + 1,
                search: filters.search,
              }, {
                preserveState: false,
              })
              " class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 disabled:opacity-50">
              Siguiente
            </button>
          </div>

        </div>
      </div>
    </div>

    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
      <div class="bg-white w-full max-w-lg p-6 rounded shadow-xl">

        <h2 class="text-xl font-bold mb-4">Editar Categoría</h2>

        <label class="block font-medium">Nombre:</label>
        <input type="text" v-model="editForm.categories_name" class="w-full border rounded p-2 mb-1" />
        <p v-if="formErrors.categories_name" class="text-red-600 text-sm">
          {{ formErrors.categories_name }}
        </p>

        <label class="block font-medium mt-3">Descripción:</label>
        <textarea v-model="editForm.categories_description" class="w-full border rounded p-2"></textarea>
        <p v-if="formErrors.categories_description" class="text-red-600 text-sm">
          {{ formErrors.categories_description }}
        </p>

        <label class="block font-medium mt-3">Estado:</label>
        <select v-model.number="editForm.categories_status" class="w-full border rounded p-2">
          <option :value="1">Activo</option>
          <option :value="0">Inactivo</option>
        </select>

        <p v-if="formErrors.categories_status" class="text-red-600 text-sm">
          {{ formErrors.categories_status }}
        </p>

        <div class="flex justify-end mt-6 space-x-2">
          <button @click="showEditModal = false" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            Cancelar
          </button>

          <button @click="updateCategory" :disabled="isSubmitting"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
            Guardar Cambios
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
