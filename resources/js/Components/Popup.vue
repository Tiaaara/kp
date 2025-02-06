<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    popup: Object, // Data popup dari parent
});

// Konversi string image_popup menjadi array
const images = computed(() => {
    return props.popup?.image_popup ? props.popup.image_popup.split(',') : [];
});

const showPopup = ref(true);
const currentIndex = ref(0);

// Fungsi untuk slide ke gambar berikutnya
const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % images.value.length;
};

// Fungsi untuk slide ke gambar sebelumnya
const prevImage = () => {
    currentIndex.value = (currentIndex.value - 1 + images.value.length) % images.value.length;
};
</script>

<template>
    <div v-if="showPopup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg relative w-96 z-50">
            <!-- Tombol Close -->
            <button @click="showPopup = false" class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded">
                X
            </button>
            
            <!-- Slide Show Gambar -->
            <div class="relative">
                <img 
                    v-if="images.length > 0" 
                    :src="`/storage/${images[currentIndex]}`" 
                    alt="Popup Image" 
                    class="w-full h-auto rounded transition-all duration-500 ease-in-out"
                >
                <p v-else class="text-center text-gray-500">Tidak ada gambar</p>

                <!-- Tombol Navigasi (Hanya tampil jika lebih dari 1 gambar) -->
                <button 
                    v-show="images.length > 1" 
                    @click="prevImage" 
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black text-white p-2 rounded-l"
                >
                    &lt;
                </button>
                <button 
                    v-show="images.length > 1" 
                    @click="nextImage" 
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black text-white p-2 rounded-r"
                >
                    &gt;
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
button {
    cursor: pointer;
    transition: background-color 0.3s;
}
button:hover {
    background-color: rgba(0, 0, 0, 0.7);
}
</style>
