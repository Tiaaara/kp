<template>
 <div>
            <button @click="showPopup = true" class="btn-popup">Show Popup</button>

            <!-- Popup Modal -->
            <div v-if="showPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-xl shadow-xl relative max-w-lg w-full">
                    <button @click="closePopup" class="absolute top-2 right-2 bg-gray-200 p-2 rounded-full">&times;</button>
                    <h2 class="text-xl font-bold mb-4">Selamat Datang!</h2>
                    <p class="mb-4">Ini adalah popup yang muncul saat halaman beranda terbuka.</p>
                    <div class="relative">
                        <button @click="prevImage" class="absolute left-0 bg-gray-300 p-2 rounded-full">&#9664;</button>
                        <img :src="popups[currentIndex].image_url" alt="Popup Image" class="w-full h-auto rounded" />
                        <button @click="nextImage" class="absolute right-0 bg-gray-300 p-2 rounded-full">&#9654;</button>
                    </div>
                    <button @click="closePopup" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Tutup</button>
                </div>
            </div>
        </div>
    `
</template>

<script>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

export default {
    setup() {
        const page = usePage();
        const popups = ref(page.props.popups);
        const showPopup = ref(false);
        const currentIndex = ref(0);

        onMounted(() => {
            if (popups.value.length > 0) {
                showPopup.value = true;
            }
        });

        const closePopup = () => {
            showPopup.value = false;
        };

        const nextImage = () => {
            currentIndex.value = (currentIndex.value + 1) % popups.value.length;
        };

        const prevImage = () => {
            currentIndex.value = (currentIndex.value - 1 + popups.value.length) % popups.value.length;
        };

        return {
            popups,
            showPopup,
            closePopup,
            currentIndex,
            nextImage,
            prevImage
        };
    },}
</script>

<style scoped>
.btn-popup {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}
</style>
