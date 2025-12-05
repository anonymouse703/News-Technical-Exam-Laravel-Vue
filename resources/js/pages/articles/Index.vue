<script setup lang="ts">
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import PublicHeader from '@/components/shared/PublicHeader.vue'
import BookmarkArticleCard from '@/components/shared/ArticleCard.vue'

const props = defineProps({
  articles: Object,
})

const seriesData = computed(() => props.articles?.data ?? [])

</script>

<template>
  <Head title="Articles" />

  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <PublicHeader :canRegister="true" />

    <h2 class="text-xl text-center mt-3 font-semibold mb-4">Latest Articles</h2>

    <div class="flex flex-wrap justify-center gap-6">
      <BookmarkArticleCard
        v-for="article in seriesData"
        :key="article.id"
        :article="article"
        cardClass="w-[250px] shrink-0"
        imageHeight="h-40"
      />
    </div>
  </div>
</template>

