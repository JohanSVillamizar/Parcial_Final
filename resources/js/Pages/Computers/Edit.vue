<script setup>
import { useForm, Link } from '@inertiajs/vue3';
const props = $page.props;
const categories = props.categories || [];
const computer = props.computer;

const form = useForm({
  computer_brand: computer.computer_brand,
  computer_model: computer.computer_model,
  computer_price: computer.computer_price,
  computer_ram_size: computer.computer_ram_size,
  computer_is_laptop: computer.computer_is_laptop,
  category_id: computer.category_id,
});
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Editar Computadora</h1>

    <form @submit.prevent="form.put(`/computers/${computer.id_computer}`)">

      <!-- Campos igual que en Create.vue -->
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
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.categories_name }}</option>
      </select>
      <div class="text-red-600">{{ form.errors.category_id }}</div>

      <button type="submit" :disabled="form.processing">Actualizar</button>
    </form>

    <Link href="/computers" class="mt-4 inline-block">Volver al listado</Link>
  </div>
</template>
