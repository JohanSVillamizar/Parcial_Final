<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
const form = useForm({
  computer_brand: '',
  computer_model: '',
  computer_price: '',
  computer_ram_size: '',
  computer_is_laptop: false,
  category_id: '',
});
const categories = $page.props.categories || [];
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Crear Computadora</h1>

    <form @submit.prevent="form.post('/computers')">

      <label>Marca</label>
      <input v-model="form.computer_brand" type="text" />
      <div class="text-red-600">{{ form.errors.computer_brand }}</div>

      <label>Modelo</label>
      <input v-model="form.computer_model" type="text" />
      <div class="text-red-600">{{ form.errors.computer_model }}</div>

      <label>Precio</label>
      <input v-model="form.computer_price" type="number" />
      <div class="text-red-600">{{ form.errors.computer_price }}</div>

      <label>RAM (GB)</label>
      <input v-model="form.computer_ram_size" type="number" />
      <div class="text-red-600">{{ form.errors.computer_ram_size }}</div>

      <label>¿Es portátil?</label>
      <input type="checkbox" v-model="form.computer_is_laptop" />

      <label>Categoría</label>
      <select v-model="form.category_id">
        <option value="" disabled>Seleccione una categoría</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.categories_name }}
        </option>
      </select>
      <div class="text-red-600">{{ form.errors.category_id }}</div>

      <button type="submit" :disabled="form.processing">Guardar</button>
    </form>

    <Link href="/computers" class="mt-4 inline-block">Volver al listado</Link>
  </div>
</template>
