<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Bookmark, Newspaper } from 'lucide-vue-next'

const page = usePage()

const currentUrl = page.url
const isBookmarksPage = currentUrl.startsWith('/bookmarks')
const isArticlesPage = currentUrl.startsWith('/articles')

const props = defineProps({
  startDate: String,
  endDate: String,
  search: String,
})

const startDate = ref(props.startDate ?? '')
const endDate = ref(props.endDate ?? '')

const applyFilters = () => {
  // Determine which route to use
  const route = isBookmarksPage ? '/bookmarks' : '/articles'
  
  router.get(route, {
    search: props.search,
    start_date: startDate.value,
    end_date: endDate.value,
  })
}

// You can also add a computed property for the button text if you want
const applyButtonText = computed(() => {
  return `Apply Filters to ${isBookmarksPage ? 'Bookmarks' : 'Articles'}`
})

const goToBookmarks = () => router.get('/bookmarks')
const goToArticles = () => router.get('/articles')
</script>

<template>
  <aside class="w-64 h-full p-4 border-r border-gray-300 dark:border-gray-700 space-y-6">

    <button
      v-if="!isBookmarksPage"
      @click="goToBookmarks"
      class="flex items-center gap-2 w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-100 dark:hover:bg-gray-200 rounded-lg"
    >
      <Bookmark class="w-4 h-4" />
      <span class="font-medium">My Bookmarks</span>
    </button>

    <button
      v-if="!isArticlesPage"
      @click="goToArticles"
      class="flex items-center gap-2 w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-100 dark:hover:bg-gray-200 rounded-lg"
    >
      <Newspaper class="w-4 h-4" />
      <span class="font-medium">Articles</span>
    </button>

    <div>
      <h3 class="font-semibold mb-2">Filter by Date</h3>

      <label class="block text-sm mb-1">Start Date</label>
      <input
        v-model="startDate"
        type="date"
        class="w-full mb-3 rounded-lg border px-3 py-2 dark:bg-slate-800 dark:border-gray-600"
      />

      <label class="block text-sm mb-1">End Date</label>
      <input
        v-model="endDate"
        type="date"
        class="w-full rounded-lg border px-3 py-2 dark:bg-slate-800 dark:border-gray-600"
      />

      <button
        @click="applyFilters"
        class="mt-3 w-full bg-teal-700 text-white py-2 rounded-lg hover:bg-teal-800"
      >
        {{ applyButtonText }}
      </button>
    </div>
  </aside>
</template>