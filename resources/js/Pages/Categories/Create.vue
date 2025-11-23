<!--
Profe,  se implementó la validación en frontend por requerimiento según el documento del parcial,
para mejorar la experiencia del usuario con retroalimentación inmediata.
Sin embargo, también existen validaciones en el backend (FormRequest)
pues creo que el frontend nunca es seguro: un usuario podría manipular el navegador,
desactivar validaciones o enviar datos vía Postman. Por ello apliqué una
**validación doble**: frontend + backend.
-->

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// Formulario usando los mismos campos que el backend
const form = useForm({
    categories_name: '',
    categories_description: '',
    categories_status: true,
    categories_created_date: new Date().toISOString().slice(0, 10) // YYYY-MM-DD
});

const page = usePage();
</script>

<template>
    <AppLayout title="Crear Categoría">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear nueva categoría
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300"
                >
                    {{ page.props.flash.success }}
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300"
                >
                    {{ page.props.flash.error }}
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="form.post(route('categories.store'))" class="space-y-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Nombre *
                            </label>
                            <input
                                type="text"
                                v-model="form.categories_name"
                                required
                                minlength="3"
                                maxlength="100"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                            <div v-if="form.errors.categories_name" class="text-red-600 text-sm mt-1">
                                {{ form.errors.categories_name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Descripción
                            </label>
                            <textarea
                                v-model="form.categories_description"
                                maxlength="255"
                                rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            ></textarea>
                            <div v-if="form.errors.categories_description" class="text-red-600 text-sm mt-1">
                                {{ form.errors.categories_description }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Estado *
                            </label>

                            <select
                                v-model="form.categories_status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option :value="true">Activo</option>
                                <option :value="false">Inactivo</option>
                            </select>

                            <div v-if="form.errors.categories_status" class="text-red-600 text-sm mt-1">
                                {{ form.errors.categories_status }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Fecha creación *
                            </label>

                            <input
                                type="date"
                                v-model="form.categories_created_date"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />

                            <div v-if="form.errors.categories_created_date" class="text-red-600 text-sm mt-1">
                                {{ form.errors.categories_created_date }}
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <Link
                                :href="route('categories.index')"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 text-sm font-semibold"
                            >
                                Cancelar
                            </Link>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-semibold"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
