<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  category: Object,
  computers: Array,
});
</script>

<template>
  <AppLayout>

    <Head :title="`Detalles de: ${props.category.categories_name}`" />

    <div class="max-w-5xl mx-auto mt-8 bg-white shadow rounded p-6">

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">
          Detalles de la Categoría
        </h1>

        <Link :href="route('categories.index')" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-900">
        Volver
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Nombre</h2>
          <p class="text-gray-800">{{ props.category.categories_name }}</p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Estado</h2>
          <span class="px-3 py-1 rounded text-white"
            :class="props.category.categories_status ? 'bg-green-600' : 'bg-red-600'">
            {{ props.category.categories_status ? "Activa" : "Inactiva" }}
          </span>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Descripción</h2>
          <p class="text-gray-800">
            {{ props.category.categories_description ?? "Sin descripción" }}
          </p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Fecha de creación</h2>
          <p class="text-gray-800">
            {{ new Date(props.category.categories_created_date).toLocaleDateString() }}
          </p>
        </div>

      </div>

      <div>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">
          Computadores asociados ({{ props.computers.length }})
        </h2>

        <div v-if="props.computers.length === 0" class="text-gray-600 italic">
          No hay computadores en esta categoría.
        </div>
        <table class="min-w-full bg-white shadow border border-gray-200 rounded-lg">
          <thead class="bg-gray-50 border-b">
            <tr>
              <th class="py-2 px-4 text-left font-semibold text-gray-700">ID</th>
              <th class="py-2 px-4 text-left font-semibold text-gray-700">Marca</th>
              <th class="py-2 px-4 text-left font-semibold text-gray-700">Modelo</th>
              <th class="py-2 px-4 text-left font-semibold text-gray-700">Tipo</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pc in computers" :key="pc.id_computer" class="border-b hover:bg-gray-100 transition">
              <td class="py-2 px-4">{{ pc.id_computer }}</td>
              <td class="py-2 px-4">{{ pc.computer_brand }}</td>
              <td class="py-2 px-4">{{ pc.computer_model }}</td>
              <td class="py-2 px-4">
                <span :class="pc.computer_is_laptop ? 'text-blue-600' : 'text-gray-700'" class="font-semibold">
                  {{ pc.computer_is_laptop ? 'Laptop' : 'Escritorio' }}
                </span>
              </td>
            </tr>
            <tr v-if="computers.length === 0">
              <td colspan="4" class="text-center py-4 text-gray-500">
                No hay computadores asociados.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>