<script setup lang="ts">
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import type { PageProps as InertiaPageProps } from '@inertiajs/core'

import PublicHeader from '@/components/shared/PublicHeader.vue'
import BookmarkArticleCard from '@/components/shared/ArticleCard.vue'
import Sidebar from '@/components/shared/Sidebar.vue'
import Pagination from '@/components/shared/Pagination.vue'

const props = defineProps({
  articles: Object, // Laravel LengthAwarePaginator wrapped in a resource collection
})

const articlesData = computed(() => props.articles?.data ?? [])

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

const paginationLinks = computed(() => props.articles?.meta ?? null)
</script>

<template>
  <Head title="Articles" />

  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <PublicHeader :canRegister="true" />

    <div class="flex">
      <Sidebar
        :start-date="filters.start_date"
        :end-date="filters.end_date"
        :search="filters.search"
      />

      <div class="flex-1 p-6">
        <h2 class="text-xl text-center mt-3 font-semibold mb-4">
          Latest Articles
        </h2>

        <Pagination v-if="paginationLinks" :meta="paginationLinks" :query="filters" />

        <div class="flex flex-wrap justify-center gap-6 mt-6">
          <BookmarkArticleCard
            v-for="article in articlesData"
            :key="article.id"
            :article="article"
            cardClass="w-[250px] shrink-0"
            imageHeight="h-40"
          />
        </div>

        <Pagination v-if="paginationLinks" class="mt-6" :meta="paginationLinks" :query="filters" />
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
