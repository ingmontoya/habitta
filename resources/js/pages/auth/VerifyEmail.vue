<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <AuthLayout
        title="Verificar correo electrónico"
        description="Por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar."
    >
        <Head title="Verificación de Correo" />

        <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
            Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.
        </div>

        <form @submit.prevent="submit" class="space-y-6 text-center">
            <Button :disabled="form.processing" variant="secondary">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                Reenviar correo de verificación
            </Button>

            <TextLink :href="route('logout')" method="post" as="button" class="mx-auto block text-sm">Cerrar sesión</TextLink>
        </form>
    </AuthLayout>
</template>
