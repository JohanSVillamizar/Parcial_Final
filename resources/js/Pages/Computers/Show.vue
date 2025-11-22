<script setup>
const computer = $page.props.computer;
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Detalle Computadora</h1>

    <p><strong>Marca:</strong> {{ computer.computer_brand }}</p>
    <p><strong>Modelo:</strong> {{ computer.computer_model }}</p>
    <p><strong>Precio:</strong> {{ computer.computer_price }}</p>
    <p><strong>RAM:</strong> {{ computer.computer_ram_size }} GB</p>
    <p><strong>Portátil:</strong> {{ computer.computer_is_laptop ? 'Sí' : 'No' }}</p>
    <p><strong>Categoría:</strong> {{ computer.category?.categories_name || 'N/A' }}</p>

    <Link href="/computers" class="mt-4 inline-block">Volver al listado</Link>
  </div>
</template>
