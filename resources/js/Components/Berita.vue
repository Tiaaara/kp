<template>
    <div class="bg-[#268B79] p-8">
      <!-- Judul Berita Terkini dengan Garis Kuning -->
      <div class="flex items-center mx-15 mb-4">
        <div class="text-white text-4xl font-bold">Berita Terkini</div>
        <div class="border-t-4 border-yellow-500 flex-1 ml-16"></div>
      </div>
  
      <div class="grid grid-cols-3 gap-4 mx-15 my-15">
        <div v-if="mainNews" class="bg-white p-4 rounded-lg shadow-md col-span-2">
          <div>
            <h3 
              class="text-xl font-semibold mb-2 cursor-pointer hover:underline" 
              @click="goToNewsDetail(mainNews.id)"
            >
              {{ mainNews.judul }}
            </h3>
  
            <div class="flex items-center text-sm text-gray-500 mb-4">
              <div class="flex items-center mr-4">
                <i class="fas fa-user mr-2"></i>
                <p>{{ mainNews.penulis }}</p>
              </div>
              <div class="flex items-center mr-4">
                <i class="fas fa-calendar-alt mr-2"></i>
                <p>{{ mainNews.created_at }}</p>
              </div>
              <div class="flex items-center">
                <i class="fas fa-tag mr-2"></i>
               <p v-if="Array.isArray(mainNews.kategori)">
  {{ mainNews.kategori.join(', ') }}
</p>
<p v-else>
  {{ mainNews.kategori || 'Kategori tidak tersedia' }}
</p>

              </div>
            </div>
  
            <div class="flex">
              <div class="w-1/2 pr-1">
                <img 
                  v-if="mainNews.gambar_utama" 
                  :src="mainNews.gambar_utama" 
                  class="w-full h-60 object-cover rounded-md cursor-pointer" 
                  @click="goToNewsDetail(mainNews.id)" 
                  alt="Gambar Berita Utama" 
                />
              </div>
              <div class="w-1/2 pl-3">
                <p class="text-gray-700" v-html="mainNews.isi_berita"> </p> 
              </div>
            </div>
  
            <!-- Carousel Berita -->
            <div v-if="newsCards.length" class="mt-6 relative col-span-3">
              <div class="overflow-hidden">
                <div class="flex transition-transform duration-500" :style="carouselStyle">
                    <div v-for="news in newsCards" :key="news.id" class="bg-[#F9F6EE] rounded-lg shadow-md w-1/2 min-w-[49.4%] cursor-pointer mr-1.5 mb-1 overflow-hidden">
                    <img 
                      v-if="news.gambar_utama" 
                      :src="news.gambar_utama" 
                      class="rounded-md w-full h-32 object-cover mb-2" 
                      alt="Gambar Berita" 
                    />
  
                    <!-- Kategori dengan style sesuai gambar -->
                    <div class="flex items-center text-sm text-gray-500 mb-3 ml-1">
                      <div 
                        v-for="(kategori, i) in news.kategori" 
                        :key="i"
                        class="flex items-center bg-[#F3ECE1] text-[#396C6D] rounded-full px-3 py-1 text-sm mr-2"
                      >
                        <span class="w-3 h-3 bg-[#396C6D] rounded-full mr-2"></span>
                        {{ kategori }}
                      </div>
                    </div>
  
                    <!-- Judul Berita -->
                    <h3 class="text-lg font-semibold mb-2 ml-4 text-[#396C6D]">
                      {{ news.judul }}
                    </h3>
  
                    <!-- Penulis dan Tanggal -->
                    <div class="flex items-center text-sm text-gray-500 mb-4 ml-4">
                      <i class="fas fa-user mr-2"></i>
                      <p class="text-[#396C6D]">{{ news.penulis }}</p>
                      <p class="ml-4 text-[#396C6D]">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ news.created_at }}
                      </p>
                    </div>
  
                    <!-- Isi Berita -->
                    <p class="text-sm text-gray-700 line-clamp-3 ml-4 mr-4">
                      {{ news.isi_berita }}
                    </p>
                  </div>
                </div>
              </div>
  
              <!-- Tombol Navigasi -->
              <button 
                v-if="currentIndex > 0" 
                @click="prevSlide" 
                class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-md"
              >
                ❮
              </button>
              <button 
                v-if="currentIndex < this.maxIndex" 
                @click="nextSlide" 
                class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-md"
              >
                ❯
              </button>
            </div>
          </div>
        </div>
  
        <!-- Widget Kominfo -->
        <div id="gpr-kominfo-widget-container" class="bg-white p-4 rounded-lg shadow-md"></div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      mainNews: Object,
      newsCards: Array,
    },
    data() {
      return {
        currentIndex: 0,
        autoSlideInterval: 3000, // Interval dalam milidetik (3 detik)
        autoSlideTimer: null, // Timer untuk auto slide
      };
    },
    computed: {
      maxIndex() {
        return Math.max(0, this.newsCards.length - 2);
      },
      carouselStyle() {
        return `transform: translateX(-${this.currentIndex * 50}%)`;
      },
    },
    methods: {
      goToNewsDetail(newsId) {
        if (newsId) {
          this.$router.push({ name: 'news-detail', params: { id: newsId } });
        } else {
          console.error('News ID is missing!');
        }
      },
      prevSlide() {
        if (this.currentIndex > 0) {
          this.currentIndex--;
        }
      },
      nextSlide() {
        if (this.currentIndex < this.maxIndex) {
          this.currentIndex++;
        } else {
          this.currentIndex = 0; // Jika sudah mencapai slide terakhir, kembali ke awal
        }
      },
      startAutoSlide() {
        this.autoSlideTimer = setInterval(() => {
          this.nextSlide();
        }, this.autoSlideInterval);
      },
      stopAutoSlide() {
        if (this.autoSlideTimer) {
          clearInterval(this.autoSlideTimer);
          this.autoSlideTimer = null;
        }
      },
    },
    mounted() {
      const script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://widget.kominfo.go.id/gpr-widget-kominfo.min.js';
      document.head.appendChild(script);
  
      this.startAutoSlide(); // Mulai auto-slide saat komponen dimuat
    },
    beforeDestroy() {
      this.stopAutoSlide(); // Bersihkan interval saat komponen dihancurkan
    },
  };
  </script>
  