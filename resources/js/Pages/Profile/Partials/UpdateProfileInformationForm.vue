<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    apellido: props.user.apellido,
    email: props.user.email,
    telefono: props.user.telefono,
    documento_identidad: props.user.documento_identidad,
    ciudad: props.user.ciudad,
    direccion: props.user.direccion,
    genero: props.user.genero,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = async () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    const requiredFields = ['name', 'apellido', 'telefono', 'documento_identidad', 'ciudad', 'direccion', 'genero'];
    for (const field of requiredFields) {
        if (!form[field]) {
            form.errors[field] = 'Este campo es obligatorio';
            return;
        } else {
            delete form.errors[field];
        }
    }

    try {
        await form.post(route('user-profile-information.update'), {
            errorBag: 'updateProfileInformation',
            preserveScroll: true,
        });
        
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'La información del perfil se ha actualizado correctamente.',
        });

        clearPhotoFileInput();
    } catch (error) {
        const errors = error?.props?.errors || {};
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errors.email ? errors.email[0] : (errors.documento_identidad ? errors.documento_identidad[0] : 'Ocurrió un error al actualizar la información del perfil. Por favor, inténtalo de nuevo.'),
        });
    }
};


const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>
<!-- De aca para abajo mdificar -->
<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Informacion del perfil
        </template>

        <template #description>
            Ingresa tus datos para tener un mejor control de tu usuario
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <InputLabel for="photo" value="Foto" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Seleccionar una foto
                </SecondaryButton>

                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Quitar foto
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Nombre:" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                    autocomplete="name" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="Correo Electronico:" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                    autocomplete="username" />
                <InputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Su dirección de correo electrónico no está verificada.

                        <Link :href="route('verification.send')" method="post" as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click.prevent="sendEmailVerification">
                        Haga clic aquí para volver a enviar el correo electrónico de verificación.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                    </div>
                </div>
            </div>
            <!-- Apellido -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="apellido" value="Apellidos:" />
                <TextInput id="apellido" v-model="form.apellido" type="text" class="mt-1 block w-full" required />
                <InputError :message="form.errors.apellido" class="mt-2" />
            </div>
            <!-- Teléfono -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="telefono" value="Teléfono:" />
                <TextInput id="telefono" v-model="form.telefono" type="text" class="mt-1 block w-full" required />
                <InputError :message="form.errors.telefono" class="mt-2" />
            </div>

            <!-- Documento de Identidad -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="documento_identidad" value="Carnet de identidad:" />
                <TextInput id="documento_identidad" v-model="form.documento_identidad" type="text"
                    class="mt-1 block w-full" required />
                <InputError :message="form.errors.documento_identidad" class="mt-2" />
            </div>

            <!-- Ciudad -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="ciudad" value="Ciudad:" />
                <TextInput id="ciudad" v-model="form.ciudad" type="text" class="mt-1 block w-full" required />
                <InputError :message="form.errors.ciudad" class="mt-2" />
            </div>

            <!-- Dirección -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="direccion" value="Dirección:" />
                <TextInput id="direccion" v-model="form.direccion" type="text" class="mt-1 block w-full" required />
                <InputError :message="form.errors.direccion" class="mt-2" />
            </div>

            <!-- Género -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="genero" value="Género:" />
                <select id="genero" v-model="form.genero" class="mt-1 block w-full" required>
                    <option value="Masculino" :selected="form.genero === 'Masculino'">Masculino</option>
                    <option value="Femenino" :selected="form.genero === 'Femenino'">Femenino</option>
                    <option value="Otro" :selected="form.genero === 'Otro'">Otro</option>
                </select>
                <InputError :message="form.errors.genero" class="mt-2" />
            </div>

        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Guardado exitosamente.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
