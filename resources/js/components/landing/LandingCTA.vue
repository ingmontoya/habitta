<template>
    <section class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 px-6 py-20">
        <div class="mx-auto max-w-4xl text-center">
            <div class="rounded-3xl border border-white/20 bg-gradient-to-r from-white/10 to-white/5 p-12 backdrop-blur-lg">
                <h2 class="mb-6 text-4xl font-bold text-white lg:text-5xl">
                    {{ $t('cta.title') }}
                    <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        {{ $t('cta.titleHighlight') }}
                    </span>
                </h2>
                <p class="mx-auto mb-8 max-w-2xl text-xl text-white/80">
                    {{ $t('cta.description') }}
                </p>

                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="transform rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 px-3 py-2 sm:px-8 sm:py-4 text-xs sm:text-lg font-semibold text-white shadow-2xl transition-all duration-200 hover:-translate-y-1 hover:from-cyan-600 hover:to-blue-700 hover:shadow-cyan-500/25 flex-shrink-0"
                    >
                        <span class="d-none sm:hidden">{{ $t('cta.primary') }}</span>
                        <span class="hidden sm:inline">{{ $t('cta.primaryFull') }}</span>
                    </Link>
                    <button class="rounded-xl border-2 border-white/20 px-3 py-2 sm:px-8 sm:py-4 text-xs sm:text-lg font-semibold text-white transition-all duration-200 hover:bg-white/10 flex-shrink-0">
                        <span class="sm:hidden">{{ $t('cta.secondary') }}</span>
                        <span class="hidden sm:inline">{{ $t('cta.secondaryFull') }}</span>
                    </button>
                </div>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-white/60">
                    <span v-for="benefit in benefits" :key="benefit" class="flex items-center space-x-2">
                        <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span>{{ benefit }}</span>
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const benefits = computed(() => [
    t('cta.benefits.freeTrial'),
    t('cta.benefits.noCard'),
    t('cta.benefits.support'),
    t('cta.benefits.implementation'),
]);
</script>
