<script setup lang="ts">
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import type { PageProps as InertiaPageProps } from '@inertiajs/core'
import PublicHeader from '@/components/shared/PublicHeader.vue'
import BookmarkArticleCard from '@/components/shared/BookMarkArticleCard.vue'
import Sidebar from '@/components/shared/Sidebar.vue'

const props = defineProps({
  bookmarks: Object,
})

const articlesData = computed(() => props.bookmarks?.data ?? [])

interface Filters {
  search: string
  start_date: string
  end_date: string
}

interface PageProps extends InertiaPageProps {
  filters: Filters
}

const page = usePage<PageProps>()

const filters = computed(() => ({
  search: page.props.filters?.search ?? '',
  start_date: page.props.filters?.start_date ?? '',
  end_date: page.props.filters?.end_date ?? ''
}))
</script>

<template>

  <Head title="Articles" />

  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <PublicHeader :canRegister="true" />

    <div class="flex">
      <Sidebar :start-date="filters.start_date" :end-date="filters.end_date" :search="filters.search" />

      <div class="flex-1 p-6">
        <h2 class="text-xl text-center mt-3 font-semibold mb-4">
          Bookmarked Articles
        </h2>

        <div class="flex flex-wrap justify-center gap-6 mt-10">
          <BookmarkArticleCard v-for="bookmark in articlesData" :key="bookmark.id" :article="bookmark.article"
            cardClass="w-[250px] shrink-0" imageHeight="h-40" />
        </div>
      </div>
    </div>
  </div>
</template>
