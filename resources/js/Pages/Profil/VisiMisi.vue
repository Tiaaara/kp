<template>
   <Navbar />
  <div class="w-full min-h-screen bg-white relative flex flex-col justify-center items-center text-white">

    <!-- Breadcrumb -->
    <div class="absolute top-4 left-6 text-sm text-[#99CBC0] font-bold">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Visi Misi 
    </div>

    <!-- Content Section -->
    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-center text-4xl text-[#D4A017] font-bold" >VISI MISI</h1>

      <!-- New section for the image and yellow lines -->
      <div class="flex items-center justify-center mt-3 w-full px-4">
        <div class="h-[2px] bg-[#D4A017] flex-1"></div>
        <img src="/public/image/madiun_silat.png" alt="Gambar Lambang Daerah" class="mx-4 h-14 w-auto">
        <div class="h-[2px] bg-[#D4A017] flex-1"></div>
      </div>

      <p class="text-xl text-center text-[#D4A017] mb-5 max-w-[85%] mx-auto">
        Visi Misi Bupati dan Wakil Bupati Madiun Periode 2018 – 2023
      </p>

<Card class="p-6 mt-0 text-white relative bg-cover bg-center bg-no-repeat" style="background-image: url('/image/background.png');">
    <div class="p-6">
        <!-- Bagian atas dengan dua gambar yang berada di tengah -->
        <div class="flex justify-center items-center mb-4" style="gap: 200px;">
            <img src="/public/image/bupati.png" alt="Gambar Lambang 1" class="w-54 h-auto ml-60">
            <img src="/public/image/bupati.png" alt="Gambar Lambang 2" class="w-54 h-auto mr-60">
        </div>

        <!-- Tabel di bawah foto -->
        <div class="mt-6">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="border-t">
                        <th class="border-0 p-6 text-center w-1/3">VISI</th>
                        <td class="border-0 p-6 text-left italic">“Terwujudnya Kabupaten Madiun Yang Aman, Mandiri, Sejahtera dan Berakhlak”</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <th class="border-0 p-6 text-center w-1/3 align-top">MISI</th>
                        <td class="border-0 p-6">
                            <ol class="list-decimal pl-4">
                                <li>Mewujudkan rasa aman bagi seluruh masyarakat dan aparatur Pemerintah Kabupaten Madiun.</li>
                                <li>Mewujudkan Aparatur Pemerintah yang Profesional untuk meningkatkan Pelayanan Publik.</li>
                                <li>Meningkatkan Pembangunan Ekonomi yang Mandiri Berbasis Agrobisnis, Agroindustri, dan Pariwisata yang Berkelanjutan.</li>
                                <li>Meningkatkan Kesejahteraan yang Berkeadilan.</li>
                                <li>Mewujudkan Masyarakat Berakhlak Mulia dengan Meningkatkan Kehidupan Beragama, Menguatkan Budaya, dan Mengedepankan Kearifan Lokal.</li>
                            </ol>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</Card>

    </div>
  </div>
  <Footer />
</template>

<script>
import Card from '@/Components/Card.vue';
import { Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
export default {
  components: {
    Card,
    Navbar,
    Footer,
    Link,
  },
};
</script>
