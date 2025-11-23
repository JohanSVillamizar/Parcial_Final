<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, Link, usePage } from "@inertiajs/vue3";

// Datos enviados desde el backend
const page = usePage();

// Form con los mismos campos que espera el FormRequest
const form = useForm({
  computer_brand: "",
  computer_model: "",
  computer_price: "",
  computer_ram_size: "",
  computer_is_laptop: false,
  category_id: "",
  computers_barcode: "",
});
</script>

<template>
  <AppLayout title="Crear Nueva Computadora">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Crear Nueva Computadora
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-6">

          <h1 class="text-2xl font-bold mb-6">Registrar computadora</h1>

          <form @submit.prevent="form.post(route('computers.store'))" class="space-y-6">

            <div>
              <label class="block font-medium text-gray-700">Marca *</label>
              <input type="text" v-model="form.computer_brand" required minlength="2" maxlength="100"
                class="mt-1 block w-full border rounded p-2" placeholder="Dell, HP, Lenovo..." />
              <div v-if="form.errors.computer_brand" class="text-red-600 text-sm">
                {{ form.errors.computer_brand }}
              </div>
            </div>

            <div>
              <label class="block font-medium text-gray-700">Modelo *</label>
              <input type="text" v-model="form.computer_model" required minlength="2" maxlength="100"
                class="mt-1 block w-full border rounded p-2" placeholder="Inspiron, Pavilion..." />
              <div v-if="form.errors.computer_model" class="text-red-600 text-sm">
                {{ form.errors.computer_model }}
              </div>
            </div>

            <div>
              <label class="block font-medium text-gray-700">Precio (USD) *</label>
              <input type="number" v-model="form.computer_price" required min="0" step="0.01"
                class="mt-1 block w-full border rounded p-2" />
              <div v-if="form.errors.computer_price" class="text-red-600 text-sm">
                {{ form.errors.computer_price }}
              </div>
            </div>

            <div>
              <label class="block font-medium text-gray-700">Memoria RAM (GB) *</label>
              <input type="number" v-model="form.computer_ram_size" required min="1"
                class="mt-1 block w-full border rounded p-2" />
              <div v-if="form.errors.computer_ram_size" class="text-red-600 text-sm">
                {{ form.errors.computer_ram_size }}
              </div>
            </div>

            <div class="flex items-center gap-2">
              <input type="checkbox" v-model="form.computer_is_laptop" class="h-4 w-4" />
              <label class="font-medium">Es portátil</label>
            </div>

            <div>
              <label class="block font-medium text-gray-700">Categoría *</label>
              <select v-model="form.category_id" required class="mt-1 block w-full border rounded p-2">
                <option value="">Seleccione una categoría</option>
                <option v-for="cat in page.props.categories" :key="cat.id" :value="cat.id">
                  {{ cat.categories_name }}
                </option>
              </select>
              <div v-if="form.errors.category_id" class="text-red-600 text-sm">
                {{ form.errors.category_id }}
              </div>
            </div>

            <div>
              <label class="block font-medium text-gray-700">Código de barras *</label>

              <input type="text" v-model="form.computers_barcode"
                @input="form.computers_barcode = form.computers_barcode.toUpperCase()" required minlength="12"
                maxlength="12" pattern="[A-Z0-9]{12}" class="mt-1 block w-full border rounded p-2"
                placeholder="Ejemplo: A1B2C3D4E5F6" />

              <p class="text-gray-500 text-sm mt-1">
                Debe contener <strong>12 caracteres</strong>, solo <strong>letras mayúsculas</strong>
                y <strong>números</strong>.
              </p>

              <p v-if="form.errors.computers_barcode" class="text-red-600 text-sm mt-1">
                {{ form.errors.computers_barcode }}
              </p>
            </div>

            <div class="flex justify-end gap-3">
              <Link :href="route('computers.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
              Cancelar
              </Link>

              <button type="submit" :disabled="form.processing"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Guardar
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
