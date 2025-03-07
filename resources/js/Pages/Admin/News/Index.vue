<script setup>
import { ref, onMounted, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import NewsButton from '@/Components/NewsButton.vue';

// Get news data from props
const { props } = usePage();
const newsItems = ref(props.newsItems);
const allNews = ref(props.newsItems);
const activeCategory = ref(props.activeCategory || 'all');
const searchQuery = ref('');
const currentSlide = ref(0);

// Pagination state
const currentPage = ref(1);
const pageSize = ref(10);
const pageSizeOptions = [10, 20, 25, 50, 100];

// Group news by date
const groupedNews = computed(() => {
  const groups = {};
  const startIndex = (currentPage.value - 1) * pageSize.value;
  const endIndex = startIndex + pageSize.value;
  
  newsItems.value
    .slice()
    .sort((a, b) => new Date(b.tanggal_terbit) - new Date(a.tanggal_terbit))
    .slice(startIndex, endIndex)
    .forEach(news => {
      const date = new Date(news.tanggal_terbit);
      const dateKey = `${date.getDate()} ${['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][date.getMonth()]} ${date.getFullYear()}`;
      if (!groups[dateKey]) {
        groups[dateKey] = [];
      }
      groups[dateKey].push(news);
    });
  return groups;
});

// Featured news for carousel (latest 3 news items)
const featuredNews = computed(() => {
  return [...allNews.value]
    .sort((a, b) => new Date(b.tanggal_terbit) - new Date(a.tanggal_terbit))
    .slice(0, 3);
});

// Compute total pages
const totalPages = computed(() => {
  return Math.ceil(newsItems.value.length / pageSize.value);
});

// Generate page numbers for pagination
const pageNumbers = computed(() => {
  const pages = [];
  const maxVisiblePages = 5;
  let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1);

  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  return pages;
});

// Handle page size change
const handlePageSizeChange = (event) => {
  pageSize.value = parseInt(event.target.value);
  currentPage.value = 1; // Reset to first page when changing page size
};

// Handle page change
const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

// Carousel auto-rotation
const startCarousel = () => {
  setInterval(() => {
    currentSlide.value = (currentSlide.value + 1) % featuredNews.value.length;
  }, 3000);
};

// Filter news by category
const filterByCategory = (category) => {
  activeCategory.value = category;
  newsItems.value = allNews.value.filter((news) =>
    news.kategori.includes(category)
  );
};

// Search news
const searchNews = () => {
  activeCategory.value = 'all';
  newsItems.value = allNews.value.filter((news) => {
    const query = searchQuery.value.toLowerCase();
    return (
      news.judul.toLowerCase().includes(query) ||
      news.isi_berita.toLowerCase().includes(query) ||
      news.penulis.toLowerCase().includes(query)
    );
  });
};

// Delete news method
const deleteNews = async (id) => {
  if (confirm('Are you sure you want to delete this news?')) {
    router.delete(`/news/${id}`, {
      onSuccess: () => {
        newsItems.value = newsItems.value.filter(news => news.id !== id);
        allNews.value = allNews.value.filter(news => news.id !== id);
      },
    });
  }
};


// Check if category is active
const isActive = (category) => activeCategory.value === category;

// Truncate text helper
const truncateText = (text, length) => {
  if (text.length <= length) return text;
  return text.substring(0, length) + '...';
};

onMounted(() => {
  if (activeCategory.value !== 'all') {
    filterByCategory(activeCategory.value);
  }
  startCarousel();
});
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-6 py-8">
      <div class="flex justify-between items-center mb-5 relative">
        <h1 class="text-2xl md:text-4xl font-bold text-[#D4A017] w-full text-center">
          BERITA KABUPATEN MADIUN
        </h1>
      </div>
      <p class="text-lg md:text-xl text-center text-black mb-5 max-w-[85%] mx-auto">Temukan informasi menarik seputar pemerintah kabupaten madiun</p>
      <div class="h-[3px] bg-[#268B79] w-1/5 mx-auto"></div>

      <div class="flex justify-end mt-4">
        <NewsButton href="/news/create" class="flex items-center gap-2 bg-[#268B79] text-white px-4 py-2 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="color: #ffffff;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          Add
        </NewsButton>
      </div>
    </div>


    <!-- Carousel Section -->
    <div class="container mx-auto px-6 mb-12 relative">
      <div class="relative w-full h-[300px] md:h-[400px] rounded-xl overflow-hidden">
        <div v-for="(news, index) in featuredNews" :key="news.id"
          class="absolute w-full h-full transition-transform duration-500 ease-in-out"
          :class="{ 'translate-x-0': index === currentSlide, 'translate-x-full': index > currentSlide, '-translate-x-full': index < currentSlide }">
          <div class="relative w-full h-full rounded-xl overflow-hidden">
            <img :src="`/storage/${news.gambar_utama}`" :alt="news.judul"
              class="w-full h-full object-cover rounded-xl" />
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/50 to-black/80"></div>
            <div class="absolute bottom-0 left-0 right-0 p-4 md:p-8">
              <h2 class="text-xl md:text-2xl font-bold text-white mb-2">{{ news.judul }}</h2>
              <div class="text-white/90 mb-4 line-clamp-2 text-sm md:text-base" v-html="news.isi_berita"></div>
              
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 md:gap-6 text-white/90 text-xs md:text-sm">
                  <div class="flex items-center gap-2">
                    <svg class="size-4 md:size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#268B79">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="hidden md:inline">{{ news.penulis }}</span>
                    <span class="md:hidden">{{ truncateText(news.penulis, 10) }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <svg class="size-4 md:size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#268B79">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ news.tanggal_terbit }}
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="w-2 h-2 md:w-3 md:h-3 bg-yellow-500 rounded-full"></span>
                    <span class="hidden md:inline">{{ news.kategori.join(', ') }}</span>
                    <span class="md:hidden">{{ truncateText(news.kategori[0], 10) }}</span>
                  </div>
                </div>
                <Link :href="`/news/${news.id}`" 
                      class="flex items-center gap-2 px-1 py-1 md:px-4 md:py-2 text-white rounded text-xs md:text-sm">
                  <span>Selengkapnya</span>
                  <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="#268B79" viewBox="0 0 20 20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Filter -->
    <div class="container mx-auto px-6 mb-8">
      <div class="flex flex-wrap gap-3">
        
        <!-- All News Button -->
        <NewsButton 
          href="/news" 
          :isActive="activeCategory === 'all'"
        >
          Semua Berita ({{ props.totalNewsCount }})
        </NewsButton>

        <!-- Category Buttons -->
        <NewsButton 
          v-for="category in props.categoriesWithCount"
          :key="category.category"
          :href="`/news/${category.category}`"
          :isActive="isActive(category.category)"
        >
          {{ category.category }} ({{ category.count }})
        </NewsButton>

      </div>
    </div>
      

    <!-- Container untuk Page Size Selector dan Search Bar -->
    <div class="container mx-auto px-6 mb-6">
      <div class="flex flex-col md:flex-row justify-between gap-4">
        <!-- Page Size Selector -->
        <div class="flex items-center gap-2">
          <label for="pageSize" class="text-gray-600 whitespace-nowrap">Show items:</label>
          <select
            id="pageSize"
            v-model="pageSize"
            @change="handlePageSizeChange"
            class="form-select w-32 rounded-full border-gray-300 shadow-sm focus:border-[#396C6D] focus:ring focus:ring-[#396C6D] focus:ring-opacity-50 text-gray-700 cursor-pointer hover:border-[#396C6D] transition-colors duration-200"
          >
            <option v-for="size in pageSizeOptions" :key="size" :value="size">
              {{ size }} items
            </option>
          </select>
        </div>

        <!-- Search Bar -->
        <div class="flex w-full md:w-80 relative"> 
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari berita..."
            class="w-full px-4 py-2 border rounded-full focus:outline-none focus:ring-2"
            :class="{'focus:ring-[#396C6D] border-[#396C6D]': true}"
          />
          <div class="absolute right-1 top-1/2 transform -translate-y-1/2">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              fill="#396C6D" 
              class="size-9 cursor-pointer"
              @click="searchNews"
            >
              <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
              <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Tanggal section moved after search -->
    <div class="container mx-auto px-6 mb-6">
      <h3 class="flex items-center gap-2">
        <span class="text-4xl font-bold underline text-[#396C6D]">
          {{ Object.keys(groupedNews)[0]?.split(' ')[0] }}
        </span>
        <span class="text-lg font-light text-[#396C6D]">
          {{ Object.keys(groupedNews)[0]?.split(' ')[1] }} {{ Object.keys(groupedNews)[0]?.split(' ')[2] }}
        </span>
      </h3>
    </div>

  <!-- News Cards with Pagination -->
  <div class="container mx-auto px-6 mb-12">

    <div v-for="(newsGroup, date) in groupedNews" :key="date" class="mb-12">
      <h3 v-if="date !== Object.keys(groupedNews)[0]" class="flex items-center gap-2 mb-6">
        <span class="text-4xl font-bold underline text-[#396C6D]">
          {{ date.split(' ')[0] }}
        </span>
        <span class="text-lg font-light text-[#396C6D]">
          {{ date.split(' ')[1] }} {{ date.split(' ')[2] }}
        </span>
      </h3>
      <div class="space-y-6">
        <div v-for="news in newsGroup" :key="news.id" class="bg-[#F9F6EE] rounded-lg overflow-hidden p-4">
          <!-- Changed to flex container with items-stretch -->
          <div class="flex flex-col md:flex-row gap-6 h-full">
            <!-- Image container with self-center for vertical alignment -->
            <div class="md:w-72 w-full flex-shrink-0 self-center">
              <div class="rounded-lg overflow-hidden">
                <img 
                  v-if="news.gambar_utama"
                  :src="`/storage/${news.gambar_utama}`"
                  :alt="news.judul"
                  class="w-full h-48 object-cover"
                />
              </div>
            </div>

            <!-- Content container with flex and justify-between -->
            <div class="flex-1 flex flex-col">
              <!-- Main content -->
              <div>
                <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ news.judul }}</h4>
                <div class="text-gray-600 mb-3 line-clamp-3" v-html="news.isi_berita"></div>
                <p class="text-gray-600 mb-4 line-clamp-2">{{ news.excerpt }}</p>

                <!-- Author and date info -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                  <div class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#268B79" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    {{ news.penulis }}
                  </div>
                  <div class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#268B79" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                    </svg>
                    {{ news.tanggal_terbit }}
                  </div>
                </div>

                <!-- Categories -->
                <div class="flex items-center gap-2 mb-2">
                  <span class="bg-[#EBE3D8] px-2 py-1 rounded-full text-black text-sm lg:text-s flex items-center gap-2 overflow-hidden md:overflow-visible whitespace-nowrap md:whitespace-normal text-ellipsis max-w-[200px] md:max-w-none w-full md:w-auto">
                    <span class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full flex-shrink-0"></span>
                    <span class="truncate md:whitespace-normal md:overflow-visible w-full">{{ news.kategori.join(', ') }}</span>
                  </span>
                </div>
              </div>

              <!-- Action buttons container -->
              <div class="flex justify-end mt-auto">
                <div class="flex items-center gap-3">
                  <Link :href="`/news/edit/${news.id}`" class="px-1 py-2 rounded text-sm flex items-end gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="color: #396C6D;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                  </Link>
                  <button @click="deleteNews(news.id)" class="px-3 py-2 rounded text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="color: #BD2222;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.021-2.09 2.201v.916H16.34z" />
                    </svg>
                  </button>
                  <NewsButton :href="`/news/${news.id}`">
                    Selengkapnya
                  </NewsButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pagination Controls -->
    <div class="flex justify-center items-center gap-2 mt-8">
      <!-- First Page -->
      <button
        @click="changePage(1)"
        :disabled="currentPage === 1"
        class="p-2 rounded-md transition-all duration-200"
        :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Previous Page -->
      <button
        @click="changePage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="p-2 rounded-md transition-all duration-200"
        :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L8.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Page Numbers -->
      <div class="flex gap-1">
        <button
          v-for="page in pageNumbers"
          :key="page"
          @click="changePage(page)"
          class="min-w-[2.5rem] h-10 rounded-md transition-all duration-200 text-sm font-medium"
          :class="currentPage === page ? 
            'bg-[#268B79] text-white' : 
            'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        >
          {{ page }}
        </button>
      </div>

      <!-- Next Page -->
      <button
        @click="changePage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="p-2 rounded-md transition-all duration-200"
        :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M7.293 15.707a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L11.586 10l-4.293 4.293a1 1 0 000 1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Last Page -->
      <button
        @click="changePage(totalPages)"
        :disabled="currentPage === totalPages"
        class="p-2 rounded-md transition-all duration-200"
        :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L8.586 10l-4.293 4.293a1 1 0 000 1.414zm6 0a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L14.586 10l-4.293 4.293a1 1 0 000 1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <!-- Page Information -->
    <div class="text-center mt-4 text-gray-600">
      Showing {{ ((currentPage - 1) * pageSize) + 1 }} to {{ Math.min(currentPage * pageSize, newsItems.length) }} of {{ newsItems.length }} items
    </div>
  </div>
</div>
</template>