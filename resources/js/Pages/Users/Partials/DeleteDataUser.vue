<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { notify } from "@kyvg/vue3-notification";


// Define emits
const emit = defineEmits(['deleted']);
const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
})
const form = useForm({});

const confirmingUserDeletion = ref(false);


const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = (id) => {
    form.delete(route('user.destroy', id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => {
            emit('deleted', id),
                notify({ type: "success", text: props.user.name + ' Deleted!' });
        }
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section class="space-y-6">


        <DangerButton @click="confirmUserDeletion" class="px-2">Delete</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete {{ user.name }}?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once this account is deleted, all of its resources and data will be permanently deleted.
                </p>


                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton class="ms-3 px-4" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing" @click="deleteUser(user.id)">
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
