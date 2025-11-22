<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash-es';
import { router, usePage, Link } from '@inertiajs/vue3';

// ----------------------------------------------------------------------------
// 1) Obtenemos los props que Inertia envía desde el backend (Laravel)
// ----------------------------------------------------------------------------
const page = usePage();

// ----------------------------------------------------------------------------
// 2) Hacemos reactivo el paginado y los datos que vienen desde Laravel
//    Como 'computers' cambia cada vez que Inertia hace una nueva petición,
//    usamos computed() para mantenerlo sincronizado automáticamente.
// ----------------------------------------------------------------------------
const computers = computed(() => page.props.computers);

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
//    Cada vez que el usuario escribe, esperamos 400 ms antes de hacer
//    la petición, evitando consultas por cada tecla presionada.
// ----------------------------------------------------------------------------
const debouncedFilter = debounce(() => {
  router.get(route('computers.index'), {
    search: filters.value.search,
    page: 1,
  }, {
    preserveState: false,  // IMPORTANTE: deja que Inertia reemplace el estado
    replace: true,         // actualiza la URL sin llenar el historial
  });
}, 400);

// ----------------------------------------------------------------------------
// 5) Observamos los cambios en el campo de búsqueda
// ----------------------------------------------------------------------------
watch(() => filters.value.search, () => {
  debouncedFilter();
});

</script>

<template>
  <AppLayout title="Computadoras">
    <!-- HEADER -->
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Listado de Computadoras
      </h2>
    </template>

    <!-- CONTENIDO -->
    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Card principal -->
        <div class="bg-white shadow-xl sm:rounded-lg p-6">

          <!-- Título + botón -->
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Computadoras</h1>

            <Link
              :href="route('computers.create')"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
            >
              Crear Nueva
            </Link>
          </div>

          <!-- Buscador -->
          <input
            v-model="filters.search"
            type="search"
            placeholder="Buscar por marca o modelo..."
            class="w-full max-w-md p-2 rounded border border-gray-300 mb-4 focus:ring-blue-500 focus:border-blue-500"
          />

          <!-- Tabla -->
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
                <tr
                  v-for="computer in computers.data"
                  :key="computer.id_computer"
                  class="border-b hover:bg-gray-100 transition"
                >
                  <td class="py-2 px-4">{{ computer.id_computer }}</td>
                  <td class="py-2 px-4">{{ computer.computer_brand }}</td>
                  <td class="py-2 px-4">{{ computer.computer_model }}</td>
                  <td class="py-2 px-4">${{ computer.computer_price }}</td>
                  <td class="py-2 px-4">{{ computer.computer_ram_size }}</td>
                  <td class="py-2 px-4">{{ computer.computer_is_laptop ? 'Sí' : 'No' }}</td>
                  <td class="py-2 px-4">{{ computer.category?.categories_name || 'N/A' }}</td>
                  <td class="py-2 px-4">
                    <Link
                      :href="route('computers.edit', computer.id_computer)"
                      class="text-blue-600 hover:underline"
                    >
                      Editar
                    </Link>
                  </td>
                </tr>

                <!-- Sin resultados -->
                <tr v-if="computers.data.length === 0">
                  <td colspan="8" class="text-center py-4 text-gray-500">
                    No se encontraron computadoras.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Paginación -->
          <div class="mt-4 flex justify-center space-x-4">
            <button
              :disabled="!computers.prev_page_url"
              @click.prevent="
                router.get(route('computers.index'), {
                  page: computers.current_page - 1,
                  search: filters.search,
                }, { preserveState: false })
              "
              class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 disabled:opacity-50"
            >
              Anterior
            </button>

            <button
              :disabled="!computers.next_page_url"
              @click.prevent="
                router.get(route('computers.index'), {
                  page: computers.current_page + 1,
                  search: filters.search,
                }, { preserveState: false })
              "
              class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 disabled:opacity-50"
            >
              Siguiente
            </button>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>