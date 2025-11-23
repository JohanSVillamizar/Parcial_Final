<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash-es';
import { router, usePage, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

// ----------------------------------------------------------------------------
// 1) Props desde Inertia
// ----------------------------------------------------------------------------
const page = usePage();

// ----------------------------------------------------------------------------
// 2) Datos reactivos sincronizados con el backend (computers)
// ----------------------------------------------------------------------------
const computers = computed(() => page.props.computers);

// ----------------------------------------------------------------------------
// 3) Filtros locales (search y category_id)
// ----------------------------------------------------------------------------
const filters = ref({
  search: page.props.filters?.search || '',
  category_id: page.props.filters?.category_id || ''
});

// ----------------------------------------------------------------------------
// 4) Debounce de búsqueda
// ----------------------------------------------------------------------------
const debouncedFilter = debounce(() => {
  router.get(route('computers.index'), {
    search: filters.value.search,
    category_id: filters.value.category_id,
    page: 1,
  }, {
    preserveState: false,
    replace: true,
  });
}, 400);

// Observadores
watch(() => filters.value.search, () => debouncedFilter());
watch(() => filters.value.category_id, () => {
  router.get(route('computers.index'), {
    search: filters.value.search,
    category_id: filters.value.category_id,
    page: 1,
  }, {
    preserveState: false,
    replace: true,
  });
});


function deleteComputer(id) {
  Swal.fire({
    title: '¿Eliminar computadora?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (result.isConfirmed) {
      router.delete(route('computers.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire('Eliminada', 'La computadora fue eliminada correctamente.', 'success');
        },
        onError: () => {
          Swal.fire('Error', 'No se pudo eliminar la computadora.', 'error');
        }
      });
    }
  });
}

const showEditModal = ref(false);
const isSubmitting = ref(false);
const formErrors = ref({});

const editForm = ref({
  id: null,
  computer_brand: '',
  computer_model: '',
  computer_price: 0,
  computer_ram_size: 0,
  computer_is_laptop: false,
  category_id: '',
  computers_barcode: '',
});

function openEditModal(computer) {
  editForm.value = {
    id: computer.id_computer,
    computer_brand: computer.computer_brand,
    computer_model: computer.computer_model,
    computer_price: computer.computer_price,
    computer_ram_size: computer.computer_ram_size,
    computer_is_laptop: computer.computer_is_laptop ? 1 : 0,
    category_id: computer.category_id || '',
    computers_barcode: computer.computers_barcode || '',
  };
  formErrors.value = {};
  showEditModal.value = true;
}

function updateComputer() {
  isSubmitting.value = true;

  router.put(
    route("computers.update", editForm.value.id),
    { ...editForm.value },
    {
      preserveScroll: true,
      preserveState: false,
      onSuccess() {
        isSubmitting.value = false;
        showEditModal.value = false;
        Swal.fire({
          title: "Computadora actualizada",
          text: "Los cambios fueron guardados correctamente.",
          icon: "success",
          timer: 1500,
          showConfirmButton: false,
        });
      },
      onError(errors) {
        isSubmitting.value = false;
        formErrors.value = errors;
      }
    }
  );
}
</script>

<template>
  <AppLayout title="Computadoras">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Listado de Computadoras
      </h2>
    </template>

    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-xl sm:rounded-lg p-6">

          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Computadoras</h1>

            <Link :href="route('computers.create')"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            Crear Nueva
            </Link>
          </div>

          <div class="flex gap-4 mb-4">
            <input v-model="filters.search" type="search" placeholder="Buscar por marca o modelo..."
              class="w-full max-w-md p-2 rounded border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
            <select v-model="filters.category_id" class="border rounded px-2 py-1 min-w-[220px] pr-10">
              <option value="">Todas las categorías</option>
              <option v-for="cat in page.props.categories" :key="cat.id" :value="cat.id">
                {{ cat.categories_name }}
              </option>
            </select>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow border border-gray-200 rounded-lg">
              <thead class="bg-gray-50 border-b">
                <tr>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">ID</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Marca</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Modelo</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Precio</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">RAM (GB)</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Portátil</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Categoría</th>
                  <th class="py-2 px-4 text-left font-semibold text-gray-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="computer in computers.data" :key="computer.id_computer"
                  class="border-b hover:bg-gray-100 transition">
                  <td class="py-2 px-4">{{ computer.id_computer }}</td>
                  <td class="py-2 px-4">{{ computer.computer_brand }}</td>
                  <td class="py-2 px-4">{{ computer.computer_model }}</td>
                  <td class="py-2 px-4">$ {{ computer.computer_price }}</td>
                  <td class="py-2 px-4">{{ computer.computer_ram_size }}</td>
                  <td class="py-2 px-4">{{ computer.computer_is_laptop ? 'Sí' : 'No' }}</td>
                  <td class="py-2 px-4">{{ computer.category?.categories_name || 'N/A' }}</td>

                  <td class="py-2 px-4 flex gap-3">
                    <Link :href="route('computers.show', computer.id_computer)"
                      class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                    Ver
                    </Link>

                    <button @click="openEditModal(computer)"
                      class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                      Editar
                    </button>

                    <button @click="deleteComputer(computer.id_computer)"
                      class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                      Eliminar
                    </button>
                  </td>
                </tr>

                <tr v-if="computers.data.length === 0">
                  <td colspan="8" class="text-center py-4 text-gray-500">
                    No se encontraron computadoras.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-4 flex justify-center space-x-4">
            <button :disabled="!computers.prev_page_url" @click.prevent="
              router.get(route('computers.index'), {
                page: computers.current_page - 1,
                search: filters.search,
                category_id: filters.category_id,
              }, { preserveState: false })
              " class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 disabled:opacity-50">
              Anterior
            </button>

            <span class="px-3 py-1 font-semibold text-gray-700">
              {{ computers.current_page }} / {{ computers.last_page }}
            </span>

            <button :disabled="!computers.next_page_url" @click.prevent="
              router.get(route('computers.index'), {
                page: computers.current_page + 1,
                search: filters.search,
                category_id: filters.category_id,
              }, { preserveState: false })
              " class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 disabled:opacity-50">
              Siguiente
            </button>
          </div>

        </div>
      </div>
    </div>

    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
      <div class="bg-white w-full max-w-lg p-6 rounded shadow-xl max-h-[90vh] overflow-y-auto">

        <h2 class="text-xl font-bold mb-4">Editar Computador</h2>

        <label class="block font-medium">Marca:</label>
        <input type="text" v-model="editForm.computer_brand" class="w-full border rounded p-2 mb-1" />
        <p v-if="formErrors.computer_brand" class="text-red-600 text-sm">{{ formErrors.computer_brand }}</p>

        <label class="block font-medium mt-3">Modelo:</label>
        <input type="text" v-model="editForm.computer_model" class="w-full border rounded p-2 mb-1" />
        <p v-if="formErrors.computer_model" class="text-red-600 text-sm">{{ formErrors.computer_model }}</p>

        <label class="block font-medium mt-3">Precio:</label>
        <input type="number" v-model="editForm.computer_price" class="w-full border rounded p-2 mb-1" />
        <p v-if="formErrors.computer_price" class="text-red-600 text-sm">{{ formErrors.computer_price }}</p>

        <label class="block font-medium mt-3">RAM (GB):</label>
        <input type="number" v-model="editForm.computer_ram_size" class="w-full border rounded p-2 mb-1" />
        <p v-if="formErrors.computer_ram_size" class="text-red-600 text-sm">{{ formErrors.computer_ram_size }}</p>

        <label class="block font-medium mt-3">Portátil:</label>
        <select v-model.number="editForm.computer_is_laptop" class="w-full border rounded p-2 mb-1">
          <option :value="1">Sí</option>
          <option :value="0">No</option>
        </select>
        <p v-if="formErrors.computer_is_laptop" class="text-red-600 text-sm">{{ formErrors.computer_is_laptop }}</p>

        <label class="block font-medium mt-3">Categoría:</label>
        <select v-model="editForm.category_id" class="w-full border rounded p-2 mb-1">
          <option v-for="cat in page.props.categories" :key="cat.id" :value="cat.id">
            {{ cat.categories_name }}
          </option>
        </select>
        <p v-if="formErrors.category_id" class="text-red-600 text-sm">{{ formErrors.category_id }}</p>

        <label class="block font-medium mt-3">Código de barras:</label>
        <input type="text" v-model="editForm.computers_barcode"
          @input="editForm.computers_barcode = editForm.computers_barcode.toUpperCase()" maxlength="12"
          class="w-full border rounded p-2 mb-1" />
        <p class="text-gray-500 text-sm mb-1">
          Debe tener 12 caracteres alfanuméricos (A-Z, 0-9). Se convertirá automáticamente a mayúsculas.
        </p>
        <p v-if="formErrors.computers_barcode" class="text-red-600 text-sm">{{ formErrors.computers_barcode }}</p>

        <div class="flex justify-end mt-6 space-x-2">
          <button @click="showEditModal = false" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            Cancelar
          </button>
          <button @click="updateComputer" :disabled="isSubmitting"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
            Guardar Cambios
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>