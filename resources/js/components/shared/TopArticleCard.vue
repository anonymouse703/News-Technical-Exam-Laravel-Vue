<script setup lang="ts">
import { defineProps } from 'vue'
import noImage from '../../../assets/no-image.png'

type Article = {
  id: number
  title: string
  description: string
  author: string
  url: string
  image_url: string
  published_at: string
}

const props = defineProps<{
  article: Article
  cardClass?: string
  imageHeight?: string
}>()

// fallback if image missing
function getImage() {
  if (!props.article.image_url || props.article.image_url.trim() === '') {
    return noImage
  }
  return props.article.image_url
}
</script>

<template>
  <div :class="`rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-md ${cardClass}`">
    <img
      :src="getImage()"
      @error="($event.target as HTMLImageElement).src = noImage"
      :class="`w-full ${imageHeight ?? 'h-56'} object-cover`"
    />

    <div class="p-4">
      <!-- Title -->
      <h3 class="font-bold text-lg mb-2 line-clamp-2">
        <a :href="article.url" target="_blank" class="hover:text-blue-600">
          {{ article.title }}
        </a>
      </h3>

      <!-- Description -->
      <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
        {{ article.description }}
      </p>

      <!-- Author and Published Date -->
      <div class="mt-3 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
        <span class="font-medium">By {{ article.author ?? 'N/A' }}</span>
        <span>{{ new Date(article.published_at).toLocaleDateString() }}</span>
      </div>
    </div>
  </div>
</template>

