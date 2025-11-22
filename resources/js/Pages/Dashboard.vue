<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel principal
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tarjeta Computers -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Computadoras
                        </h3>
                        <p class="text-sm text-gray-600">
                            Administración de las computadoras registradas en el sistema.
                        </p>
                    </div>

                    <div class="mt-4">
                        <!-- Botón que navega al index de Computers (ruta resource 'computers.index') -->
                        <Link
                            :href="route('computers.index')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Ir al listado de Computadoras
                        </Link>
                    </div>
                </div>

                <!-- Tarjeta Categories -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Categorías
                        </h3>
                        <p class="text-sm text-gray-600">
                            Administración de las categorías asociadas a los computadores.
                        </p>
                    </div>

                    <div class="mt-4">
                        <!-- Botón que navega al index de Categories (ruta resource 'categories.index') -->
                        <Link
                            :href="route('categories.index')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Ir al listado de Categorías
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
