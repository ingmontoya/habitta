<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Settings, ToggleLeft, ToggleRight, Trash2 } from 'lucide-vue-next';

// Using AppLayout directly in template

interface PaymentConcept {
    id: number;
    name: string;
    description?: string;
    type: string;
    type_label: string;
    default_amount: number;
    is_recurring: boolean;
    is_active: boolean;
    billing_cycle: string;
    billing_cycle_label: string;
    applicable_apartment_types?: number[];
    created_at: string;
    updated_at: string;
    conjunto_config: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    concept: PaymentConcept;
}>();

const toggleStatus = () => {
    router.post(
        `/payment-concepts/${props.concept.id}/toggle`,
        {},
        {
            preserveState: true,
        },
    );
};

const deleteConcept = () => {
    if (confirm('¿Estás seguro de que deseas eliminar este concepto de pago? Esta acción no se puede deshacer.')) {
        router.delete(`/payment-concepts/${props.concept.id}`, {
            onSuccess: () => {
                router.visit('/payment-concepts');
            },
        });
    }
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getTypeColor = (type: string) => {
    const colors = {
        common_expense: 'bg-blue-100 text-blue-800',
        sanction: 'bg-red-100 text-red-800',
        parking: 'bg-green-100 text-green-800',
        special: 'bg-purple-100 text-purple-800',
        late_fee: 'bg-orange-100 text-orange-800',
        other: 'bg-gray-100 text-gray-800',
    };
    return colors[type as keyof typeof colors] || 'bg-gray-100 text-gray-800';
};

const getTypeDescription = (type: string) => {
    const descriptions = {
        common_expense: 'Gastos comunes del conjunto (administración, vigilancia, etc.)',
        sanction: 'Multas por incumplimiento de normas',
        parking: 'Cuotas de parqueadero adicional',
        special: 'Conceptos especiales o extraordinarios',
        late_fee: 'Intereses por mora en pagos',
        other: 'Otros conceptos de facturación',
    };
    return descriptions[type as keyof typeof descriptions] || 'Concepto de facturación';
};

const breadcrumbs = [
    { title: 'Escritorio', href: '/dashboard' },
    { title: 'Gestión de Pagos', href: '/payments' },
    { title: 'Conceptos de Pago', href: '/payment-concepts' },
    { title: props.concept.name },
];
</script>

<template>
    <Head :title="`Concepto: ${concept.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-6xl px-4 py-8">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold tracking-tight">{{ concept.name }}</h1>
                    <p class="text-muted-foreground">
                        {{ concept.type_label }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Status Badge -->
                    <Badge :class="concept.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                        {{ concept.is_active ? 'Activo' : 'Inactivo' }}
                    </Badge>

                    <!-- Actions -->
                    <Button asChild variant="outline" size="sm">
                        <Link href="/payment-concepts">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Volver a Conceptos
                        </Link>
                    </Button>

                    <Button @click="toggleStatus" variant="outline" size="sm">
                        <component :is="concept.is_active ? ToggleLeft : ToggleRight" class="mr-2 h-4 w-4" />
                        {{ concept.is_active ? 'Desactivar' : 'Activar' }}
                    </Button>

                    <Button asChild variant="outline" size="sm">
                        <Link :href="`/payment-concepts/${concept.id}/edit`">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>

                    <Button @click="deleteConcept" variant="destructive" size="sm">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Eliminar
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Basic Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Settings class="h-5 w-5" />
                                <span>Información del Concepto</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <Label class="text-sm font-medium text-muted-foreground">Nombre</Label>
                                    <p class="text-lg font-medium">{{ concept.name }}</p>
                                </div>

                                <div>
                                    <Label class="text-sm font-medium text-muted-foreground">Tipo</Label>
                                    <div class="mt-1 flex items-center space-x-2">
                                        <Badge :class="getTypeColor(concept.type)">
                                            {{ concept.type_label }}
                                        </Badge>
                                    </div>
                                    <p class="mt-1 text-sm text-muted-foreground">
                                        {{ getTypeDescription(concept.type) }}
                                    </p>
                                </div>

                                <div>
                                    <Label class="text-sm font-medium text-muted-foreground">Monto por Defecto</Label>
                                    <p class="text-xl font-medium text-green-600">{{ formatCurrency(concept.default_amount) }}</p>
                                </div>

                                <div>
                                    <Label class="text-sm font-medium text-muted-foreground">Ciclo de Facturación</Label>
                                    <p>{{ concept.billing_cycle_label }}</p>
                                </div>

                                <div>
                                    <Label class="text-sm font-medium text-muted-foreground">Recurrente</Label>
                                    <Badge :variant="concept.is_recurring ? 'default' : 'secondary'">
                                        {{ concept.is_recurring ? 'Sí' : 'No' }}
                                    </Badge>
                                    <p class="mt-1 text-sm text-muted-foreground">
                                        {{ concept.is_recurring ? 'Se incluye en facturación automática' : 'Solo facturación manual' }}
                                    </p>
                                </div>

                                <div>
                                    <Label class="text-sm font-medium text-muted-foreground">Estado</Label>
                                    <Badge :class="concept.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                        {{ concept.is_active ? 'Activo' : 'Inactivo' }}
                                    </Badge>
                                    <p class="mt-1 text-sm text-muted-foreground">
                                        {{ concept.is_active ? 'Disponible para facturar' : 'No disponible para facturar' }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="concept.description">
                                <Label class="text-sm font-medium text-muted-foreground">Descripción</Label>
                                <p class="mt-1 text-sm">{{ concept.description }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Apartment Types -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Tipos de Apartamento Aplicables</CardTitle>
                            <CardDescription> Tipos de apartamento a los que aplica este concepto </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="!concept.applicable_apartment_types || concept.applicable_apartment_types.length === 0">
                                <div class="py-8 text-center">
                                    <p class="text-muted-foreground">Aplica a todos los tipos de apartamento</p>
                                    <p class="mt-1 text-sm text-muted-foreground">
                                        Este concepto se puede facturar a cualquier apartamento del conjunto
                                    </p>
                                </div>
                            </div>

                            <div v-else class="space-y-2">
                                <p class="text-sm text-muted-foreground">Este concepto solo aplica a los siguientes tipos de apartamento:</p>
                                <div class="flex flex-wrap gap-2">
                                    <Badge v-for="typeId in concept.applicable_apartment_types" :key="typeId" variant="secondary">
                                        Tipo ID: {{ typeId }}
                                    </Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Acciones Rápidas</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <Button asChild class="w-full" variant="default">
                                <Link :href="`/invoices/create?concept=${concept.id}`"> Crear Factura con este Concepto </Link>
                            </Button>

                            <Button asChild class="w-full" variant="outline">
                                <Link :href="`/payment-concepts/${concept.id}/edit`"> Editar Concepto </Link>
                            </Button>

                            <Button @click="toggleStatus" class="w-full" variant="outline">
                                {{ concept.is_active ? 'Desactivar' : 'Activar' }} Concepto
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Usage Stats -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Estadísticas de Uso</CardTitle>
                            <CardDescription> Información sobre el uso de este concepto </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm">Facturas generadas:</span>
                                    <span class="font-medium">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm">Total facturado:</span>
                                    <span class="font-medium">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm">Último uso:</span>
                                    <span class="text-sm text-muted-foreground">--</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Metadata -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Información del Sistema</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Conjunto</Label>
                                <p class="text-sm">{{ concept.conjunto_config.name }}</p>
                            </div>

                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Creado</Label>
                                <p class="text-sm">{{ formatDate(concept.created_at) }}</p>
                            </div>

                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Última Actualización</Label>
                                <p class="text-sm">{{ formatDate(concept.updated_at) }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
