<script setup>
import { ref, watch } from "vue";

import Layout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    posts: Object,
    filters:Object
});

const form = useForm();

const search = ref(props.filters.search);
const perPage = ref(10);


watch(search,(value) => {
    const debouncer = createDebounce();

    debouncer(() => {
        Inertia.get('/posts',{
                search: value,
                perPage: perPage.value,
            },
            {
                preserveState:true,
                replace:true,
            }
        );
     })
})

const changePerPage = () => {
    Inertia.get('/posts',{
            search: value,
            perPage: perPage.value,
        },
        {
            preserveState:true,
            replace:true,
        }
    );
}

function createDebounce() {
    let timeout = null;
    return function (fnc, delayMs) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        fnc();
        }, delayMs || 500);
    };
}

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('posts.destroy', id));
    }
}
</script>
<template>
    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laravel 9 Vue JS CRUD App using Vite Example - LaravelTuts.com
            </h2>
        </template>
        <Head title="Dashboard" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div className="flex items-center justify-between mb-6">
                            <Link
                                className="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none"
                                :href="route('posts.create')"
                            >
                                Create Post
                            </Link>
                        </div>

                        <div class="mb-4 flex">
                            <div class="flex-1 pr-4">
                                <div class="relative md:w-1/3">
                                    <input type="search"
                                        v-model="search"
                                        class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                                        placeholder="Buscar...">

                                    <div class="absolute top-0 left-0 inline-flex items-center p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                            <circle cx="10" cy="10" r="7" />
                                            <line x1="21" y1="21" x2="15" y2="15" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <select v-model="perPage"
                                    @change="changePerPage"
                                    class="
                                        py-3
                                        w-full
                                        rounded-md
                                        bg-gray-100
                                        border-transparent
                                        focus:border-gray-500 focus:bg-white focus:ring-0
                                        text-sm
                                    "
                                >
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                        </div>

                        <table className="table-fixed w-full pt-3">
                            <thead>
                                <tr className="bg-gray-100">
                                    <th className="px-4 py-2 w-20">No.</th>
                                    <th className="px-4 py-2">Title</th>
                                    <th className="px-4 py-2">Body</th>
                                    <th className="px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts.data" :key="post.id">
                                    <td className="border px-4 py-2">{{ post.id }}</td>
                                    <td className="border px-4 py-2">{{ post.title }}</td>
                                    <td className="border px-4 py-2">{{ post.body }}</td>
                                    <td className="border px-4 py-2">
                                        <Link
                                            tabIndex="1"
                                            className="px-4 py-2 text-sm text-white bg-blue-500 rounded"
                                            :href="route('posts.edit', post.id)"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="destroy(post.id)"
                                            tabIndex="-1"
                                            type="button"
                                            className="mx-1 px-4 py-2 text-sm text-white bg-red-500 rounded"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <Pagination class="mt-6" :links="posts.links" />
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
