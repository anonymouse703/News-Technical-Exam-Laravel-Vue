<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router  } from '@inertiajs/vue3';
import PublicHeader from '@/components/shared/PublicHeader.vue';
import TopArticleCard from '@/components/shared/TopArticleCard.vue';
import LatestArticleCard from '@/components/shared/LatestArticleCard.vue';

type Article = {
  id: number;
  title: string;
  description: string;
  author: string;
  url: string;
  image_url: string;
  published_at: string;
};

type Paginator<T> = {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  next_page_url: string | null;
  prev_page_url: string | null;
};

const props = defineProps<{
  articles?: Paginator<Article>;
  canRegister?: boolean;
}>();

const layout = ref<'fixed' | 'responsive' | 'full'>('responsive');

const articles = computed(() => props.articles ?? {
  data: [],
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  next_page_url: null,
  prev_page_url: null,
});

const firstFive = computed(() => articles.value.data.slice(0, 5));
const nextFive = computed(() => articles.value.data.slice(5, 15));

const cardClass = computed(() => {
  switch (layout.value) {
    case 'fixed':
      return 'w-[300px] shrink-0 snap-start';
    case 'responsive':
      return 'shrink-0 snap-start w-[250px] sm:w-[300px] md:w-[350px] lg:w-[400px]';
    case 'full':
      return 'min-w-full snap-start';
    default:
      return '';
  }
});

const loadMoreNews = () => {
  router.get('/articles'); 
};


</script>

<template>

  <Head title="News Portal" />

  <div class="flex min-h-screen flex-col bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a]">

    <header class="w-full border-b border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center">
      <PublicHeader :canRegister="true" />
    </header>

    <main class="flex-1 container mx-auto p-6">


      <h2 class="text-xl font-semibold mb-3">Top Stories</h2>

      <div class="relative overflow-hidden">
        <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-4">

          <TopArticleCard
            v-for="article in firstFive"
            :key="article.id"
            :article="article"
            :cardClass="cardClass"
            imageHeight="h-56"
          />

        </div>
      </div>


      <h2 class="text-xl font-semibold mt-10 mb-3">Latest News</h2>

      <div class="flex overflow-x-auto gap-4 scrollbar-hide pb-4">

        <LatestArticleCard
          v-for="article in nextFive"
          :key="article.id"
          :article="article"
          cardClass="w-[250px] shrink-0"
          imageHeight="h-40"
        />

      </div>


      <div class="mt-6 flex justify-center items-center gap-4">
        
        <button
          class="px-3 py-1 bg-slate-800 hover:bg-slate-950 text-white text-3xl p-2 border rounded"
          @click="loadMoreNews"
        >
          More News
        </button>

      </div>

    </main>
  </div>
</template>
