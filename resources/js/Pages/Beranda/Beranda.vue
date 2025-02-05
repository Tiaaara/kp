<template>
  <div>
  
    <Navbar />

    <!-- Video Section -->
    <VideoSection :videoSource="videoSource" />

    <!-- Profile Section -->
    <ProfileSection />
<Berita
:mainNews="mainNews" 
:newsCards="newsCards"/>
    <!-- Visitor Section -->
 
    <Layanan/>
    <VideoGallery/>
    <Visitor
      :visitorCount="visitorCount"
      :todayVisitorCount="todayVisitorCount"
      :monthlyVisitorCount="monthlyVisitorCount"
      :yearlyVisitorCount="yearlyVisitorCount"
    />
  <Footer/>
  <Popup v-if="showPopup" @closePopup="showPopup = false" />
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import VideoSection from '@/Components/VideoSection.vue';
import ProfileSection from '@/Components/ProfileSection.vue';
import Visitor from "@/Components/Visitor.vue";
import Footer from "@/Components/Footer.vue";
import Berita from "@/Components/Berita.vue";
import Layanan from "@/Components/Layanan.vue";
import VideoGallery from "@/Components/VideoGallery.vue";
import videoSource from '/Users/apple/Documents/KerjaPraktik/pemkab/public/vid/VideoBeranda.mp4';
import Popup from "@/Components/Popup.vue";

export default {
  components: {
    Navbar,
    VideoSection,
    ProfileSection,
    Visitor,
    Footer,
    Berita,
    Layanan,
    VideoGallery,
    Popup,
  },
  props: {
    visitorCount: Number,
    todayVisitorCount: Number,
    monthlyVisitorCount: Number,
    yearlyVisitorCount: Number,
    mainNews: Object,
    newsCards: Array,
  },
  data() {
    return {
      videoSource,
    };
  },
};
</script>
