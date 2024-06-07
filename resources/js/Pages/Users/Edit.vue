<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { notify } from "@kyvg/vue3-notification";



const { props } = usePage();
const user = props.user;
const provinces = props.provinces;

const kabupatens = ref(props.kabupatens);
const kecamatans = ref(props.kecamatans);

const form = useForm({
    name: user.name,
    email: user.email,
    provinsi: user.provinsi_id,
    kabupaten: user.kabupaten_id,
    kecamatan: user.kecamatan_id,
    password: '',
    password_confirmation: '',
});

const fetchKabupaten = async () => {
    if (form.provinsi) {
        await fetch(`/users/get-regencies/${form.provinsi}`)
            .then(response => response.json())
            .then(data => {
                kabupatens.value = data;
                kecamatans.value = [];
            });
    } else {
        kabupatens.value = [];
        kecamatans.value = [];
    }
};

const fetchKecamatan = async () => {
    if (form.kabupaten) {
        await fetch(`/users/get-districts/${form.kabupaten}`)
            .then(response => response.json())
            .then(data => {
                kecamatans.value = data;
            });
    } else {
        kecamatans.value = [];
    }
};

const fetchChangeFormValue = async () => {
    const responsePro = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/province/${form.provinsi}.json`);
    const responseKab = await fetch(`/users/get-regencies/${form.provinsi}`);
    const responseKec = await fetch(`/users/get-districts/${form.kabupaten}`);
    const dataPro = await responsePro.json();
    const dataKab = await responseKab.json();
    const dataKec = await responseKec.json();
    const provinsi = dataPro.name
    const kabupaten = dataKab.find(item => item.id === form.kabupaten);
    const kecamatan = dataKec.find(item => item.id === form.kecamatan);
    if (kabupaten && kecamatan && provinsi) {
        form.provinsi = provinsi;
        form.kabupaten = kabupaten.name;
        form.kecamatan = kecamatan.name;
    }
};

// watch(() => form.provinsi, fetchKabupaten);
// watch(() => form.kabupaten, fetchKecamatan);

const submit = async () => {

    await fetchChangeFormValue();
    console.log(form)

    form.patch(route('user.update', user.id), {
        onFinish: () => {
            form.reset('password', 'password_confirmation'),
                notify({
                    type: "success",
                    text: "Successfull update user!",
                })
        }
    });
};

</script>

<template>
    <notifications />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create User</h2>
        </template>

        <Head title="Create" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" value="Name" />

                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus
                                    autocomplete="name" />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email" />

                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                                    autocomplete="username" />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="provinsi" value="Provinsi" />

                                <select v-model="form.provinsi" @change="fetchKabupaten"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="" disabled>Select Provinsi</option>
                                    <option v-for="province in provinces" :key="province.id" :value="province.id">{{
                                        province.name
                                        }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.provinsi" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="kabupaten" value="Kabupaten/Kota" />

                                <select v-model="form.kabupaten" @change="fetchKecamatan"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="" disabled>Select Kabupaten</option>
                                    <option v-for="kabupaten in kabupatens" :key="kabupaten.id" :value="kabupaten.id">
                                        {{
                                            kabupaten.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.kabupaten" />
                            </div>
                            <div class="mt-4">
                                <InputLabel for="kecamatan" value="Kecamatan" />

                                <select v-model="form.kecamatan"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="" disabled>Select Kecamatan</option>
                                    <option v-for="kecamatan in kecamatans" :key="kecamatan.id" :value="kecamatan.id">
                                        {{
                                            kecamatan.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.kecamatan" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password" value="Password" />

                                <TextInput id="password" type="password" class="mt-1 block w-full"
                                    v-model="form.password" autocomplete="new-password" />

                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password_confirmation" value="Confirm Password" />

                                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                    v-model="form.password_confirmation" autocomplete="new-password" />

                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <div class="flex items-center justify-center mt-4">


                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Create
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </AuthenticatedLayout>

</template>
