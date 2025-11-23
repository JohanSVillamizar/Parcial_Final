<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  computer: Object,
});
</script>

<template>
  <AppLayout>

    <Head :title="`Computador: ${props.computer.computer_brand}`" />

    <div class="max-w-5xl mx-auto mt-8 bg-white shadow rounded p-6">

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">
          Detalles del Computador
        </h1>

        <Link
          :href="route('computers.index')"
          class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-900"
        >
          Volver
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Marca</h2>
          <p class="text-gray-800">{{ props.computer.computer_brand }}</p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Modelo</h2>
          <p class="text-gray-800">{{ props.computer.computer_model }}</p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Precio</h2>
          <p class="text-gray-800">$ {{ props.computer.computer_price }}</p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">RAM</h2>
          <p class="text-gray-800">{{ props.computer.computer_ram_size }} GB</p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Tipo</h2>
          <span
            class="px-3 py-1 rounded text-white"
            :class="props.computer.computer_is_laptop ? 'bg-blue-600' : 'bg-gray-600'"
          >
            {{ props.computer.computer_is_laptop ? "Laptop" : "Escritorio" }}
          </span>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Categoría</h2>
          <p class="text-gray-800">
            {{ props.computer.category?.categories_name ?? "Sin categoría" }}
          </p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Código de Barras</h2>
          <p class="text-gray-800 font-mono tracking-wider">
            {{ props.computer.computers_barcode }}
          </p>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-gray-600 mb-2">Fecha de creación</h2>
          <p class="text-gray-800">
            {{ new Date(props.computer.created_at).toLocaleDateString() }}
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
