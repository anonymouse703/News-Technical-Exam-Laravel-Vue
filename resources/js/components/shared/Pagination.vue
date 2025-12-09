<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

interface Link {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginationMeta {
  current_page: number;
  last_page: number;
  links: Link[];
}

const props = defineProps({
  meta: {
    type: Object as () => PaginationMeta,
    required: true
  },
  query: {
    type: Object,
    default: () => ({})
  }
});

// Filter numbered pages (remove Previous/Next or HTML entities « »)
const pagesList = computed(() =>
  props.meta?.links?.filter(
    link =>
      !link.label.includes('Previous') &&
      !link.label.includes('Next') &&
      !link.label.includes('«') &&
      !link.label.includes('»')
  ) ?? []
);

// Previous / Next buttons
const prevPage = computed(() =>
  props.meta?.links?.find(
    link => link.label.includes('Previous') || link.label.includes('«')
  ) ?? null
)

const nextPage = computed(() =>
  props.meta?.links?.find(
    link => link.label.includes('Next') || link.label.includes('»')
  ) ?? null
)

const currentPage = computed(() => props.meta?.current_page ?? 1)
const numPages = computed(() => props.meta?.last_page ?? 1)

// Navigate via Inertia preserving filters
const redirectToUrl = (url: string | null) => {
  if (url) {
    router.visit(url, { data: props.query, preserveState: true })
  }
}
</script>

<template>
  <div class="flex flex-col items-center mt-6 space-y-2">
    <div class="flex space-x-2">
      <!-- Previous Button -->
      <button
        v-if="prevPage"
        @click="redirectToUrl(prevPage.url)"
        class="px-3 py-1 rounded border bg-white text-gray-700 hover:bg-gray-100"
      >
        <ChevronLeft class="w-4 h-4 inline" />
      </button>

      <!-- Numbered Pages -->
      <button
        v-for="(page, index) in pagesList"
        :key="index"
        @click="redirectToUrl(page.url)"
        :class="[
          'px-3 py-1 rounded border',
          page.active
            ? 'bg-teal-600 text-white border-teal-600'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
        ]"
      >
        {{ page.label }}
      </button>

      <!-- Next Button -->
      <button
        v-if="nextPage"
        @click="redirectToUrl(nextPage.url)"
        class="px-3 py-1 rounded border bg-white text-gray-700 hover:bg-gray-100"
      >
        <ChevronRight class="w-4 h-4 inline" />
      </button>
    </div>

    <small>Page {{ currentPage }} of {{ numPages }}</small>
  </div>
</template>

<style scoped>
/* Optional: hover effects or spacing tweaks */
</style>
