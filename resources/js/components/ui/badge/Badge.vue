<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue'
import { type VariantProps, cva } from 'class-variance-authority'

import { cn } from '@/lib/utils'

const badgeVariants = cva(
  'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
  {
    variants: {
      variant: {
        default:
          'border-transparent bg-primary text-primary-foreground hover:bg-primary/80',
        secondary:
          'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
        destructive:
          'border-transparent bg-destructive text-destructive-foreground hover:bg-destructive/80',
        outline: 'text-foreground',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  },
)

interface Props extends /* @vue-ignore */ HTMLAttributes {
  variant?: VariantProps<typeof badgeVariants>['variant']
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
})

const delegatedProps = computed(() => {
  const { variant: _, ...delegated } = props
  return delegated
})
</script>

<template>
  <div v-bind="delegatedProps" :class="cn(badgeVariants({ variant }), props.class)">
    <slot />
  </div>
</template>
